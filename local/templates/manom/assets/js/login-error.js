$(".popup-login__form").on("submit", function(e) {
  e.preventDefault();
  var email = this["USER_LOGIN"].value;
  var password = this["USER_PASSWORD"].value;
  $.ajax({
    type: 'POST',
    url: "/ajax/ajax_func.php",
    data: {
      email: email,
      password: password,
      type: "checkPassword",
      sessid: BX.bitrix_sessid(),
    },
    success: function(data) {
      data = $.parseJSON(data);
      console.log({ data });
      if (data.status) {
        e.target.submit();
        $('#popap-login .errortext').removeClass('show');
      } else {
        console.log("FAILED");
        $('#popap-login .errortext').addClass('show');
      }
    },
    error: function(error) {
      console.log("FAILED");
      $('#popap-login .errortext').addClass('show');
    },
  });
});