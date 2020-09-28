$(function () {
  if(!$(".product-tabs").length) {
    return;
  }

  $(".product-tabs").offset().top - 140;
});

var scroll = new SmoothScroll('a[href*="#"]');