(() => {
  const slider = document.querySelector(`.product-photo__right .swiper-container`);

  if (slider) {
    if (window.isMobileSwiper()) {
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