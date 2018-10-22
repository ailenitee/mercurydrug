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
    $data['cart'] = DB::table('cart')
    ->join('themes', 'themes.id', '=', 'cart.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->select('cart.*','denominations.denomination','themes.theme')
    ->get();
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
    // dd($data);
    return view('index',$data);
  }


  public function edit($id)
  {
    // TODO: display for edit
    // dd($id);
    $data['cart'] = DB::table('cart')
    ->join('themes', 'themes.id', '=', 'cart.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->get();
    // dd($data['cart']);
    $data['item'] = DB::table('cart')
    ->join('themes', 'themes.id', '=', 'cart.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->where('cart.id', (int)$id)
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
    $data['cart'] = DB::table('cart')
    ->join('themes', 'themes.id', '=', 'cart.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->get();
    // dd($data['cart']);
    $data['amount'] = "";
    // dd(count($key));
    return view('confirm',$data);

  }

  public function checkout()
  {
    $data['cart'] = DB::table('cart')
    ->join('themes', 'themes.id', '=', 'cart.theme_id')
    ->join('denominations', 'themes.denomination_id', '=', 'denominations.id')
    ->select('cart.*','denominations.denomination','themes.theme')
    ->get();
    return view('checkout',$data);
  }
}
