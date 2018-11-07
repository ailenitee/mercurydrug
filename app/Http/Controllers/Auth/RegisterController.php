<?php

namespace App\Http\Controllers\Auth;

use App\User;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Jobs\SendVerificationEmail;

class RegisterController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Register Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users as well as their
  | validation and creation. By default this controller uses a trait to
  | provide this functionality without requiring any additional code.
  |
  */

  use RegistersUsers;

  /**
  * Where to redirect users after registration.
  *
  * @var string
  */
  protected $redirectTo = '/';

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('guest');
  }

  /**
  * Get a validator for an incoming registration request.
  *
  * @param  array  $data
  * @return \Illuminate\Contracts\Validation\Validator
  */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'username' => 'required|string|max:20|unique:users',
      'password' => 'required|string|min:6|confirmed',
    ]);
  }

  /**
  * Create a new user instance after a valid registration.
  *
  * @param  array  $data
  * @return \App\User
  */
  protected function create(array $data)
  {
    return User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'username' => $data['user_name'],
      'mobile' => $data['mnumber'],
      'role' => 2,
      'password' => Hash::make($data['password']),
      'email_token' => base64_encode($data['email'])
    ]);
  }

  public function register(Request $request)
  {
    $user= new \App\User;
    $user->name=$request->get('name');
    $user->email=$request->get('email');
    $user->mobile=$request->get('mnumber');
    $user->password=Hash::make($request->get('password'));
    $user->role_id=2;
    $user->status=0;
    $date=date_create($request->get('date'));
    $format = date_format($date,"Y-m-d");
    $user->created_at = strtotime($format);
    try{
      $user->save();
      dispatch(new SendVerificationEmail($user));
      return redirect('/login')->with('success', 'Registered Succesfully! Please check your registered email for email verification');
    }
    catch(\Exception $e){
      if($e){
        return back()->with('error', 'The email address you have entered is already registered.');
      } 
    }
  }

  public function verify($user_id)
  {
    $user = User::where('id',$user_id)->first();
    $user->status = 1;
    if($user->save()){
      return redirect('/login')->with('success', 'Email Verified! You may now Login.');
    }
  }
}
