@extends('includes.app2')
<!-- app2.blade.php = without cart modal -->
@section('content')
<div class="login-container content">
  <div class="container">
    <div class="row">
      <div class=" col-md-12">
        @if(Session::has('error2'))
        <div class="alert alert-danger alert-dismissible" role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <i class="fas fa-exclamation-circle"></i> {{ Session::get('error2') }}
       </div>
       @endif
      </div>
      <div class="col-xs-12 col-md-6">
        <img src="{{URL::asset('/img/login/gift-certificate-image.jpg')}}">
        <br><br>
        <h4 class="text-center" style="margin-bottom:25px;"><u><a href="{{url('/register')}}">Create an Account</a></u></h4>
      </div>
      <div class="col-xs-12 col-md-6">
        <h1 class="text-center">Sign In</h1>
        <br>
        <form class="loginform_details" action="{{ route('user_login') }}" method="post">
          {!! csrf_field() !!}
          @if(Session::has('error'))
          <div class="alert alert-danger alert-dismissible" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <i class="fas fa-exclamation-circle"></i> {{ Session::get('error') }}
         </div>
         @endif
         @if(Session::has('success'))
         <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <i class="fas fa-check"></i> {{ Session::get('success') }}
        </div>
         @endif
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1"name="email" value="{{ old('email') }}" required>
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-unlock-alt"></i></span>
            </div>
            <input type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1"name="password"  required>
          </div>
          <button type="submit" name="button" class="nav-link btn-border btn-center" >Login</button>
        </form>
        <br><br>
        <h3 class="text-center">Or </h3>
        <br>
        <a class="nav-link btn-fb" href=""><i class="fab fa-facebook"></i>&nbsp;Sign in with Facebook</a>
        <br>
        <a class="nav-link btn-g" href=""><i class="fab fa-google"></i>&nbsp;Sign in with Google</a>
      </div>
    </div>
  </div>
</div>
@stop
