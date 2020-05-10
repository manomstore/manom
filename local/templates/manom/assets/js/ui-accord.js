'use strict';

$(function () {
  if (window.isMobile()) {
    $("#accord-mobile").accordion({
      heightStyle: "content",
      collapsible: true,
      active: false
    });

    $(".product-questions").click(function () {
      if ($.each(".accord-mobile__header").hasClass('ui-accordion-header-active')) {
        $(this).removeClass("ui-accordion-header-active")
        $(this).addClass("ui-accordion-header-collapsed")
      }
    });
  }

  $("#accordion").accordion({
    heightStyle: "content",
    collapsible: true,
    active: false
  });

  $(".product-questions").click(function () {
    $("#tab1").prop('checked', false);
    $("#tab3").prop('checked', false);
    $("#tab5").prop('checked', false);
    $("#tab6").prop('checked', false);
    $("#tab7").prop('checked', false);
    $("#tab2").prop('checked', false);
    $("#tab8").prop('checked', false);
    $("#tab4").prop('checked', true);
  });

});




