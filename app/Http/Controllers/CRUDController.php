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
use App\Logs;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
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

    //todo insert, match tbl_td and tbl_tc, get denomination amount,
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
    }
    foreach ($request->denomID as $key3 => $value){
      $input['input'][$key3]['denomination_id'] =  (int)$value;
      $input['input'][$key3]['dm_tbl'] = DB::table('denominations')
      ->where('id',(int)$value)
      ->get();
      foreach ($input['input'][$key3]['dm_tbl'] as $key5 => $value){
        $input['input'][$key3]['amount']  = $value->amount;
      }
      // dd($value);
      $input['input'][$key3]['temp_den'] = DB::table('template_denominations')
      ->where('brand_id',1)
      ->where('denomination_id',(int)$value->id)
      ->get();
      foreach ($input['input'][$key3]['temp_den'] as $key6 => $value){
        $input['input'][$key3]['template_denomination_id']  = $value->id;
      }
    }

    foreach ($request->themeID as $key3 => $value){
      $input['input'][$key3]['temp_cat'] = DB::table('template_categories')
      ->where('brand_id',1)
      ->where('category_id',(int)$value)
      ->get();
      foreach ($input['input'][$key3]['temp_cat'] as $key6 => $value){
        $input['input'][$key3]['template_category_id']  = $value->id;
      }
    }
    // dd($input);
    foreach ($request->quantityVal as $key2 => $value){
      $input['input'][$key2]['quantity'] =  (int)$value;
    }
    // dd($input);
    foreach ($input['input'] as $key => $value){
      if($input['input'][$key]['quantity'] != 0){
        $res[] =[
          'brand_id'                    => 1,
          'user_id'                     => $input['user_id'],
          'sender'                      => $input['input'][$key]['sender'],
          'name'                        => $input['input'][$key]['name'],
          'quantity'                    => $input['input'][$key]['quantity'],
          'address'                     => $input['input'][$key]['address'],
          'mobile'                      => $input['input'][$key]['mobile'],
          'total'                       => $input['input'][$key]['quantity'] * $input['input'][$key]['amount'],
          'user_type'                   => $input['input'][$key]['user_type'],
          'fulfillment_type'            => $input['input'][$key]['option'],
          'template_denomination_id'    => $input['input'][$key]['template_denomination_id'],
          'template_category_id'        => $input['input'][$key]['template_category_id']
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
    ->join('template_categories', 'carts.template_category_id', '=', 'template_categories.id')
    ->join('categories', 'template_categories.category_id', '=', 'categories.id')
    ->join('template_denominations', 'carts.template_denomination_id', '=', 'template_denominations.id')
    ->join('denominations', 'template_denominations.denomination_id', '=', 'denominations.id')
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
        "name"=> "Gift Card ".$value->amount,
        "quantity"=> $value->quantity,
        "amount"=> $value->amount,
        "id"=> $value->id
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
        "city"=> $input['city']
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
        // $input['reference_num'] = $res->reference;
        foreach ($data['cart'] as $key => $value){
          $itemsTrans[] = [
            "status"=> "pending",
            "cart_id"=> $value->id,
            "client_id"=> 1,
            "reference_num"=>$res->reference
          ];
        }
        // foreach ($itemsTrans as $key => $value){
        Transaction::insert($itemsTrans);
        // }
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
    try {
      $current_time = Carbon::now()->toDateTimeString();
      $user = Auth::user();
      $tid =request()->tid;
      Transaction::where('reference_num', $tid)->update(array('status' => 'paid'));
      Cart::where('user_id', $user->id)->where('transaction_id','pending')->update(array('transaction_id' => $tid)); 
      $data['cart'] = DB::table('carts')
      ->join('template_categories', 'carts.template_category_id', '=', 'template_categories.id')
      ->join('categories', 'template_categories.category_id', '=', 'categories.id')
      ->join('template_denominations', 'carts.template_denomination_id', '=', 'template_denominations.id')
      ->join('denominations', 'template_denominations.denomination_id', '=', 'denominations.id')
      ->join('brands', 'carts.brand_id', '=', 'brands.id')
      ->where('user_id',$user->id)
      ->where('transaction_id',$tid)
      ->get();
      // dd($data['cart']);
      foreach ($data['cart'] as $key => $value){
        if(!$value->address){
          // get epin code from giftcard service
          $gservice = array(
            "template" =>   $value->epin_brand,
            "amount"=>    $value->amount,
            "userId"=>    $value->user_id,
            "name"=>      $value->name,
            "background"=> $value->url,
            "timestamp"=> $current_time,
            "theme"=>     "".$value->template_category_id.""
          );
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://nhs935x875.execute-api.us-east-1.amazonaws.com/production/generate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($gservice),
            CURLOPT_HTTPHEADER => array(
              "accept: */*",
              "accept-language: en-US,en;q=0.8",
              "Authorization:  DSPL9aJwYM3XbTsDue8xsUPwbXq6rb3WcWGjfRZ7JzwBjgcZ",
            ),
          ));
          $response = curl_exec($curl);
          $res = json_decode($response);
          $err = curl_error($curl);
          curl_close($curl);
          //send text message
          $string = str_replace('-', '', $value->mobile);
          $mobile = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
          $input['mobile'] = $mobile;
          $data1 = [
            "messages" => array(array(
              "source"=> "php",
              "from"=> "Mercury",
              "body"=> "Hi ".$value->name.". You purchased a Mercury Gift Card worth P".$value->amount.".<br><br>Here is your verification code ".$tid.".",
              "to"=> $input['mobile']
            ))
          ];
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rest.clicksend.com/v3/sms/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data1),
            CURLOPT_USERPWD => "glyphleni:Pu&5@MWHlQ45",
            CURLOPT_HTTPHEADER => array(
              "accept: */*",
              "accept-language: en-US,en;q=0.8",
              "content-type: application/json",
              "Authorization: Basic ". base64_encode("glyphleni:Pu&5@MWHlQ45")
            ),
          ));
          $response = curl_exec($curl);
          $res = json_decode($response);
          $err = curl_error($curl);
          curl_close($curl);
          // dd($res,json_encode($data1),$data['cart'],$mobile);
        }else{
          $string = str_replace('-', '', $value->mobile);
          $mobile = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
          $input['mobile'] = $mobile;
        }
      }
      return redirect('/')->with('success', 'Payment Successful!');
    } catch (\Exception $e) {
      dd($e);
      return redirect('/')->with('error', 'Payment Error!');
    }
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
