$(function () {
  var imglist = $(".product-photo__left").length;
  if (imglist < 2) {
    $(".product-photo__left img").addClass(visually - hidden)
  }
});