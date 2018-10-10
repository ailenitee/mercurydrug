@extends('includes.app')
@section('content')
<div class="banner-container"></div>
<div class="container">
  <form  class="form_details" action="{{ route('cart') }}" enctype="multipart/form-data" method="post" style="width:100%;margin-top:0;">

    <div class="content denums" style="padding-bottom:30px;">
      <div class="row">
        <div class="col-md-12">
          <button class="nav-link btn-red btn-center float-right cart-btn"><i class="fa fa-shopping-cart"></i>&nbsp; Cart</button>
        </div>
      </div>
      <h1 class="text-center">Mercury Drug Gift Cards</h1><br>
      {{ csrf_field() }}
      <input type="hidden" value="{{Auth::user() ? Auth::user()->id : '0'}}" name="user_id">
      <input type="hidden" value="{{$brand_id}}" name="brand_id">
      <div class="row">
        <div class="col-md-offset-1 col-md-10">
          @if(session()->has('success'))
          <div class="alert alert-success">
            {{ session()->get('success') }}
          </div>
          @elseif(session()->has('error'))
          <div class="alert alert-danger">
            {{ session()->get('error') }}
          </div>
          @endif
          <?php
          $x = 0;
          ?>
          @foreach ($denum as $k => $result)
          @foreach ($result as $key => $denum)
          <div class="col-md-4">
            <div class="brand-container">
              <img alt="" class="denum" src="{{$denum->theme}}">
              <br>
              <input type="hidden" name="" value="{{$loop->count}}" id="counter">
              <div class="denums-margins">
                <label class="radio-inline">
                  &#8369; {{$denum->denomination}}
                </label>
              </div>
              <div class="quantity">
                <label for="" class="text-center">Quantity</label>
                <div class="input-group">
                  <span class="input-group-btn">
                    <button type="button" style="margin-top:0;" class="quantity-left-minus btn btn-danger btn-number"  data-type="minus" data-field="">
                      <span class="glyphicon glyphicon-minus"></span>
                    </button>
                  </span>
                  <?php $x++; ?>
                  <input type="text" name="quantityVal[{{$x}}]" class="form-control input-number quantity-{{$key}}" value="0" min="0" max="100">
                  <input type="hidden" name="themeID[{{$x}}]" value="{{$denum->id}}">
                  <span class="input-group-btn">
                    <button type="button" style="margin-top:0; " class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="">
                      <span class="glyphicon glyphicon-plus"></span>
                    </button>
                  </span>
                </div>
              </div>
            </div>
          </div>
          @endforeach
          @endforeach
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-12">
          <hr>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <ul class="nav nav-pills mb-3 send-pills" id="pills-tab" role="tablist">
            <li class="nav-item p-item" id="pillsEmail">
              <a class="nav-link" id="pills-profile-tab" data-toggle="pill" role="tab" aria-selected="false">
                <i class="far fa-comment"></i>
              </a>
              <h3>DELIVER</h3>
            </li>
            <li class="nav-item p-item" id="pillsDeliver">
              <a class="nav-link active" data-toggle="pill" role="tab" aria-selected="true">
                <i class="fas fa-at"></i>
              </a>
              <h3>SMS</h3>
            </li>
          </ul>
          <div class="tab-content send-tab-content " id="pills-tabContent">
            @if($edit == 'edit')
            <div class="tab-pane show active" id="pillsEmailContent" role="tabpanel" aria-labelledby="pills-home-tab">
              @else
              <div class="tab-pane show active email-content" id="pillsEmailContent" role="tabpanel" aria-labelledby="pills-home-tab">
                @endif
                <div class="form-group">
                  <label>Sender Name</label>
                  <input type="text" class="form-control" value="" name="sender" required>
                </div>
                <div class="form-group">
                  <label>Recipient's Name</label>
                  <input type="name" class="form-control" name="name" value="">
                </div>
                <div class="form-group">
                  <label>Recipient's Address</label>
                  <input type="text" class="form-control" name="address" value="">
                </div>
                <div class="form-group">
                  <label>Recipient's Mobile</label>
                  <input type="number" class="form-control" name="mobile" value="">
                </div>
              </div>
              @if($edit == 'edit')
              <div class="tab-pane" id="pillsDeliverContent" role="tabpanel" aria-labelledby="pills-contact-tab">
                @else
                <div class="tab-pane deliver-content" id="pillsDeliverContent" role="tabpanel" aria-labelledby="pills-contact-tab">
                  @endif
                  <div class="form-group">
                    <label>Sender Name</label>
                    <input type="text" class="form-control" value="" name="sender" required>
                  </div>
                  <div class="form-group">
                    <label>Recipient's Name</label>
                    <input type="name" class="form-control" name="name" value="">
                  </div>
                  <div class="form-group">
                    <label>Recipient's Mobile</label>
                    <input type="number" class="form-control" name="mobile" value="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="r-details" style="margin-top:0;">
            <div class="col-sm-6">
              <button type="submit" class="btn-border btn-center disabled" style="background-color: #ddd; border:1px solid #ddd;">ADD TO CART</button>
              <button type="submit" class="btn-border btn-center n_disabled" value="save" name='submitbutton'>ADD TO CART</button>
            </div>
            <div class="col-sm-6">
              <button type="submit" class="btn-red btn-center disabled" style="background-color: #ddd; border:1px solid #ddd;">ADD AND CONFIRM ORDER</button>
              <button type="submit" class="btn-red btn-center n_disabled" name='submitbutton' value="save_cart">ADD AND CONFIRM ORDER</button>
            </div>
          </div>
          <br><br>
        </div>
        <div class="col-md-2"></div>

      </div>
    </div>
  </form>
</div>
@stop
