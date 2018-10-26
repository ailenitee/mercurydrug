<div class="navbar-top">
  <ul>
    <li style="float:left;">
      <a class="navbar-brand" href="{{ url('/') }}"> 
        <img src="{{URL::asset('/img/logo.jpg')}}" alt="" style="height:100%;">
      </a>
    </li>
    @if(Auth::guest())
    <li class="nav-item">
      <a class="nav-link-top login " href="{{url('/login')}}">Login</a>
    </li>
    <li class="nav-item">
      <a class="nav-link-top " href="{{url('/register')}}">Sign Up</a>
    </li>
    @else
    <li class="nav-item">
      <h4 class="nav-link-top login">Hi {{ Auth::user()->name }}!</h4>
    </li>
      <li class="nav-item">
        <a class="nav-link-top logout " href="{{url('/logout')}}">Logout</a>
      </li>
      @endif
    </ul>
  </div>
