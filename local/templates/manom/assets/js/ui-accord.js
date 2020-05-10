'use strict';

$(function () {
  if (window.isMobile()) {
    $("#accord-mobile").accordion({
      heightStyle: "content",
      collapsible: true,
      active: false
    });
  }

  $("#accordion").accordion({
    heightStyle: "content",
    collapsible: true,
    active: false
  });

  $(".product-questions").click(function () {
    $("#tab4").attr('checked');
    $("#tab2").prop('checked', false);
  });

});




