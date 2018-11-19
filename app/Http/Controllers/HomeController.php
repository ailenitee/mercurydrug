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

  public function index()
  {
    $user = Auth::user();
    // dd(last(session()->get('user_id')));
    if ($user){
      if(session()->get('user_id')){
        $data['carts'] = DB::table('carts')
        ->where('user_id',last(session()->get('user_id')))
        ->update(array('user_id' =>$user->id));
      }

      $data['type'] = DB::table('carts')
      ->where('user_id',$user->id)
      ->update(array('user_type' => 'user'));

      $data['cart'] = DB::table('carts')
      ->join('themes', 'themes.id', '=', 'carts.theme_id')
      ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
      ->select('carts.*','denominations.denomination','themes.theme')
      ->where('user_id',$user->id)
      ->where('transaction_id','pending')
      ->orWhereNull('transaction_id')
      ->get();
      // dd($data);
    }else{
      if(session()->get('user_id')){
        $data['cart'] = DB::table('carts')
        ->join('themes', 'themes.id', '=', 'carts.theme_id')
        ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
        ->select('carts.*','denominations.denomination','themes.theme')
        ->where('user_id',last(session()->get('user_id')))
        ->where('transaction_id','pending')
        ->orWhereNull('transaction_id')
        ->get();
      }else{
        $data['cart'] = DB::table('carts')
        ->join('themes', 'themes.id', '=', 'carts.theme_id')
        ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
        ->select('carts.*','denominations.denomination','themes.theme')
        ->where('user_id',0)
        ->where('transaction_id','pending')
        ->orWhereNull('transaction_id')
        ->get();
      }
    }
    $data['edit'] = '';
    //get denum for mercury
    $data['brand'] = DB::table('brands')
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
      $data['denum'][] = DB::table('denominations')
      ->leftJoin('themes', 'themes.denomination_id', '=', 'denominations.id')
      ->where('themes.id',$intval)
      ->get();
    }
    $data['sender'] = "";
    $data['mobile'] = "";
    $data['total'] = "";
    $data['address'] = "";
    $data['name'] = "";
    // dd(session()->getId());
    // dd($data);
    return view('index',$data);
  }


  public function edit($id)
  {
    // TODO: display for edit
    // dd($id);
    $data['cart'] = DB::table('carts')
    ->join('themes', 'themes.id', '=', 'carts.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->get();
    // dd($data['cart']);
    $data['item'] = DB::table('carts')
    ->join('themes', 'themes.id', '=', 'carts.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->where('carts.id', (int)$id)
    ->first();

    $data['quantity'] = $data['item']->quantity;
    $data['brand'] = DB::table('brands')
    ->where('id', 1)
    ->get();

    foreach ($data['brand'] as $key => $value){
      $data['brand_id'] = 1;
      $denum = explode(',' ,$value->themes);
    }
    if($data['item']->address){
      $data['option'] = 'delivery';
      $data['address'] = $data['item']->address;
    }else{
      $data['option'] = 'sms';
      $data['address'] ='';
    }

    $data['id'] = $id;
    $data['edit'] = 'edit';
    $data['sender'] = $data['item']->sender;
    $data['name'] = $data['item']->name;
    $data['mobile'] = $data['item']->mobile;

    $data['count'] = count($denum);
    $data['allThemes'] = $denum;
    foreach ($denum  as $key => $value){
      $intval= (int)$value;
      $data['denum'][] = DB::table('denominations')
      ->leftJoin('themes', 'themes.denomination_id', '=', 'denominations.id')
      ->where('themes.id',$intval)
      ->get();
    }

    return view('index',$data);
  }

  public function confirm()
  {
    $user = Auth::user();
    $data['cart'] = DB::table('carts')
    ->join('themes', 'themes.id', '=', 'carts.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->where('user_id',$user->id)
    ->where('transaction_id','pending')
    ->orWhereNull('transaction_id')
    ->get();
    $data['amount'] = "";
    $user = Auth::user();
    if ($user){
      return view('confirm',$data);
    }else{
      return redirect()->route('login')->with('error2', 'User is required to login before checking out.');
    }
  }

  public function checkout()
  {
    $user = Auth::user();
    $data['cart'] = DB::table('carts')
    ->join('themes', 'themes.id', '=', 'carts.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->select('carts.*','denominations.denomination','themes.theme')
    ->where('user_id',$user->id)
    ->where('transaction_id','pending')
    ->orWhereNull('transaction_id')
    ->get();
    if ($user){
      return view('checkout',$data);
    }else{
      return redirect()->route('login')->with('error2', 'User is required to login before checking out.');
    }
  }

  public function login()
  {
    $user = Auth::user();
    $data['cart'] = DB::table('carts')
    ->join('themes', 'themes.id', '=', 'carts.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->select('carts.*','denominations.denomination','themes.theme')
    ->get();
    if ($user){
      return view('index',$data);
    }else{
      return view('login',$data);
    }
  }

  public function register()
  {
    $user = Auth::user();
    $data['cart'] = DB::table('carts')
    ->join('themes', 'themes.id', '=', 'carts.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->select('carts.*','denominations.denomination','themes.theme')
    ->get();
    if ($user){
      return view('index',$data);
    }else{
      return view('register',$data);
    }
  }
}
