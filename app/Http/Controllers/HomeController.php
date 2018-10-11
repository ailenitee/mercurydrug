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

class HomeController extends Controller
{
  protected $url;

  public function __construct(UrlGenerator $url)
  {
    $this->url = $url;
    $this->cart = session('cart');
  }
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    $data['edit'] = '';

    if (session()->exists('cart')){
      $data3 = session()->get('cart');
      $data2 = session()->get('cart.items');
      if (!empty($data2)){
        $data['cart'] =$data2;

        foreach ($data2 as $key => $value){
          foreach ($value as $key2 => $value2){
            //get theme img
            $data['cart'][$key][$key2]['themes'] = DB::table('themes')
            ->where('id', $value2['theme_id'])
            ->get();
            foreach ($data['cart'][$key][$key2]['themes'] as $value3){
              $data['cart'][$key][$key2]['themeImg'] = $value3->theme;
              $data['cart'][$key][$key2]['denomination_id'] = $value3->denomination_id;
            }
            //get denomination
            $data['cart'][$key][$key2]['denomination'] = DB::table('denomination')
            ->where('id', $data['cart'][$key][$key2]['denomination_id'])
            ->get();
            foreach ($data['cart'][$key][$key2]['denomination'] as $value4){
              $data['cart'][$key][$key2]['denomination'] = $value4->denomination;
            }

            $data['cart'][$key][$key2]['theme'] = $data['cart'][$key][$key2]['themeImg'];
            $data['cart'][$key][$key2]['denomination'] = $data['cart'][$key][$key2]['denomination'];

          }
        }
      }
    }
    //get denum for mercury
    $data['brand'] = DB::table('brand')
    ->where('id', 1)
    ->get();

    foreach ($data['brand'] as $key => $value){
      $data['brand_id'] = 1;
      $denum = explode(',' ,$value->themes);
    }
    $count = count($denum);
    $data['count'] = count($denum);
    $data['allThemes'] = $denum;
    foreach ($denum  as $key => $value){
      $intval= (int)$value;
      $data['denum'][] = DB::table('denomination')
      ->leftJoin('themes', 'themes.denomination_id', '=', 'denomination.id')
      ->where('themes.id',$intval)
      ->get();
    }
    return view('index',$data);
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $input      = $request->except(['_token']);
    // $trans_id   = $this->cart->generateTransctionID(15);
    // $count = count($request->quantityVal);
    // dd($request);
    $input['id'] = str_random(10);
    foreach ($request->themeID as $key => $value){
      $intval= (int)$value;
      $input['input'][$key]["theme_id"]           = $value;
      // $input['input'][$key]['transaction_id']     = $trans_id;
      $input['input'][$key]['brand_id']           = $request->brand_id;
      $input['input'][$key]['user_id']            = $request->user_id;
      $input['input'][$key]['sender']             = $request->sender;
      $input['input'][$key]['name']               = $request->name;
      $input['input'][$key]['address']            = $request->address;
      $input['input'][$key]['mobile']             = $request->mobile;
      $input['input'][$key]['id']                 = $input['id'];
      $input['themes'] = DB::table('denomination')
      ->leftJoin('themes', 'themes.denomination_id', '=', 'denomination.id')
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
    // dd($input['input']);
    foreach ($input['input'] as $key => $value){
      if($input['input'][$key]['quantity'] != 0){
        // dd($input['input'][$key]['quantity']);
        $res[] =[
          'user_id'             => (int)$input['input'][$key]['user_id'],
          'theme_id'            => (int)$input['input'][$key]['theme_id'],
          'brand_id'            => (int)$input['input'][$key]['brand_id'],
          'id'                  => $input['input'][$key]['id'],
          // 'transaction_id'      => $input['input'][$key]['transaction_id'],
          'sender'              => $input['input'][$key]['sender'],
          'name'                => $input['input'][$key]['name'],
          'quantity'            => $input['input'][$key]['quantity'],
          'address'             => $input['input'][$key]['address'],
          // 'email'               => $input['email'],
          'mobile'              => $input['input'][$key]['mobile'],
          'total'               => $input['input'][$key]['total']
        ];
      }
    }
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
      //// TODO: insert to cart for guest
      if ($request->session()->exists('cart')) {
        $request->session()->push('cart.items', $res);
      }else{
        $request->session()->put('cart.items', $res);
      }
    $cart                       = $this->cart->getItems();
    $data['cart']               = $cart;
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
  public function confirm()
  {

    $data3 = session()->get('cart');
    $data2 = session()->get('cart.items');
    // dd($data2);
    if (session()->exists('cart')){
      if (!empty($data2)){
        $data['cart'] =$data2;
        // dd($data2);
        foreach ($data2 as $key => $value){
          foreach ($value as $key2 => $value2){
            //get theme img

            $data['cart'][$key][$key2]['themes'] = DB::table('themes')
            ->where('id', $value2['theme_id'])
            ->get();
            foreach ($data['cart'][$key][$key2]['themes'] as $value3){
              $data['cart'][$key][$key2]['themeImg'] = $value3->theme;
              $data['cart'][$key][$key2]['denomination_id'] = $value3->denomination_id;
            }
            //get denomination
            $data['cart'][$key][$key2]['denomination'] = DB::table('denomination')
            ->where('id', $data['cart'][$key][$key2]['denomination_id'])
            ->get();
            foreach ($data['cart'][$key][$key2]['denomination'] as $value4){
              $data['cart'][$key][$key2]['denomination'] = $value4->denomination;
            }

            $data['cart'][$key][$key2]['theme'] = $data['cart'][$key][$key2]['themeImg'];
            $data['cart'][$key][$key2]['denomination'] = $data['cart'][$key][$key2]['denomination'];
          }
        }
        // dd(count($key));
        return view('confirm',$data);
      }else{
        return view('confirm');
      }
    }else{
      return view('confirm');
    }

  }
  public function deleteCart($id)
  {
      $data2 = session()->get('cart.items');
      $data3 = session()->get('cart');
      // dd($data2,$data3,$id);
      foreach ($data2 as $key => $value){
        // dd($value[$key]['id']);
        if($value[$key]['id'] == $id){
          session()->pull('cart.items.'. $key);
          session()->forget('cart.items.'. $key);
          session()->save();
        }
      }
    return back()->with('success', 'Removed Item From Cart!');
  }
  public function clearCart(Request $request){
    $user = Auth::user();
    if ($user){
      $data = DB::table('cart')
      ->where('user_id', $user->id)
      ->delete();
    }else{
      session()->flush('cart');
    }
    return back()->with('success', 'Cleared Cart!');
  }
}
