'use strict';

$(function () {
  if (window.isMobile()) {
    $("#accord-mobile").accordion({
      heightStyle: "content",
      collapsible: true,
      active: false,
      activate: function (event, ui) {
        if (!$.isEmptyObject(ui.newHeader.offset())) {
          $('html:not(:animated), body:not(:animated)').animate({ scrollTop: ui.newHeader.offset().top - 100 }, 'slow');
        }
      }
    });

    $(".product-comments").click(function () {
      $("#ui-id-3").trigger("click");
    });

    $(".product-questions").click(function () {
      $("#ui-id-4").trigger("click");
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




