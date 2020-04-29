(() => {
  const giftSlider = document.querySelector(`.product-photo__right swiper-container`);

  if (giftSlider) {
    if (!window.isDesktop()) {
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