
(function () {
  var MOBILE = 969;
  var TABLET = 1023;
  var SMALLMOBILE = 767;

  window.isMobileSwiper = function () {
    return window.matchMedia('(max-width: ' + SMALLMOBILE + 'px)').matches;
  };
  window.isMobile = function () {
    return window.matchMedia('(max-width: ' + MOBILE + 'px)').matches;
  };

  window.isTablet = function () {
    return window.matchMedia('(max-width: ' + TABLET + 'px)').matches;
  };

  window.isDesktop = function () {
    return window.matchMedia('(min-width: ' + (TABLET + 1) + 'px)').matches;
  };
})();