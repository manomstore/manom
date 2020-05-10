'use strict';

$(function () {
  if (window.isMobile()) {
    $("#accord-mobile").accordion({
      heightStyle: "content",
      collapsible: true,
      active: false
    });

    $(".product-questions").click(function () {
    });
  }

  $("#accordion").accordion({
    heightStyle: "content",
    collapsible: true,
    active: false
  });

  $(".product-questions").click(function () {
    $("#tab1").prop('checked', true);
    $("#tab3").prop('checked', true);
    $("#tab5").prop('checked', true);
    $("#tab6").prop('checked', true);
    $("#tab7").prop('checked', true);
    $("#tab2").prop('checked', false);
  });

});




