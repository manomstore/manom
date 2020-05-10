$(function () {


  headerHeight = $(".header__wrapper--fix").height() + 5;
  $('html,body').animate({
    scrollTop: target.offset().top - headerHeight
  }, 500);
});

var scroll = new SmoothScroll('a[href*="#"]');