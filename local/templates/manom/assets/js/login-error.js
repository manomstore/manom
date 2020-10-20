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
      console.log({ data });
      if (data.status) {
        // window.location.reload();
        $(this).submit();
      } else {
        console.log("FAILED");
      }
    },
    error: function(error) {
      console.log("FAILED");
    },
  });
});