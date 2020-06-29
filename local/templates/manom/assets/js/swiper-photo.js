(() => {
  const slider = document.querySelector(`.product-photo__right .swiper-container`);
  var imglist = document.querySelectorAll(".product-photo__left img");

  if (slider) {
    if (window.isMobileSwiper() && imglist.length > 1) {
      new Swiper(slider, {
        slidesPerView: 1,
        slidesPerColumn: 1,
        spaceBetween: 0,
        loop: false,
        pagination: {
          el: '.swiper-pagination',
        }
      });
    } else {
      return;
    }
  }
})();

(() => {
  const smallSlider = document.querySelector(`.product-photo__left .swiper-container`);
  var imglist = document.querySelectorAll(".product-photo__left img");

  if (smallSlider) {
    if (imglist.length > 4) {
      new Swiper(smallSlider, {
        direction: 'vertical',
        slidesPerView: 4,
      });
    } else {
      return;
    }
  }
})();