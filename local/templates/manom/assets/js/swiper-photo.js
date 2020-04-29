(() => {
  const slider = document.querySelector(`.product-photo__right swiper-container`);

  if (slider) {
    if (window.isMobile()) {
      new Swiper(giftSlider, {
        slidesPerView: 1,
        slidesPerColumn: 1,
        spaceBetween: 0,
        loop: false
      });
    } else {
      return;
    }
  }
})();