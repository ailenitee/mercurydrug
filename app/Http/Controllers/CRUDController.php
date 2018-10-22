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
    $input      = $request->except(['_token']);
    $input['user_id'] = str_random(10);
    $input['user_type'] = 'guest';
    foreach ($request->themeID as $key => $value){
      $intval= (int)$value;
      $input['input'][$key]["theme_id"]           = $value;
      $input['input'][$key]['brand_id']           = 1;
      $input['input'][$key]['sender']             = $request->sender;
      $input['input'][$key]['name']               = $request->name;
      $input['input'][$key]['address']            = $request->address;
      $input['input'][$key]['mobile']             = $request->mobile;
      // $input['input'][$key]['id']                 = $input['id'];
      $input['input'][$key]['option']             = $input['option'];
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
    // dd($input);
    foreach ($input['input'] as $key => $value){
      if($input['input'][$key]['quantity'] != 0){
        $res[] =[
          'theme_id'            => (int)$input['input'][$key]['theme_id'],
          'brand_id'            => 1,
          'user_id'             => 1,
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
    // dd($res2);
    if(!$res){
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
    DB::table('cart')
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
    $data['cart'] = DB::table('cart')
    ->where('id', $id)
    ->delete(); //delete data from db table.cart based on item id

    return back()->with('success', 'Removed Item From Cart!');
  }

  public function clearCart(Request $request){
    $data = DB::table('cart')
    ->delete();
    return redirect('/')->with('success', 'Cleared Cart!');
  }
}
