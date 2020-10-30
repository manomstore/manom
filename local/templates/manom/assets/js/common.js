// window.onload = function(e) {
$(function () {

  //$('.top-submenu2').hide();

  // слайдер
  $('.top-slider').slick({
    arrows: false,
    dots: true,
    infinite: true,
    speed: 1000,
    autoplay: true,
    autoplaySpeed: 3000,
    fade: true,
    cssEase: 'linear',
  });

  // слайдер фото в карточке товара
  $(document).find('.product-card').hover(
    function () {
      console.log('ee');
      $(this).children(".product-card__img").slick({
        arrows: true,
        dots: false,
        infinite: true,
        speed: 1000,
      });
      $(this).children(".p-nav-top").fadeIn(200);
    },
    function () {
      $(this).children(".product-card__img").slick('unslick');
      $(this).children(".p-nav-top").fadeOut(200);
    }
  );

  // слайдер акции
  $('.promotion__block').slick({
    arrows: true,
    // dots: true,
    infinite: true,
    speed: 1000,
    //autoplay: true,
    autoplaySpeed: 3000,
    slidesToShow: 4,
    slidesToScroll: 1,
  });
  // слайдер новинки
  $('.new-product__block').slick({
    arrows: true,
    // dots: true,
    infinite: true,
    speed: 1000,
    //autoplay: true,
    autoplaySpeed: 4000,
    slidesToShow: 4,
    slidesToScroll: 1,
  });
  // слайдер бренды
  $('.brands__block').slick({
    arrows: true,
    //dots: true,
    infinite: true,
    speed: 1000,
    //autoplay: true,
    autoplaySpeed: 3000,
    slidesToShow: 6,
    slidesToScroll: 1,
  });
  // слайдер бестселлеры
  $('.bestsellers__block').slick({
    arrows: true,
    // dots: true,
    infinite: true,
    speed: 1000,
    //autoplay: true,
    autoplaySpeed: 3000,
    slidesToShow: 4,
    slidesToScroll: 1,
  });
  // слайдер доптовары
  $('.additionally__block').slick({
    arrows: true,
    dots: true,
    infinite: true,
    speed: 1000,
    //autoplay: true,
    autoplaySpeed: 3000,
    slidesToShow: 4,
    slidesToScroll: 1,
  });
  // слайдер сравнение товаров
  $('.compare__block').slick({
    arrows: true,
    dots: true,
    infinite: true,
    speed: 1000,
    //autoplay: true,
    autoplaySpeed: 3000,
    slidesToShow: 4,
    slidesToScroll: 1,
    // responsive: [
    //   {
    //     breakpoint: 900,
    //     settings: {
    //       slidesToShow: 3
    //       //slidesToScroll: 1,
    //     }
    //   },
    //   {
    //     breakpoint: 700,
    //     settings: {
    //       slidesToShow: 2
    //       //slidesToScroll: 1
    //     }
    //   }
    // ]
  });


  // top-ad-line
  $(".top-ad-line__close").on('click', function (e) {
    e.preventDefault();
    $(".top-ad-line").slideUp(300);
  });

  //Главное меню 2
  var tsVisible = false;
  $('.top-nav2__block').hover(function () {
    $(this).css("height", "340px")
    if (!tsVisible) {
      $(".top-submenu2__block").delay(1000).show(1);
    } else {
      $(".top-submenu2__block").show(1);
    }
  },
    function () {
      $(this).css("height", "50px")
      $(".top-submenu2__block").hide(1);
    }
  );
  // $('.top-submenu2__block').hover(function(){
  //     $(".top-submenu2__block").show(1);
  //   },
  //   function(){
  //     $(".top-submenu2__block").hide(1);
  //   }
  // );

  $('.top-menu2__item').hover(function () {
    var tsId = $(this).attr('data-id');
    $(".top-submenu2__items").hide();
    $("#" + tsId).css("display", "flex").show();
  },
    function () {
      $("#" + tsId).hide();
    }
  );



  // закрытие cb-filter__param
  $(".cb-filter__param span").on('click', function (e) {
    $(this).parent().fadeOut(200);
  });
  // $(".cb-filter__clear").on('click', function(e) {
  //   $(".cb-filter__param").fadeOut(200);
  //   $(".catalog-filter__checkbox").prop("checked", false);
  // });

  // disabled карты товара
  $(".product-card.disable").find(".p-label-top").removeClass('active');
  $(".product-card.disable").find(".p-nav-middle__sale").text('Нет в наличии');
  $(".cb-line-card.disable").find(".cb-line-card__sale").removeClass('active');
  $(".cb-line-card.disable").find(".cb-line-bottom__buy").html('Следить за&nbsp;товаром');
  $(".cb-line-card.disable").find(".cb-line-nav-top__available").text('Нет в наличии');

  // Переключение стилей показа товаров в каталоге
  function changeStyleCat() {
    if ($("#v-block").prop("checked")) {
      $('.cb-block').css("display", "flex");
    } else {
      $('.cb-block').css("display", "none");
    };
    if ($("#v-single").prop("checked")) {
      $('.cb-single').css("display", "flex");
    } else {
      $('.cb-single').css("display", "none");
    };
    if ($("#v-line").prop("checked")) {
      $('.cb-line').css("display", "flex");
    } else {
      $('.cb-line').css("display", "none");
    };
    //избранное
    if ($("#v-block-favour").prop("checked")) {
      $('#cb-block-favour').css("display", "flex");
    } else {
      $('#cb-block-favour').css("display", "none");
    };
    if ($("#v-single-favour").prop("checked")) {
      $('#cb-single-favour').css("display", "flex");
    } else {
      $('#cb-single-favour').css("display", "none");
    };
    if ($("#v-line-favour").prop("checked")) {
      $('#cb-line-favour').css("display", "flex");
    } else {
      $('#cb-line-favour').css("display", "none");
    };


  };
  // ползунок цен
  $("#slider-range").slider({
    range: true,
    min: 0,
    max: 200000,
    step: 100,
    values: [15000, 60000],
    slide: function (event, ui) {
      $("#price-start").val(ui.values[0]);
      $("#price-end").val(ui.values[1]);
    }
  });
  $("#price-start").val($("#slider-range").slider("values", 0));
  $("#price-end").val($("#slider-range").slider("values", 1));
  // подстройка ползунка под введенные значения от 0 до 200000
  $("#price-start").change(function () {
    var inputStart = $(this).val();
    if (inputStart > 200000) { inputStart = 200000 };
    if (inputStart < 0) { inputStart = 0 };
    $("#slider-range").slider("values", 0, inputStart);
    $(this).val(inputStart);
  });
  $("#price-end").change(function () {
    var inputEnd = $(this).val();
    if (inputEnd > 200000) { inputEnd = 200000 };
    if (inputEnd < 0) { inputEnd = 0 };
    $("#slider-range").slider("values", 1, inputEnd);
    $(this).val(inputEnd);
  });


  // вывод карточек фильтров
  // $("input.catalog-filter__checkbox").bind("change", function () {
  //   var str = '';
  //   if ($(this).prop("checked")) {
  //     str = $(this).val() + $(this).siblings('.catalog-filter__item').text() + '<span>×</span>';
  //     console.log(str);
  //     var mydiv = $('<div/>', {
  //         class:  'cb-filter__param',
  //         html:   str
  //     });
  //     $(".cb-filter").prepend(mydiv);
  //     // Обновление обработчика закрытия cb-filter__param
  //     $(".cb-filter__param span").on('click', function(e) {
  //       var text = $(this).parent().text();
  //       $(this).parent().fadeOut(200);
  //       console.log(text);
  //       var s1 = text.indexOf(': ');
  //       var s2 = text.indexOf('<span>');
  //       var str1 = text.slice(s1+2, text.length-1);
  //       console.log(str1);
  //       $('.catalog-filter__item:contains('+str1+')').siblings('.catalog-filter__checkbox').prop("checked", false);
  //     });
  //   } else {
  //     str = $(this).val() + $(this).siblings(".catalog-filter__item").text();
  //     $('.cb-filter__param:contains('+str+')').fadeOut(200);
  //   };
  //
  // });

  // Пагинация на странице Каталог
  // $(".cb-nav-pagination__item").on('click', function(e) {
  //   //var thisPage = $(this)
  //   $(".cb-nav-pagination__item").removeClass("active");
  //   $(this).addClass("active");
  // });

  // Пагинация на странице Статьи
  $(".articles__pagination_item").on('click', function (e) {
    //var thisPage = $(this)
    $(".articles__pagination_item").removeClass("active");
    $(this).addClass("active");
  });

  // ---- Страница продукта
  var srcImgBig;
  // Смена фоток (клик)
  $(".product-photo__left img").on('click', function (e) {
    srcImgBig = $(this).attr('src');
    $(".product-photo__left img").removeClass("active");
    $(this).addClass("active");
    $(".product-photo__right img").attr('src', srcImgBig);
  });
  // Смена фоток (ховер)
  $('.product-photo__left img').hover(function () {
    var srcImgPre = $(this).attr('src');
    srcImgBig = $(".product-photo__right img").attr('src');
    console.log('Ховер', srcImgPre, srcImgBig);
    // $(".product-photo__right img").fadeOut(0).fadeIn(100).attr('src', srcImgPre);
    $(".product-photo__right img").attr('src', srcImgPre);
  },
    function () {
      $(".product-photo__right img").attr('src', srcImgBig);
      console.log('АнХовер', srcImgBig);
    }
  );
  // Галерея картинок
  // $('[data-fancybox="gallery-prod"]').fancybox({
  //   // Options will go here
  // });
  // Смена цвета (клик)
  $(".square-color").on('click', function (e) {
    //thisColor = $(this).attr('title');
    // $(".square-color").removeClass("active");
    // $(this).addClass("active");
    // $(".product-content__color span").text($(this).attr('data-color'));
    //Смена фото с цветом
    if (!$(this).hasClass('propDisabled')) {
      $(".product-photo__left img").removeClass("active");
      var dcolor = $(this).attr('data-color');
      console.log(dcolor);
      srcImgBig = $(".product-photo__left img[data-color='" + dcolor + "']").addClass("active").attr('src');
      $(".product-photo__left .mp-element").removeClass('mp-active')
      $(".product-photo__left .mp-element").addClass('mp-disable')
      $(".product-photo__left .mp-element[data-color-mp='" + dcolor + "']").removeClass('mp-disable')
      $(".product-photo__left .mp-element[data-color-mp='" + dcolor + "']").addClass('mp-active')
      $(".product-photo__right img").attr('src', srcImgBig);
    }
  });

  // Смена объема памяти (клик)
  //   $(".product-content__memory_item").on('click', function(e) {
  // //    thisColor = $(this).attr('title');
  //     $(".product-content__memory_item").removeClass("active");
  //     $(this).addClass("active");
  //   });
  // Смена количества товара (страница товара)
  var count = 1,
    newPrice = 35000,
    newCost = 35000,
    oldPrice = 40000,
    oldCost = 40000,
    profit = 5000;

  $(".product-sidebar__total_count-up").on('click', function (e) {
    count += 1;
    if (count > 100) { count = 100 };
    newCost = (newPrice * count);
    oldCost = (oldPrice * count);
    profit = oldCost - newCost;
    console.log(count);
    $(".product-sidebar__total_count span").text(count);
    $(".product-sidebar__old-price").text(oldCost);
    $(".product-sidebar__total-price-price").text(newCost);
    $(".product-sidebar__profit span").text(profit);
  });
  $(".product-sidebar__total_count-down").on('click', function (e) {
    count -= 1;
    if (count < 1) { count = 1 };
    newCost = (newPrice * count);
    oldCost = (oldPrice * count);
    profit = oldCost - newCost;
    console.log(count);
    $(".product-sidebar__total_count span").text(count);
    $(".product-sidebar__old-price").text(oldCost);
    $(".product-sidebar__total-price-price").text(newCost);
    $(".product-sidebar__profit span").text(profit);
  });
  // Кнопка Купить дешевле
  $(".product-sidebar__cheaper").on('click', function (e) {
    if (!$(this).hasClass('product-sidebar__cheaper__disbled')) {
      $("#tab8").prop("checked", true);
      var offsetTop = $("#product-tabs").offset().top - ($('.header__wrapper').height() - 1);
      $('html, body').stop().animate({
        scrollTop: offsetTop
      }, 300);
    }
  });
  // Кнопка Подробнее
  $(".product-content__more").on('click', function (e) {
    $("#tab1").prop("checked", true);
    var offsetTop = $("#product-tabs").offset().top;
    $('html, body').stop().animate({
      scrollTop: offsetTop
    }, 300);
    e.preventDefault();
  });




  // Смена страниц в корзине
  $(".shopcart-nav1 input").change(function () {
    $(".shopcart-item").fadeOut(0);
    var idSection = $(this).val();
    console.log(idSection);
    $(".shopcart-items " + idSection).css("display", "flex").fadeIn(300);
    if ($("#shopcart-tab1").prop("checked")) {
      $(".shopcart-sidebar__buyer").hide();
      $(".shopcart-sidebar__delivery").hide();
    } else {
      $(".shopcart-sidebar__buyer").show();
      $(".shopcart-sidebar__delivery").show();
    };
    if ($("#shopcart-tab2").prop("checked")) {
      $(".shopcart-sidebar__delivery").hide();
    } else {
      $(".shopcart-sidebar__delivery").show();
    };
  });

  // Смена методов оплаты на вкладке Оплата страницы Корзина
  $(".sci-payment__input").change(function () {
    var idSection = $(this).val();
    console.log(".sci-payment-content" + idSection);
    $(".sci-payment-content").fadeOut(0);
    $("#sci-payment-content" + idSection).fadeIn(200);
  });



  // Переход по кнопке "Оформить заказ"
  // $(".shopcart-sidebar__button").on('click', function(e) {
  //   $(".shopcart-item").fadeOut(0);
  //   $("#shopcart-item2").fadeIn(300);
  //   $("#shopcart-tab2").prop("checked", true);
  // });


  // Смена количества товара (страница Корзина)
  var count = 1,
    newPrice = 5000,
    newCost = 5000,
    oldPrice = 10000,
    oldCost = 10000;
  // $(".sci-top__count-up").on('click', function(e) {
  //   count += 1;
  //   if (count > 100) {count = 100};
  //   newCost = (newPrice * count);
  //   oldCost = (oldPrice * count);
  //   $(".sci-top__count span").text(count);
  //   $(".sci-top__new-price span").text(newCost);
  //   $(".sci-top__old-price").text(oldCost);
  // });
  // $(".sci-top__count-down").on('click', function(e) {
  //   count -= 1;
  //   if (count < 1) {count = 1};
  //   newCost = (newPrice * count);
  //   oldCost = (oldPrice * count);
  //   $(".sci-top__count span").text(count);
  //   $(".sci-top__new-price span").text(newCost);
  //   $(".sci-top__old-price").text(oldCost);
  // });

  // Удаление товара из корзины
  $(".sci-top__remove").on('click', function (e) {
    //console.log
    $(this).parent().parent(".sci-product").slideUp();
  });


  // Попап галерея на странице Статья
  // $('[data-fancybox="gallery"]').fancybox({
  //   // Options will go here
  // });
  // $('[data-fancybox="gallery1"]').fancybox({
  //   // Options will go here
  // });



  // };

  // Смена стилей отображения каталога
  changeStyleCat();
  $("input[name='style']").change(changeStyleCat);
  $("input[name='style-last']").change(changeStyleCat);
  $("input[name='style-favour']").change(changeStyleCat);



  // вкл/выкл режима редактирования информации в Личном кабинете
  $("#pb-info__button").bind("change", function () {
    if ($(this).prop("checked")) {
      $("#pb-info__col1-text, #pb-info__col2-text").fadeOut(0);
      $("#pb-info__col1-input, #pb-info__col2-input, #pb-info__col3-input").fadeIn(200);
      $(".pb-info__button").text("Сохранить");
      $(document).find('#info__button_save').css({ display: 'inline-block' })
    } else {
      $("#pb-info__col1-input, #pb-info__col2-input, #pb-info__col3-input").fadeOut(0);
      $("#pb-info__col1-text, #pb-info__col2-text").fadeIn(200);
      $(".pb-info__button").text("Редактировать");
      $(document).find('#info__button_save').css({ display: 'none' })
    };
  });

  // Смена блоков по меню в сайдбаре на странице Корзина
  $(".personal-nav__item").on('click', function () {
    var idSection = $(this).attr('data-id');
    console.log("#" + idSection);
    $(".personal-block__section").fadeOut(0);
    $("#" + idSection).fadeIn(200);
  });

  // Удаление товара из слайдера Сравнения
  var colSl = 4;
  $(".compare__basket").on('click', function (e) {
    var clickSlide = $(this).parents(".col4");
    //var clickSlide = $(this).parents(".product-card");
    var currentSlide = $('.compare__block').slick('slickCurrentSlide');
    //clickSlide.children(".product-card").css("background-color", "#efefef");
    var ind = clickSlide.index() % colSl;
    console.log(clickSlide.index(), ind, colSl);
    //console.log($('.slick-slide').index(currentSlide) - 1);
    $('.compare__block').slick('slickRemove', ind);
    //$('.compare__block').slick('slickRemove', $('.slick-slide').index(currentSlide) - 1);
    //$(this).parents(".col-3").addClass("del-prod");
    //focusOnSelect
  });

  // вкл/выкл левого сайдбара на странице Сравнение в зависимости от Login/UnLogin
  $(".login__checkbox").bind("change", function (e) {
    if ($(this).prop("checked")) {
      colSl = 3;
      $(".compare .personal__aside").show();
      $(".compare__wrap").addClass("compare__wrap-login");
      $(".compare__block").slick("slickSetOption", "slidesToShow", colSl, false);
      //$("#pb-info__col1-input, #pb-info__col2-input, #pb-info__col3-input").fadeIn(200);
      //$(".pb-info__button").text("Сохранить");
    } else {
      colSl = 4;
      $(".compare .personal__aside").hide();
      $(".compare__wrap").removeClass("compare__wrap-login");
      $(".compare__block").slick("slickSetOption", "slidesToShow", colSl, false);
      //$(".compare__block").slick({slidesToShow: 4});
      // $("#pb-info__col1-input, #pb-info__col2-input, #pb-info__col3-input").fadeOut(0);
      // $("#pb-info__col1-text, #pb-info__col2-text").fadeIn(200);
      // $(".pb-info__button").text("Редактировать");
    }
  });

  $(".popup-login__form").on("submit", function(e) {
    e.preventDefault();
    var email = this["USER_LOGIN"].value;
    var password = this["USER_PASSWORD"].value;
    $.ajax({
      type: 'POST',
      url: "/ajax/ajax_func.php",
      data: {
        email: email,
        password: password,
        type: "checkPassword",
        sessid: BX.bitrix_sessid(),
      },
      success: function(data) {
        console.log({ data });
        if (data.status) {
          window.location.reload();
          // $(this).submit();
        } else {
          console.log("FAILED");
        }
      },
      error: function(error) {
        console.log("FAILED");
      },
    });
  });
});