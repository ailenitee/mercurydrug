
<div class="shop-container">
  <div class="col-sm-5">
    <img alt="" class="denum" src="{{$denum->theme}}">
  </div>
  <div class="col-sm-4">
    <label class="radio-inline">
      &#8369; {{$denum->denomination}}
    </label>
  </div>
  <div class="col-sm-3">
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
