'use strict';

$(function () {
  if (window.isMobile()) {
    $("#accord-mobile").accordion({
      heightStyle: "content",
      collapsible: true,
      active: false
    });

    $(".product-questions").click(function () {
      $("#ui-id-3").trigger("click");
    });
  }

  $("#accordion").accordion({
    heightStyle: "content",
    collapsible: true,
    active: false
  });

  $(".product-questions").click(function () {
    $("#tab4").prop('checked', true);
    $("#tab2").prop('checked', false);
    $("#tab1").prop('checked', false);
    $("#tab3").prop('checked', false);
    $("#tab5").prop('checked', false);
    $("#tab6").prop('checked', false);
    $("#tab7").prop('checked', false);
    $("#tab8").prop('checked', false);

  });

});




