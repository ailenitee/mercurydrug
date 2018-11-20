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
    //payment
    $user = Auth::user();
    $input      = $request->except(['_token','confirm','button']);
    $data['cart'] = DB::table('carts')
    ->join('themes', 'themes.id', '=', 'carts.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->select('carts.*','denominations.denomination','themes.theme')
    ->where('user_id',$user->id)
    ->where('transaction_id','pending')
    ->orWhereNull('transaction_id')
    ->get();
    $myvalue = $input['name'];
    $name= explode(' ',trim($myvalue));
    $lastname= $name[count($name)-1];
    $mytime = Carbon::now()->format('Y-m-d H:i:s');
    $ymd = str_replace('-', '',$mytime);
    $random = preg_replace('/[^A-Za-z0-9\-]/', '', $ymd);

    $input['user_id'] = $user->id;
    foreach ($data['cart'] as $key => $value){
      $items[] = [
        "name"=> "Gift Card ".$value->denomination,
        "quantity"=> $value->quantity,
        "amount"=> $value->denomination
      ];
    }
    $cart = [
      'amount' => (int)$input['total'],
      'redirect_url' => url('/transaction/success'),
      "user"=> [
        "id"=> "".$user->id."",
        "firstName"=> $name[0],
        "lastName"=> $lastname,
        "email"=> $input['email'],
        "mobile"=> $input['mobile'],
        "address1"=> $input['Address'],
        "city"=> $input['city'],
        "region"=>"Metro Manila",
        "country"=>"PH",
        "zip"=> 1601,
      ],
      "items"=>$items,
    ];
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://fxyccwxx7b.execute-api.us-east-1.amazonaws.com/dev/create",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30000,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($cart),
      CURLOPT_HTTPHEADER => array(
        // Set here required headers
        "accept: */*",
        "accept-language: en-US,en;q=0.8",
        "content-type: application/json",
        "Authorization: 977XGRDfwsJae4fqesJX5KzUcgFFyRDU"
      ),
    ));
    $response = curl_exec($curl);
    $res = json_decode($response);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      echo "cURL Error #:" . $err;
    }else {
      if($res->success == true){
        $input['reference_num'] = $res->reference;
        Transaction::insert($input);
        Cart::where('user_id', $user->id)->WhereNull('transaction_id')->update(array('transaction_id' => 'pending'));
        return redirect('https://fxyccwxx7b.execute-api.us-east-1.amazonaws.com/dev'.$res->redirect_url);
      }else{
        return back()->with('error', 'Something went wrong');
      }
    }
    // // TODO: only update latest transaction
    // // TODO: mobile format
  }

  public function success(Request $request){
    //if payment Successful
    // TODO: fix payment succesful
    // TODO: fix trans id
    // try {
    $user = Auth::user();
    $tid =request()->tid;
    Transaction::where('reference_num', $tid)->update(array('status' => 'paid'));
    Cart::where('user_id', $user->id)->where('transaction_id','pending')->update(array('transaction_id' => $tid));

    $data['cart'] = DB::table('carts')
    ->join('themes', 'themes.id', '=', 'carts.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->select('carts.*','denominations.denomination','themes.theme')
    ->where('user_id',$user->id)
    ->where('transaction_id',$tid)
    ->get();
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
    return redirect('/')->with('success', 'Payment Successful!');
    // } catch (\Exception $e) {
    //   // dd($e);
    //   return redirect('/')->with('error', 'Payment Error!');
    // }
  }
  public function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
  }
}
