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
  const smallSlider = document.querySelector(`.product-photo__left.swiper-container`);
  var imglist = document.querySelectorAll(".product-photo__left img");
  const btnPrec = document.querySelector(`.product-photo__left .swiper-button-prev`);
  const btnNext = document.querySelector(`.product-photo__left .swiper-button-next`);

  if (smallSlider) {
    if (imglist.length > 4) {
      new Swiper(smallSlider, {
        direction: 'vertical',
        slidesPerView: 4,
        spaceBetween: 0,
        navigation: {
          nextEl: '.product-photo__left .swiper-button-next',
          prevEl: '.product-photo__left .swiper-button-prev',
        },
      });
      btnPrec.classList.remove('visually-hidden');
      btnNext.classList.remove('visually-hidden');
    } else {
      return;
    }
  }
})();

(() => {
  const watchedSlider = document.querySelector(`.watched__slider.swiper-container`);
  const btnPrev = document.querySelector(`.watched__button-prev`);
  const btnNext = document.querySelector(`.watched__button-next`);
  var slides = document.querySelectorAll(".watched__slide");

  if (watchedSlider) {
    if (slides.length > 4) {
      new Swiper(watchedSlider, {
        slidesPerView: 4,

        navigation: {
          nextEl: '.watched__button-next',
          prevEl: '.watched__button-prev',
        },
        breakpointsInverse: true,
        breakpoints:{
          320: {
            slidesPerView: 2,
            spaceBetween: 16,
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 32,
          },
          1028: {
            slidesPerView: 4,
            spaceBetween: 32,
          }
        }
      });
      btnPrev.classList.remove('visually-hidden');
      btnNext.classList.remove('visually-hidden');
    } else {
      return;
    }
  }
})();