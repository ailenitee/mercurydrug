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
    ->join('denomination', 'themes.denomination_id', '=', 'denomination.id')
    ->get();
    $data['edit'] = '';
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
    $data['item'] = DB::table('cart')
    ->where('id', $id)
    ->get();
    dd($data['item']);
    $data['brand'] = DB::table('brand')
    ->where('id', 1)
    ->get();

    foreach ($data['brand'] as $key => $value){
      $data['brand_id'] = 1;
      $denum = explode(',' ,$value->themes);
    }

    $data['edit'] = 'edit';
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

  public function confirm()
  {

    $data3 = session()->get('cart');
    $data2 = session()->get('cart.items');
    if (session()->exists('cart')){
      if (!empty($data2)){
        $data['cart'] =$data2;
        foreach ($data2 as $key => $value){
          foreach ($value as $key2 => $value2){
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

}
