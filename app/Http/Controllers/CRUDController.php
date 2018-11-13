<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use App\Cart\CartItem;
use App\Cart\EasyCart;
use App\Cart;
use App\Transaction;
use Carbon\Carbon;
class CRUDController extends Controller
{
  protected $url;

  public function __construct(UrlGenerator $url)
  {
    $this->url = $url;
    $this->cart = session('cart');
  }
  public function store(Request $request)
  {
    $user = Auth::user();
    $input      = $request->except(['_token']);
    $request->session()->push('user_id', session()->getId());
    if ($user){
      $input['user_type'] = 'user';
      $input['user_id'] = $user->id;
    }else{
      $input['user_type'] = 'guest';
      $input['user_id'] = session()->getId();
    }
    $res = [];
    foreach ($request->themeID as $key => $value){
      $intval= (int)$value;
      $input['input'][$key]["theme_id"]              = $value;
      $input['input'][$key]['brand_id']              = 1;
      $input['input'][$key]['sender']                = $request->sender;
      $input['input'][$key]['name']                  = $request->name;
      $input['input'][$key]['address']               = $request->address;
      $input['input'][$key]['mobile']                = $request->mobile;
      $input['input'][$key]['option']                = $input['option'];
      $input['input'][$key]['user_type']             = $input['user_type'];
      $input['themes'] = DB::table('denominations')
      ->leftJoin('themes', 'themes.denomination_id', '=', 'denominations.id')
      ->where('themes.id',$intval)
      ->get();
      foreach ($input['themes'] as $key3 => $value){
        $input['input'][$key]['denomination'] = (int)$value->denomination;
      }
    }
    foreach ($request->quantityVal as $key2 => $value){
      $input['input'][$key2]['quantity'] =  (int)$value;
      $input['input'][$key2]['total'] = $input['input'][$key2]['quantity'] * $input['input'][$key2]['denomination'];
    }
    // dd($request);
    foreach ($input['input'] as $key => $value){
      if($input['input'][$key]['quantity'] != 0){
        $res[] =[
          'theme_id'            => (int)$input['input'][$key]['theme_id'],
          'brand_id'            => 1,
          'user_id'             => $input['user_id'],
          'sender'              => $input['input'][$key]['sender'],
          'name'                => $input['input'][$key]['name'],
          'quantity'            => $input['input'][$key]['quantity'],
          'address'             => $input['input'][$key]['address'],
          'mobile'              => $input['input'][$key]['mobile'],
          'total'               => $input['input'][$key]['total'],
          'user_type'           => $input['input'][$key]['user_type']
        ];
      }
    }
    // dd($res);
    if(!$res || $res == []){
      switch($request->submitbutton) {
        case 'save':
        return back()->with('error', 'Please enter a Quantity');
        break;
        case 'save_cart':
        return back()->with('error', 'Please enter a Quantity');
        break;
      }
    }else{
      return $this->storeCart($res,$input,$request);
    }
  }

  public function storeCart($res,$input,$request)
  {
    Cart::insert($res);
    if($request->type =="json"){
      return $data;
    }
    switch($request->submitbutton) {
      case 'save':
      return back()->with('success', 'Added to Cart Succesfully!');
      break;
      case 'save_cart':
      return redirect('/confirm');
      break;
    }
  }

  public function update(Request $request)
  {
    // dd($request);
    $data['edit'] = 'edit';
    //get total amount per item
    $input      = $request;
    $input['total'] = $request->total;
    $input['brand_id'] = 1;
    $input['user_id'] = 1;

    $messages   = [
      'required' => 'The :attribute is required',
    ];

    foreach ($input['quantityVal'] as $key2 => $value){
      $input['quantity'] =  (int)$value;
      $input['total'] = (int)$value*(int)$request->denomination;
    }
    foreach ($input['themeID'] as $key2 => $value){
      $input['theme_id'] =  (int)$value;
    }
    $input      = $request->except(['_token','submitbutton','quantityVal','themeID','option','admin','denomination','admins']);
    // dd($input,(int)$request->id);
    DB::table('carts')
    ->where('id', (int)$request->id)
    ->update($input);

    if($request->type =="json"){
      return $data;
    }

    switch($request->submitbutton) {
      case 'update':
      return back()->with('success', 'Updated Cart Item Succesfully!'); //Update Cart
      break;
      case 'update_cart':
      return redirect('/confirm'); //Update and Checkout
      break;
    }
  }

  public function deleteCart($id)
  {
    //for logged on user
    $data['cart'] = DB::table('carts')
    ->where('id', $id)
    ->delete(); //delete data from db table.cart based on item id

    return back()->with('success', 'Removed Item From Cart!');
  }

  public function clearCart(Request $request){
    $data = DB::table('carts')
    ->delete();
    return redirect('/')->with('success', 'Cleared Cart!');
  }

  public function transaction(Request $request)
  {
    $user = Auth::user();
    $input      = $request->except(['_token','confirm','button','mobile']);
    $data['cart'] = DB::table('carts')
    ->join('themes', 'themes.id', '=', 'carts.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->select('carts.*','denominations.denomination','themes.theme')
    ->where('user_id',$user->id)
    ->get();
    $mytime = Carbon::now()->format('Y-m-d H:i:s');
    $ymd = str_replace('-', '',$mytime);
    $random = preg_replace('/[^A-Za-z0-9\-]/', '', $ymd);
    $input['reference_num'] = (int)$random;
    $input['user_id'] = $user->id;
    foreach ($data['cart'] as $key => $value){
      if(!$value->address){
        $string = str_replace('-', '', $value->mobile);
        $mobile = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        $input['mobile'] = $mobile;
        $data1 = [
          "organization_id"=> 7355,
          "recipient_type"=> "mobile_number",
          "mobile_numbers"=> [$mobile],
          "message"=> "Hi ".$value->name.". You purchased a Mercury Gift Card worth P".$value->denomination.".",
          "sender_id"=> "engageSPARK",
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://start.engagespark.com/api/v1/messages/sms",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30000,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($data1),
          CURLOPT_HTTPHEADER => array(
            "accept: */*",
            "accept-language: en-US,en;q=0.8",
            "content-type: application/json",
            "Authorization: Token 4731371c9df6da26988688315391fcc49a214a60"
          ),
        ));
        $response = curl_exec($curl);
        $res = json_decode($response);
        $err = curl_error($curl);
        curl_close($curl);
      }else{
        $string = str_replace('-', '', $value->mobile);
        $mobile = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        $input['mobile'] = $mobile;
      }
    }
    Transaction::insert($input);
    // TODO: only update latest transaction
    Cart::where('user_id', $user->id)->update(array('transaction_id' => $input['reference_num']));
    return redirect('/')->with('success', 'Payment Successful!');
  }
}
