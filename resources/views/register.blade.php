@extends('includes.app')
@section('content')
<div class="login-container content" style="height:auto;">
  <div class="container">
    <div class="row">
      <div class="col-sm-offset-2 col-sm-8">
        <h1 class="text-center">Sign Up</h1>
        <br>
        <h4 class="text-center"><u><a href="{{url('/login')}}">I am already a member</a></u></h4>
        <br>
        <form class="loginform_details" action="{{ route('user_register') }}" method="post">
          @if(Session::has('error'))
          <div class="alert alert-danger alert-dismissible" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <i class="fas fa-exclamation-circle"></i> {{ Session::get('error') }}
         </div>
         @endif
          {!! csrf_field() !!}
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1"name="name" required>
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-at"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1"name="email" required>
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-mobile"></i></span>
            </div>
            <input type="number" class="form-control" placeholder="Mobile Number" aria-label="Mobile Number" aria-describedby="basic-addon1"name="mnumber" required>
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" ><i class="fas fa-unlock-alt"></i></span>
            </div>
            <input type="password"  pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1"name="password"  required>
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" ><i class="fas fa-unlock-alt"></i></span>
            </div>
            <input type="password"  pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" class="form-control" placeholder="Confirm Password" aria-label="Password" aria-describedby="basic-addon1"name="cpassword"  required>
          </div>
          <div class="custom-control custom-checkbox" style="margin-top:10px;">
            <input type="checkbox" class="custom-control-input" id="customCheck1">
            <label class="custom-control-label" for="customCheck1">&nbsp;&nbsp;I agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a>.</label>
          </div>
          <button type="submit" name="button" class="nav-link btn-border btn-center" >Register</button>
        </form>
        <br><br>
      </div>
      <!-- <div class="col-sm-6">
        <img src="https://www.mercurydrug.com/public/images/slider/main-slide-update.jpg">
        <br><br>
        <h4 class="text-center"><u><a href="{{url('/login')}}">I am already a member</a></u></h4>
      </div> -->
    </div>
  </div>
</div>
@stop
