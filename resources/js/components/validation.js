$(function() {
  $('.reg-btn').attr('disabled','disabled');
  $('#customCheck1').change(function(){
    if ($(this).is(':checked')) {
      //sign up form validation
      $('.signup_form input').each(function() {
        if ($(this).val().length == 0) {
          empty = true;
        }
      });
      if (empty == true) {
        if($(".pw").val().length != 0 && $(".cpw").val().length != 0){
          if($(".pw").val() != $(".cpw").val()){
            $('.reg-btn').attr('disabled','disabled');
          }else{
            $('.reg-btn').attr('disabled',false);
          }
        }
      }else {
        $('.reg-btn').attr('disabled','disabled');
      }
    }else{
      $('.reg-btn').attr('disabled','disabled');
    }
  });
  //sign up form validation
  $('.signup_form input').keyup(function() {
    $('.signup_form input').each(function() {
      if ($(this).val().length == 0) {
        empty = true;
      }
    });
    if (empty == true) {
      if($(".pw").val().length != 0 && $(".cpw").val().length != 0){
        if($(".pw").val() != $(".cpw").val()){
          $('.reg-btn').attr('disabled','disabled');
          $('.alert-password').css('display', 'block');
          $('.alert-password').css('opacity', '1');
        }else{
          $('.reg-btn').attr('disabled',false);
          $('.alert-password').css('display', 'none');
          $('.alert-password').css('opacity', '0');
        }
      }
    }else {
      $('.reg-btn').attr('disabled','disabled');
    }
  });
  // // send gift card empty input validation
  // $('.form_details input').keyup(function() {
  //   var empty = false;
  //   $('.form_details input').each(function() {
  //     if ($(this).val().length == 0) {
  //       empty = true;
  //     }
  //   });
  // });

  $('.q-val').on('input propertychange paste', function (e) {
    var reg = /^0+/gi;
    if (this.value.match(reg)) {
      this.value = this.value.replace(reg, '');
    }
  });

  
});
