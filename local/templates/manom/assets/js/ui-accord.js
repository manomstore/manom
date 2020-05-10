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

  // function scrollTo(element) {
  //   jQuery('html, body').animate({
  //     scrollTop: jQuery(element).offset().top
  //   }, 500);
  // }

  // jQuery('.accordion').on('accordionactivate', function (event, ui) {
  //   scrollTo(jQuery(event.target).find('.ui-accordion-header-active'))
  // });

});




