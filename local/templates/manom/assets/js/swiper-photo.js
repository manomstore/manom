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
  const slider = document.querySelector(`.product-photo__left .swiper-container`);
  var imglist = document.querySelectorAll(".product-photo__left img");

  if (slider) {
    if (imglist.length > 4) {
      new Swiper(slider, {
        direction: 'vertical',
        slidesPerView: 4,
        slidesPerColumn: 1,
        spaceBetween: 0,
        loop: true,
      });
    } else {
      return;
    }
  }
})();