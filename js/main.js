$(function () {
  $('.record__answer').click(function (e) {
    e.preventDefault();
    $(this).parent().siblings('.inactive').toggle(1000);
  });

  $('.captchaAnswer').focusout(function () {
    $.ajax({
      type: 'post',
      url : 'classes/modelAJAX.php',
      data: {
        'key'   : 'checkCaptcha',
        'value' : $(this).val(),
        'index' : $('.captchaAnswer').index($(this))
      },
      success: function (data) {
        var res = JSON.parse(data);
        if (res.result == 0) {
          $('.my-captcha').eq(res.index).attr('src', 'img/captcha/'+res.captchaName);
          alert('Была введена не правильная каптча!');
        }
      }
    });
  });

  $('.record-text').keypress(function (key) {
    if (key.charCode == 60 || key.charCode == 62) {
      return false;
    }
  })
});
