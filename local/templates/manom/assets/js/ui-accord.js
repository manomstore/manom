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
    $("#tab4").prop('checked', true);
    $("#tab2").prop('checked', false);
    $('body').animate({
      scrollTop: $($(this).attr('href')).offset().top - 165
    }, 1400);
  });

});




