$(document).ready(function() {
  var $clearAll, $maxPrice, $minPrice;
  var lastTimeout = null;
  var header = $('.header');
  var headerWrapper = $('.header__wrapper');

  var debounce = function(callback, time) {
    if (lastTimeout) {
      clearTimeout(lastTimeout);
    }
    lastTimeout = setTimeout(callback, time);
  };

  $(window).scroll(function() {
    if ($(this).scrollTop() > 1) {
      header.css('min-height', header.height() + 'px');
      headerWrapper.addClass('header__wrapper--fix');
    } else {
      headerWrapper.removeClass('header__wrapper--fix');
    }
  });

  $.fn.updateCartHeader();
  if ($(document).find('#btnSubmitOrder').is('div')) {
    submitForm();
  }
  $(document).on('click', '#cart_btn_open_login', function() {
    return $(document).find('.top-sign .top-sign__on').click();
  });
  $(document).find('form[is-search-form] input[type="text"]').prop('required', true);
  $(document).on('click', '.shopcart-nav1 label', function() {
    if (!$(document).find('#' + $(this).attr('for')).hasClass('slide-disable')) {
      console.log($(this).position().left);
      return $(document).find('.layout_cart_menu').animate({
        width: $(this).position().left + 'px'
      }, 500);
    }
  });
  $(document).on('click', '#btnNextSlide', function() {
    var inputCount, slideNum;
    inputCount = $(document).find('.shopcart-nav1 input').length;
    slideNum = 1;
    return $(document).find('.shopcart-nav1 input[type="radio"]').each(function() {
      var $count, $formIsValid, sBlock;
      if ($(this).prop('checked')) {
        slideNum = $(this).attr('data-num');
      }
      console.log(slideNum);
      if (!--inputCount) {
        if (parseInt(slideNum) === 1) {
          $(document).find('.shopcart-nav1 input#shopcart-tab' + (
            parseInt(slideNum) + 1
          ) + '').removeClass('slide-disable');
          $(document).find('.shopcart-nav1 label[for="shopcart-tab' + (
            parseInt(slideNum) + 1
          ) + '"]').click();
          if ((
            parseInt(slideNum) + 1
          ) === 4) {
            $(document).find('#btnSubmitOrder').removeClass('hidden');
            $(document).find('#btnNextSlide').addClass('hidden');
          }
        } else if (parseInt(slideNum) === 2) {
          if ($(document).find('#sci-contact-tab1').prop('checked')) {
            sBlock = $(document).find('#sci-contact-content1');
          } else {
            sBlock = $(document).find('#sci-contact-content2');
          }
          $count = sBlock.find('input').length;
          $formIsValid = true;
          sBlock.find('input').each(function() {
            if (!$(this).val() && $(this).prop('required')) {
              $formIsValid = false;
            }
            if (!--$count) {
              if ($formIsValid) {
                $(document).find('.shopcart-nav1 input#shopcart-tab' + (
                  parseInt(slideNum) + 1
                ) + '').removeClass('slide-disable');
                $(document).find('.shopcart-nav1 label[for="shopcart-tab' + (
                  parseInt(slideNum) + 1
                ) + '"]').click();
                if ((
                  parseInt(slideNum) + 1
                ) === 4) {
                  $(document).find('#btnSubmitOrder').removeClass('hidden');
                  return $(document).find('#btnNextSlide').addClass('hidden');
                }
              } else {
                return $.fn.setPushUp("Не заполнены поля", "Поля обязательные к заполнению небыли заполнены", false, "message", false, 5000);
              }
            }
          });
        } else if (parseInt(slideNum) === 3) {
          $formIsValid = true;
          if ($(document).find('#sci-delivery-tab1').prop('checked') || $(document).find('#sci-delivery-tab3').prop('checked') ||
            $(document).find('#sci-delivery-tab4').prop('checked') || $(document).find('#sci-delivery-tab5').prop('checked') ||
            $(document).find('#sci-delivery-tab6').prop('checked')) {
            if (!$(document).find('#so_city_val').val()) {
              $formIsValid = false;
            }
            if (!$(document).find('#sci-delivery-street').val()) {
              $formIsValid = false;
            }
            if (!$(document).find('#sci-delivery-building').val()) {
              $formIsValid = false;
            }
            if (!$(document).find('#sci-delivery-apartment').val()) {
              $formIsValid = false;
            }
          } else {
            if (!$(document).find('#so_city_alt_val').val()) {
              $formIsValid = false;
            }
          }
          if ($formIsValid) {
            $(document).find('.shopcart-nav1 input#shopcart-tab' + (
              parseInt(slideNum) + 1
            ) + '').removeClass('slide-disable');
            $(document).find('.shopcart-nav1 label[for="shopcart-tab' + (
              parseInt(slideNum) + 1
            ) + '"]').click();
            if ((
              parseInt(slideNum) + 1
            ) === 4) {
              $(document).find('#btnSubmitOrder').removeClass('hidden');
              $(document).find('#btnNextSlide').addClass('hidden');
            }
          } else {
            $.fn.setPushUp("Не заполнены поля", "Поля обязательные к заполнению небыли заполнены", false, "message", false, 5000);
          }
        }
      }
      if (parseInt(slideNum) === 2) {
        console.log('#shopcart-tab' + (
          parseInt(slideNum) + 1
        ));
        if (!$(document).find('.shopcart-nav1 input#shopcart-tab' + (
          parseInt(slideNum) + 1
        ) + '').hasClass('slide-disable')) {
          console.log('1 #shopcart-tab' + (
            parseInt(slideNum) + 1
          ));
          return $(document).find('.shopcart-sidebar__delivery').removeClass('dsb-hidden');
        }
      }
    });
  });
  $(document).on('click', '.shopcart-nav1 label', function() {
    var slideNum;
    slideNum = $(document).find('.shopcart-nav1 input[id="' + $(this).attr('for') + '"]').attr('data-num');
    if (parseInt(slideNum) === 4) {
      $(document).find('#btnSubmitOrder').removeClass('hidden');
      return $(document).find('#btnNextSlide').addClass('hidden');
    } else {
      $(document).find('#btnSubmitOrder').addClass('hidden');
      return $(document).find('#btnNextSlide').removeClass('hidden');
    }
  });
  $(document).on('submit', '#popap-call form', function() {
    var $this, form_id, name, phone;
    $this = $(this);
    name = $(this).find('input[name="name"]').val();
    phone = $(this).find('input[name="phone"]').val();
    form_id = $(this).find('input[name="form_id"]').val();
    if (name && phone && form_id) {
      $.ajax({
        url: '/ajax/add_cb.php',
        type: 'POST',
        data: {
          name: name,
          phone: phone,
          form_id: form_id
        },
        success: function(data) {
          $this.find('.form_msg').css('display', 'block');
          $this.find('.form_msg').html('Ваша заявка принятя. Наши менеджеры свяжутся с вами, ожидайте.');
          return $this.find('button, label, input').css('display', 'none');
        }
      });
    } else {
      $this.find('.form_msg').css('display', 'block');
      $this.find('.form_msg').html('Заполните все поля для отправки.');
    }
    return false;
  });
  $(document).on('submit', '#popap-buy-one-click form', function() {
    var $this, email, form_id, name, phone, prod_id, prod_name;
    $this = $(this);
    name = $(this).find('input[name="name"]').val();
    phone = $(this).find('input[name="phone"]').val();
    form_id = $(this).find('input[name="form_id"]').val();
    prod_id = $(this).find('input[name="prod_id"]').val();
    prod_name = $(this).find('input[name="prod_name"]').val();
    email = $(this).find('input[name="email"]').val();
    if (name && phone && form_id && email && prod_id && prod_name) {
      $.ajax({
        url: '/ajax/add_cb.php',
        type: 'POST',
        data: {
          name: name,
          phone: phone,
          form_id: form_id,
          prod_id: prod_id,
          prod_name: prod_name,
          email: email
        },
        success: function(data) {
          $this.find('.form_msg').css('display', 'block');
          $this.find('.form_msg').html('Ваша заявка принятя. Наши менеджеры свяжутся с вами в течении 15 минут.');
          return $this.find('button, label, input').css('display', 'none');
        }
      });
    } else {
      $this.find('.form_msg').css('display', 'block');
      $this.find('.form_msg').html('Заполните все поля для отправки.');
    }
    return false;
  });
  $(document).on('submit', '#popap-buy-one-click-cart form', function() {
    var $this, email, form_id, name, phone;
    $this = $(this);
    name = $(this).find('input[name="name"]').val();
    phone = $(this).find('input[name="phone"]').val();
    form_id = $(this).find('input[name="form_id"]').val();
    email = $(this).find('input[name="email"]').val();
    if (name && phone && form_id && email) {
      $.ajax({
        url: '/ajax/add_cb.php',
        type: 'POST',
        data: {
          name: name,
          phone: phone,
          form_id: form_id,
          email: email
        },
        success: function(data) {
          $this.find('.form_msg').css('display', 'block');
          $this.find('.form_msg').html('Ваша заявка принятя. Наши менеджеры свяжутся с вами в течении 15 минут.');
          $this.find('button, label, input').css('display', 'none');
          return setTimeout(function() {
            return $.fn.refreshCart();
          }, 3000);
        }
      });
    } else {
      $this.find('.form_msg').css('display', 'block');
      $this.find('.form_msg').html('Заполните все поля для отправки.');
    }
    return false;
  });
  if ($('#slider-range-alt').is('span')) {
    $minPrice = parseInt($('#price-start-alt').attr('min'));
    $maxPrice = parseInt($('#price-start-alt').attr('max'));
    if ($minPrice && $maxPrice) {
      $('#slider-range-alt').slider({
        range: true,
        min: $minPrice,
        max: $maxPrice,
        step: 100,
        values: [$minPrice, $maxPrice],
        slide: function(event, ui) {
          $('#price-start-alt').val(ui.values[0]);
          $('#price-end-alt').val(ui.values[1]);
          $(document).find('input[name="' + $('#price-end-alt').attr('data-name') + '"]').prop('checked', false);
        }
      });
      $('#price-start-alt').val($('#slider-range-alt').slider('values', 0));
      $('#price-end-alt').val($('#slider-range-alt').slider('values', 1));
      $('#price-start-alt').change(function() {
        var inputStart;
        $(document).find('input[name="' + $(this).attr('data-name') + '"]').prop('checked', false);
        inputStart = $(this).val();
        if (inputStart > $maxPrice) {
          inputStart = $maxPrice;
        }
        if (inputStart < $minPrice) {
          inputStart = $minPrice;
        }
        $('#slider-range-alt').slider('values', 0, inputStart);
        $(this).val(inputStart);
      });
      $('#price-end-alt').change(function() {
        var inputEnd;
        $(document).find('input[name="' + $(this).attr('data-name') + '"]').prop('checked', false);
        inputEnd = $(this).val();
        if (inputEnd > $maxPrice) {
          inputEnd = $maxPrice;
        }
        if (inputEnd < $minPrice) {
          inputEnd = $minPrice;
        }
        $('#slider-range-alt').slider('values', 1, inputEnd);
        $(this).val(inputEnd);
      });
    }
  }
  $(document).on('click', '.offer_prop_item', function() {
    var itemID, propCode, propID, title;
    propCode = $(this).attr('data-prop-code');
    propID = $(this).attr('data-prop-id');
    title = $(this).attr('data-title');
    itemID = $(this).attr('data-id');
    if (!$(this).hasClass('propDisabled')) {
      if ($(this).hasClass('active')) {
        $(document).find('.offers_prop[data-code="' + propCode + '"] .offer_prop_item').removeClass('active');
        $(document).find('.offers_prop[data-code="' + propCode + '"] .prop_title>span').html('Не выбрано');
      } else {
        $(document).find('.offers_prop[data-code="' + propCode + '"] .offer_prop_item').removeClass('active');
        $(document).find('.offers_prop[data-code="' + propCode + '"] .prop_title>span').html(title);
        $(this).addClass('active');
      }
      return $.fn.checkPropParams();
    }
  });
  $(document).on('change', 'input.catalog-filter__checkbox', function() {
    var dataMaxName, dataMaxValue, dataMinName, dataMinValue, dataName, dataPropTitle, dataValue, dataValueTitle, elementFilter;
    if (!$(this).hasClass('catalogPrice')) {
      dataValue = $(this).val();
      dataValueTitle = $(this).attr('data-value');
      dataPropTitle = $(this).attr('data-title');
      dataName = $(this).attr('name');
      if ($(this).prop('checked')) {
        elementFilter = '<div class="cb-filter__param" data-id="' + dataName + '">';
        elementFilter += '' + dataPropTitle + dataValueTitle;
        elementFilter += '<input type="hidden" name="' + dataName + '" value="' + dataValue + '">';
        elementFilter += '<span>×</span>';
        elementFilter += '</div>';
        $(document).find(".cb-filter").prepend($(elementFilter));
      } else {
        $(document).find('.cb-filter__param[data-id="' + dataName + '"]').remove();
      }
    } else {
      dataPropTitle = $(this).attr('data-title');
      dataMinName = $(this).attr('data-name-min');
      dataMaxName = $(this).attr('data-name-max');
      dataMinValue = $(document).find('.catalog-filter input[name="' + dataMinName + '"]').val();
      dataMaxValue = $(document).find('.catalog-filter input[name="' + dataMaxName + '"]').val();
      $(document).find('.cb-filter__param[data-id="' + dataMinName + dataMaxName + '"]').remove();
      if ($(this).prop('checked')) {
        elementFilter = '<div class="cb-filter__param cb-filter__param-hidden" data-id="' + dataMinName + dataMaxName + '">';
        elementFilter += dataPropTitle + 'от: ' + dataMinValue + " до: " + dataMaxValue;
        elementFilter += '<input type="hidden" name="' + dataMinName + '" value="' + dataMinValue + '">';
        elementFilter += '<input type="hidden" name="' + dataMaxName + '" value="' + dataMaxValue + '">';
        elementFilter += '<span>×</span>';
        elementFilter += '</div>';
        $(document).find(".cb-filter").prepend($(elementFilter));
      }
    }
    return $.fn.ajaxLoadCatalog();
  });
  $clearAll = false;
  $(document).on('click', '.cb-filter__param>span', function() {
    var $el;
    $el = $(this).parent('div');
    $(document).find('.catalog-filter input[name="' + $el.attr('data-id') + '"]').prop("checked", false);
    $el.remove();
    if ($clearAll === false) {
      return $.fn.ajaxLoadCatalog();
    }
  });
  $(document).on('click', '.cb-filter__clear', function() {
    $clearAll = true;
    $(document).find('.cb-filter__param>span').click();
    $clearAll = false;
    return $.fn.ajaxLoadCatalog();
  });
  $(document).on('click', '.ajaxPageNav .cb-nav-pagination__item a', function(e) {
    var $parentEl;
    e.preventDefault();
    $parentEl = $(this).parent('div');
    if (!$parentEl.hasClass('active')) {
      $(document).find('.ajaxPageNav .cb-nav-pagination__item').removeClass('active');
      $parentEl.addClass('active');
    }
    $.fn.ajaxLoadCatalog();
    return false;
  });
  $(document).on('mouseenter', '.product-card', function() {
    $(this).children('.product-card__img').slick({
      arrows: true,
      dots: false,
      infinite: true,
      speed: 1000
    });
    return $(this).children('.p-nav-top').fadeIn(200);
  });
  $(document).on('mouseleave', '.product-card', function() {
    $(this).children('.product-card__img').slick('unslick');
    $(this).children('.p-nav-top').fadeOut(200);
  });
  $(document).on('change', 'select[name="countOnPage"], select[name="sort_by"]', function() {
    return $.fn.ajaxLoadCatalog();
  });
  $(document).on('click', '.addToCartBtn', function() {
    var $this, productID;
    if (!$(this).hasClass('addToCartBtn_dis')) {
      $this = $(this);
      $(this).addClass('addToCartBtn_dis');
      productID = $(this).attr('data-id');
      if (productID) {
        return $.ajax({
          url: '/ajax/add_to_cart.php',
          type: 'POST',
          data: {
            PRODUCT_ID: productID,
            METHOD_CART: 'add',
            AJAX_MIN_CART: 'Y'
          },
          success: function(data) {
            $.fn.setPushUp("Товар добавлен", "Товар был успешно добавлен в вашу корзину", false, "message", false, 5000, undefined, 'is-added-to-cart');
            $this.removeClass('addToCartBtn_dis');
            var previewCart = $('.preview-shopcart');
            var showCartClass = 'preview-card--show';

            previewCart.addClass(showCartClass);
            var showMiniCart = function() {
              if (previewCart.hasClass(showCartClass)) {
                previewCart.removeClass(showCartClass);
              }
            };
            debounce(showMiniCart, 3500);
            if ($this.hasClass('product-sidebar__button')) {
              $this.addClass('dsb-hidden');
              $this.after('<a class="product-sidebar__button goToFcnCart" href="/cart/" data-id="' + productID + '">В корзину</a>');
            }
            $.fn.updateMiniCart(data);
            if ($this.hasClass('addToCartBtn_inCart')) {
              return $.fn.refreshCart();
            }
          }
        });
      }
    }
  });

  $(document).on('click', function(event){
    var showMiniCartClass = 'preview-card--show';
    var $miniCart = $('.preview-shopcart');

    if (
      !$(event.target).closest('.addToCartBtn').length > 0
      && !$(event.target).closest('.preview-shopcart').length > 0
      && $miniCart.hasClass(showMiniCartClass)
    ) {
      if (lastTimeout) {
        clearTimeout(lastTimeout);
      }

      $miniCart.removeClass(showMiniCartClass);
    }
  });

  $(document).on('click', '.preview-prod-bottom__button-cart', function() {
    var $cartItemID;
    $cartItemID = $(this).attr('data-cart-item');
    if ($cartItemID) {
      $(document).find('#mini_cart_header .preview-prod[data-cart-item="' + $cartItemID + '"]').remove();
      return $.ajax({
        url: '/ajax/add_to_cart.php',
        type: 'POST',
        data: {
          PRODUCT_ID: $cartItemID,
          METHOD_CART: 'delete',
          AJAX_MIN_CART: 'Y'
        },
        success: function(data) {
          $.fn.setPushUp("Товар удален", "Товар был удален из вашей корзины", false, "message", false, 5000);
          return $.fn.updateMiniCart(data);
        }
      });
    }
  });
  $(document).on('click', '.preview-prod-bottom__button-favorite', function() {
    var $cartItemID;
    $cartItemID = $(this).attr('data-cart-item');
    if ($cartItemID) {
      $(document).find('#mini_favorite_header .preview-prod[data-cart-item="' + $cartItemID + '"]').remove();
      return $.ajax({
        type: 'POST',
        url: '/ajax/ajax_func.php',
        data: {
          change_favorite_list: 'Y',
          product_id: $cartItemID,
          AJAX_MIN_FAVORITE: 'Y'
        },
        success: function(data) {
          $.fn.setPushUp("Товар удален", "Товар был удален из избраных товаров", false, "message", false, 5000);
          $.fn.updateMiniFavorite(data);
          if ($(document).find('.addToFavoriteListOnFP').is('div')) {
            $.fn.ajaxLoadCatalog();
          }
          if ($(document).find('.addToFavoriteListOnFP_NOT_ITEM').is('div')) {
            return location.href = '/user/favorite/';
          }
        }
      });
    }
  });
  $(document).on('click', '.preview-prod-bottom__button-compare', function() {
    var $cartItemID;
    $cartItemID = $(this).attr('data-cart-item');
    if ($cartItemID) {
      $(document).find('#mini_compare_header .preview-prod[data-cart-item="' + $cartItemID + '"]').remove();
      return $.ajax({
        type: 'POST',
        url: '/ajax/ajax_func.php',
        data: {
          change_compare_list: 'Y',
          product_id: $cartItemID,
          AJAX_MIN_COMPARE: 'Y'
        },
        success: function(data) {
          $.fn.setPushUp("Товар удален", "Товар был удален из списков сравнения", false, "message", false, 5000);
          $(document).find('.compare-page-item[data-id="' + $cartItemID + '"] .compare__basket.hidden-remove').click();
          return $.fn.updateMiniCompare(data);
        }
      });
    }
  });
  $(document).on('click', '.sci-top__count-up, .sci-top__count-down', function() {
    var cartItemID, countProd;
    cartItemID = $(this).attr('data-id');
    countProd = parseInt($(this).attr('data-q'));
    if ($(this).hasClass('sci-top__count-up')) {
      countProd = countProd + 1;
    } else {
      countProd = countProd - 1;
    }
    if (countProd > 0) {
      $(document).find('.sci-product[data-id="' + cartItemID + '"] .sci-top__count span').html(countProd);
      return $.ajax({
        url: '/ajax/add_to_cart.php',
        type: 'POST',
        data: {
          PRODUCT_ID: cartItemID,
          METHOD_CART: 'CHANGE_COUNT',
          AJAX_CART: 'Y',
          COUNT: countProd
        },
        success: function(data) {
          $.fn.updateCart(data);
          return $.fn.refreshMiniCart();
        }
      });
    }
  });
  $(document).on('click', '.sci-top__remove', function() {
    var $cartItemID;
    $cartItemID = $(this).attr('data-id');
    if ($cartItemID) {
      $(document).find('#shopcart-item1 .sci-product[data-id="' + $cartItemID + '"]').remove();
      return $.ajax({
        url: '/ajax/add_to_cart.php',
        type: 'POST',
        data: {
          PRODUCT_ID: $cartItemID,
          METHOD_CART: 'delete',
          AJAX_CART: 'Y'
        },
        success: function(data) {
          $.fn.updateCart(data);
          return $.fn.setPushUp("Товар удален", "Товар был удален из вашей корзины", false, "message", false, 5000);
        }
      });
    }
  });
  $(document).on('click', '.square-color', function(e) {
    var activePhoto, dcolor;
    if (!$(this).hasClass('propDisabled')) {
      dcolor = $(this).attr('data-color');
      $(document).find('.product-photo__left img').removeClass('active');
      $(document).find('.product-photo__left .pp__is_offer').addClass('pp__is_offer__disable');
      $(document).find('.product-photo__left .pp__is_offer[data-color="' + dcolor + '"]').removeClass('pp__is_offer__disable');
      $(document).find('.product-photo__right .pp__big_photo').removeClass('active');
      $(document).find('.product-photo__right .pp__is_offer').addClass('pp__is_offer__disable');
      $(document).find('.product-photo__right .pp__is_offer').attr('data-fancybox', '');
      $(document).find('.product-photo__right .pp__is_offer[data-color="' + dcolor + '"]').removeClass('pp__is_offer__disable');
      $(document).find('.product-photo__right .pp__is_offer[data-color="' + dcolor + '"]').attr('data-fancybox', 'gallery-prod');
      activePhoto = null;
      if ($(document).find('.product-photo__left .pp__is_offer[data-color="' + dcolor + '"]').is('img')) {
        activePhoto = $(document).find('.product-photo__left .pp__is_offer[data-color="' + dcolor + '"]').eq(0);
      } else if ($(document).find('.product-photo__left .pp__is_prod').is('img')) {
        activePhoto = $(document).find('.product-photo__left .pp__is_prod').eq(0);
      }
      if (activePhoto) {
        activePhoto.addClass('active');
        return $(document).find('.product-photo__right .pp__big_photo[data-photo-id="' + activePhoto.attr('data-photo-id') + '"]').addClass('active');
      }
    }
  });
  $(document).on('click', '.product-photo__left img', function() {
    $(document).find('.product-photo__left img').removeClass('active');
    $(document).find('.product-photo__right .pp__big_photo').removeClass('active');
    $(this).addClass('active');
    return $(document).find('.product-photo__right .pp__big_photo[data-photo-id="' + $(this).attr('data-photo-id') + '"]').addClass('active');
  });
  $(document).on('click', '.addToFavoriteList', function() {
    var $this, prodID;
    prodID = $(this).attr('data-id');
    $this = $(this);
    return $.ajax({
      type: 'POST',
      url: '/ajax/ajax_func.php',
      data: {
        change_favorite_list: 'Y',
        product_id: prodID,
        AJAX_MIN_FAVORITE: 'Y'
      },
      success: function(data) {
        var $miniFavorite = $('#mini_favorite_header');
        var showMiniFavoriteClass = 'preview-heart--show';

        $miniFavorite.addClass(showMiniFavoriteClass);

        var showMiniFavorite = function() {
          if ($miniFavorite.hasClass(showMiniFavoriteClass)) {
            $miniFavorite.removeClass(showMiniFavoriteClass);
          }
        };

        debounce(showMiniFavorite, 3500);

        $.fn.updateMiniFavorite(data);
        if ($this.hasClass('notActive')) {
          $this.removeClass('notActive');
          $.fn.setPushUp("Закладки", "Товар был добавлен в избраное", false, "message", false, 5000);
          $(document).find('.addToFavoriteList[data-id="' + prodID + '"]').parent('label').find('input[type="checkbox"]').prop('checked', true);
        } else {
          $this.addClass('notActive');
          $.fn.setPushUp("Избраное", "Товар был удален из избраного", false, "message", false, 5000);
          $(document).find('.addToFavoriteList[data-id="' + prodID + '"]').parent('label').find('input[type="checkbox"]').prop('checked', false);
        }
        if ($(document).find('.addToFavoriteListOnFP').is('div')) {
          $.fn.ajaxLoadCatalog();
        }
        if ($(document).find('.addToFavoriteListOnFP_NOT_ITEM').is('div')) {
          return location.href = '/user/favorite/';
        }
      }
    });
  });
  $(document).on('click', '.addToCompareList', function() {
    var $this, prodID;
    prodID = $(this).attr('data-id');
    $this = $(this);
    return $.ajax({
      type: 'POST',
      url: '/ajax/ajax_func.php',
      data: {
        change_compare_list: 'Y',
        product_id: prodID,
        AJAX_MIN_COMPARE: 'Y'
      },
      success: function(data) {
        var $miniCompare = $('#mini_compare_header');
        var showMiniCompareClass = 'preview-heart--show';

        $miniCompare.addClass(showMiniCompareClass);

        var showMiniCompare = function() {
          if ($miniCompare.hasClass(showMiniCompareClass)) {
            $miniCompare.removeClass(showMiniCompareClass);
          }
        };

        debounce(showMiniCompare, 3500);

        $.fn.updateMiniCompare(data);

        if ($this.hasClass('notActive')) {
          $.fn.setPushUp("Сравнение", "Товар был добавлен в сравнение", false, "message", false, 5000);
          return $(document).find('.addToCompareList[data-id="' + prodID + '"]').removeClass('notActive');
        } else {
          $.fn.setPushUp("Сравнение", "Товар был удален из сравнения", false, "message", false, 5000);
          return $(document).find('.addToCompareList[data-id="' + prodID + '"]').addClass('notActive');
        }
      }
    });
  });
  $.fn.updateDateSaleOrder();
  $(document).on('click', '#soDelivPopUp', function() {
    return $(document).find('.SDEK_selectPVZ').click();
  });
  $(document).on('click', '.rb_so', function() {
    if ($(this).attr('data-prop')) {
      return $.fn.changeRadioButtonSaleOrder($(this).attr('data-prop'));
    }
  });
  $(document).find('#module_so').bind('DOMSubtreeModified', function() {
    var soModule;
    soModule = $(document).find('#module_so');
    if (soModule.find('.wrewfwer .wrewfwer_ajax').is('span')) {
      soModule.find('.wrewfwer .wrewfwer_ajax').remove();
      return $.fn.updateDateSaleOrder();
    }
  });
  $(document).on('click', '#backToCatalog', function() {
    return location.href = '/catalog/';
  });
  $(document).on('change', '#so_city_val', function() {
    var $this, soBlock, soCityAlt, soCityAltID, soCityID, soModule;
    soCityID = $(document).find('#so_city');
    soCityAltID = $(document).find('#so_city_alt');
    soCityAlt = $(document).find('#so_city_alt_val');
    soBlock = $(document).find('#so_main_block');
    soModule = $(document).find('#module_so');
    $this = $(this);
    return setTimeout(function() {
      if (soCityID.val() === soCityID.attr('data-old')) {
        $this.val($this.attr('data-old'));
        return soCityID.val(soCityID.attr('data-old'));
      } else {
        soCityID.attr('data-old', $this.val());
        $this.attr('data-old', soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop') + '"]').val(soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop-val') + '"]').val($this.val());
        soModule.find('[name="' + $this.attr('data-city-prop-alt') + '"]').val(soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop-val-alt') + '"]').val($this.val());
        soCityAltID.val(soCityID.val());
        soCityAlt.val($this.val());
        soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
        return submitForm();
      }
    }, 300);
  });
  $(document).on('change', '#so_city_alt_val', function() {
    var $this, soBlock, soCityAlt, soCityAltID, soCityID, soModule;
    soCityID = $(document).find('#so_city_alt');
    soCityAltID = $(document).find('#so_city');
    soCityAlt = $(document).find('#so_city_val');
    soBlock = $(document).find('#so_main_block');
    soModule = $(document).find('#module_so');
    $this = $(this);
    return setTimeout(function() {
      if (soCityID.val() === soCityID.attr('data-old')) {
        $this.val($this.attr('data-old'));
        return soCityID.val(soCityID.attr('data-old'));
      } else {
        soCityID.attr('data-old', $this.val());
        $this.attr('data-old', soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop') + '"]').val(soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop-val') + '"]').val($this.val());
        soModule.find('[name="' + $this.attr('data-city-prop-alt') + '"]').val(soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop-val-alt') + '"]').val($this.val());
        soCityAltID.val(soCityID.val());
        soCityAlt.val($this.val());
        soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
        return submitForm();
      }
    }, 300);
  });
  $(document).on('change',
    '[name="sci-contact__fio"], [name="sci-contact__tel"], [name="sci-contact__ur-name"], [name="sci-contact__ur-phone"], [name="so_city_val"], [name="so_city_alt_val"], [name="sci-delivery-street"], [name="sci-delivery-building"], [name="sci-delivery-apartment"], [name="sci-delivery-date"], [name="sci-delivery-time"], [name="ORDER_PROP_37"], [name="ORDER_PROP_36"]',
    function() {
      return $.fn.updateSideInfo();
    });
  return $(document).on('click', '#btnSubmitOrder', function() {
    var firstBlock, hasError, inputCount, secondBlock, soBlock, soModule;
    soBlock = $(document).find('#so_main_block');
    soModule = $(document).find('#module_so');
    firstBlock = '#shopcart-item2 #sci-contact-content1';
    secondBlock = '#shopcart-item3 #sci-delivery-content1';
    if ($(document).find('#sci-contact-tab2').prop('checked')) {
      firstBlock = '#shopcart-item2 #sci-contact-content2';
    }
    if ($(document).find('#sci-delivery-tab2').prop('checked')) {
      secondBlock = '#shopcart-item3 #sci-delivery-content2';
    }
    inputCount = $(document).find(firstBlock + " input, " + firstBlock + " textarea, " + firstBlock + " select").length;
    hasError = false;
    return $(document).find(firstBlock + " input, " + firstBlock + " textarea, " + firstBlock + " select").each(function() {
      var valEl;
      if ($(this).attr('data-prop')) {
        valEl = $(this).val();
        if ($(this).prop('required') && !valEl) {
          $(document).find('#shopcart-tab2').click();
          $(this).css({
            "border-color": '#ef0000'
          });
          $(this).on('focus', function() {
            return $(this).css({
              "border-color": '#C4C4C4'
            });
          });
          hasError = true;
        }
        if ($(this).is('select')) {
          soModule.find('[name="' + $(this).attr('data-prop') + '"] option[value="' + valEl + '"]').prop('selected', true);
        } else {
          soModule.find('[name="' + $(this).attr('data-prop') + '"]').val(valEl);
        }
      }
      if (!--inputCount) {
        if (!hasError) {
          if (secondBlock) {
            inputCount = $(document).find(secondBlock + " input, " + secondBlock + " textarea, " + secondBlock + " select").length;
            return $(document).find(secondBlock + " input, " + secondBlock + " textarea, " + secondBlock + " select").each(function() {
              if ($(this).attr('data-prop')) {
                valEl = $(this).val();
                if ($(this).prop('required') && !valEl) {
                  $(document).find('#shopcart-tab3').click();
                  $(this).css({
                    "border-color": '#ef0000'
                  });
                  $(this).on('focus', function() {
                    return $(this).css({
                      "border-color": '#C4C4C4'
                    });
                  });
                  hasError = true;
                }
                soModule.find('[name="' + $(this).attr('data-prop') + '"]').val(valEl);
              }
              if (!--inputCount) {
                if (!hasError) {
                  soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
                  $(document).find('.layout_cart_menu').animate({
                    width: '100%'
                  }, 500);
                  return soModule.find('[name="submitbutton"]').click();
                }
              }
            });
          }
        }
      }
    });
  });
});

$.fn.updateSideInfo = function() {
  var deliveryPrice, soBlock, soModule, totalPrice, uAddress, uCity, uDeliveryDate, uDeliveryTime, uName, uPhone;
  soBlock = $(document).find('#so_main_block');
  soModule = $(document).find('#module_so');
  uName = '[name="sci-contact__fio"]';
  uPhone = '[name="sci-contact__tel"]';
  if (soModule.find('#PERSON_TYPE_2').prop('checked')) {
    uName = '[name="sci-contact__ur-name"]';
    uPhone = '[name="sci-contact__ur-phone"]';
  }
  soBlock.find('.shopcart-sidebar__buyer-fio').html(soBlock.find(uName).val());
  soBlock.find('.shopcart-sidebar__buyer-tel').html(soBlock.find(uPhone).val());
  uCity = soBlock.find('[name="so_city_val"]').val();
  uAddress = "";
  if (soBlock.find('[name="sci-delivery-street"]').val()) {
    uAddress += soBlock.find('[name="sci-delivery-street"]').val() + " ";
  }
  if (soBlock.find('[name="sci-delivery-building"]').val()) {
    uAddress += "д. " + soBlock.find('[name="sci-delivery-building"]').val() + " ";
  }
  if (soBlock.find('[name="sci-delivery-apartment"]').val()) {
    uAddress += "кв. " + soBlock.find('[name="sci-delivery-apartment"]').val() + " ";
  }
  uDeliveryDate = soBlock.find('[name="sci-delivery-date"]').val();
  uDeliveryTime = soBlock.find('[name="sci-delivery-time"]').val();
  deliveryPrice = '0';
  totalPrice = '0';
  soModule.find('.sale_order_full tfoot tr').each(function() {
    if ($(this).find('td').eq(0).find('b').is('b')) {
      if ($(this).find('td').eq(0).find('b').html().toString() === 'Доставка:') {
        deliveryPrice = $(this).find('td').eq(1).html().toString().replace('руб.', '');
      }
      if ($(this).find('td').eq(0).find('b').html().toString() === 'Итого:') {
        return totalPrice = $(this).find('td').eq(1).find('b').html().toString().replace('руб.', '');
      }
    }
  });
  uDeliveryTime = soModule.find('[for="ID_DELIVERY_ID_6"] .so_delivery_period').html();
  if (soModule.find('#ID_DELIVERY_ID_6').prop('checked')) {
    if (soModule.find('#PERSON_TYPE_2').prop('checked')) {
      uAddress = soModule.find('[name="ORDER_PROP_37"]').val();
    } else {
      uAddress = soModule.find('[name="ORDER_PROP_36"]').val();
    }
    uDeliveryDate = "";
    uDeliveryTime = "";
    uDeliveryTime = soModule.find('[for="ID_DELIVERY_ID_6"] .so_delivery_period').html();
    if (uAddress) {
      soBlock.find('.pickup_address span').html(uAddress);
    }
    soBlock.find('.pickup_summ span').html(deliveryPrice + '₽');
    soBlock.find('.pickup_date span').html(uDeliveryTime);
  }
  if (soModule.find('#ID_DELIVERY_ID_13').prop('checked')) {
    uCity = '';
    uAddress = $(document).find('label[for="ID_DELIVERY_ID_13"] .dsc_soa').html();
    deliveryPrice = $(document).find('label[for="ID_DELIVERY_ID_13"] .prs_soa').html().replace('руб.', '');
    uDeliveryTime = $(document).find('label[for="ID_DELIVERY_ID_13"] .so_delivery_period').html();
    soBlock.find('.sv_address').html($(document).find('label[for="ID_DELIVERY_ID_13"] .dsc_soa').html());
    soBlock.find('.sv_price span').html($(document).find('label[for="ID_DELIVERY_ID_13"] .prs_soa').html().replace('руб.', '') + '₽');
    soBlock.find('.sv_time span').html($(document).find('label[for="ID_DELIVERY_ID_13"] .so_delivery_period').html());
  }
  soBlock.find('.pickup_summ_alt span').html(deliveryPrice + '₽');
  soBlock.find('.shopcart-sidebar__delivery-price span').html(deliveryPrice);
  soBlock.find('#total_price_cart').html(totalPrice);
  soBlock.find('.shopcart-sidebar__delivery-city').html(uCity);
  soBlock.find('.shopcart-sidebar__delivery-address').html(uAddress);
  if (!uDeliveryDate) {
    soBlock.find('.shopcart-sidebar__delivery-date').hide();
  } else {
    soBlock.find('.shopcart-sidebar__delivery-date').show();
    soBlock.find('.shopcart-sidebar__delivery-date span').html(uDeliveryDate);
  }
  if (!uDeliveryTime) {
    return soBlock.find('.shopcart-sidebar__delivery-time').hide();
  } else {
    soBlock.find('.shopcart-sidebar__delivery-time').show();
    return soBlock.find('.shopcart-sidebar__delivery-time span').html(uDeliveryTime);
  }
};

$.fn.changeRadioButtonSaleOrder = function(l_name) {
  var soBlock, soModule;
  soBlock = $(document).find('#so_main_block');
  soModule = $(document).find('#module_so');
  if (soModule.find('[for="' + l_name + '"]').is('label')) {
    soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
    return soModule.find('[for="' + l_name + '"]').click();
  }
};

$.fn.updateDateSaleOrder = function() {
  var soBlock, soCity, soCityAlt, soCityAltID, soCityID, soModule;
  soBlock = $(document).find('#so_main_block');
  soModule = $(document).find('#module_so');
  soBlock.find('.rb_so').addClass('rb_so_disbled');
  soBlock.find('.rb_so').each(function() {
    var titleDeliv;
    if ($(this).attr('data-prop')) {
      if (soModule.find('#' + $(this).attr('data-prop') + '').is('input')) {
        titleDeliv = soModule.find('label[for="' + $(this).attr('data-prop') + '"]>b').eq(0).html();
        $(this).find('span').html(titleDeliv);
        $(this).find('span.sci-payment__radio').html('');
        $(this).removeClass('rb_so__hide');
        if (soModule.find('#' + $(this).attr('data-prop') + '').prop('checked')) {
          $(this).click();
        }
      } else {
        $(this).addClass('rb_so__hide');
      }
    }
    return $(this).removeClass('rb_so_disbled');
  });
  soModule.find('.sale_order_full_table input[name="DELIVERY_ID"]').each(function() {
    var delivID, indLav, titleDeliv;
    delivID = $(this).attr('id');
    if (!soBlock.find('.sci-delivery-tabs .rb_so[data-prop="' + delivID + '"]').is('label')) {
      indLav = parseInt(soBlock.find('.sci-delivery-tabs .rb_so').length) + 1;
      titleDeliv = soModule.find('label[for="' + delivID + '"]>b').eq(0).html();
      soBlock.find('.sci-delivery-tabs').prepend('<label data-prop="' + delivID + '" class="sci-delivery-tab rb_so" for="sci-delivery-tab' + indLav +
        '"><span>' + titleDeliv + '</span></label>');
      soBlock.find('.sci-delivery-tabs').prepend('<input id="sci-delivery-tab' + indLav + '" type="radio" name="delivery-tabs" class="rb_so_proxy">');
      if ($(this).prop('checked')) {
        return soBlock.find('.rb_so[data-prop="' + delivID + '"]').click();
      }
    }
  });
  soModule.find('.sale_order_full_table input[name="PAY_SYSTEM_ID"]').each(function() {
    var delivID, htmlNewEl, indLav, titleDeliv;
    delivID = $(this).attr('id');
    if (!soBlock.find('.sci-payment-tabs .rb_so[data-prop="' + delivID + '"]').is('label')) {
      indLav = parseInt(soBlock.find('.sci-payment-tabs .rb_so').length) + 1;
      titleDeliv = soModule.find('label[for="' + delivID + '"]>b').eq(0).html();
      htmlNewEl = $('<label class="sci-payment__tab rb_so" data-prop="' + delivID + '">');
      htmlNewEl.append('<input id="sci-payment-tab' + indLav + '" type="radio" name="payment-tabs" class="sci-payment__input">');
      htmlNewEl.append('<span class="sci-payment__radio"></span>');
      htmlNewEl.append('<span class="sci-payment__name">' + titleDeliv + '</span>');
      soBlock.find('.sci-payment-tabs').prepend(htmlNewEl);
      if ($(this).prop('checked')) {
        return soBlock.find('.rb_so[data-prop="' + delivID + '"]').click();
      }
    }
  });
  soBlock.find('input, textarea, select').each(function() {
    if ($(this).attr('data-change') !== 'Y' && $(this).attr('data-prop')) {
      if (!$(this).is('select')) {
        if (soModule.find('[name="' + $(this).attr('data-prop') + '"]').is('input')) {
          $(this).val(soModule.find('[name="' + $(this).attr('data-prop') + '"]').val());
          $(this).attr('data-change', 'Y');
        }
        if (soModule.find('[name="' + $(this).attr('data-prop-alt') + '"]').is('input')) {
          $(this).val(soModule.find('[name="' + $(this).attr('data-prop-alt') + '"]').val());
          return $(this).attr('data-change', 'Y');
        }
      } else {
        if (soModule.find('[name="' + $(this).attr('data-prop') + '"]').is('select')) {
          $(this).find('option[value="' + soModule.find('[name="' + $(this).attr('data-prop') + '"]').val() + '"]').prop('selected', true);
          return $(this).attr('data-change', 'Y');
        }
      }
    }
  });
  soCity = soBlock.find('#so_city_val');
  soCityID = soBlock.find('#so_city');
  soCityAlt = soBlock.find('#so_city_alt_val');
  soCityAltID = soBlock.find('#so_city_alt');
  if (soCity.attr('data-change') !== 'Y' && soCity.attr('data-city-prop') && soCity.attr('data-city-prop-val')) {
    if (soModule.find('[name="' + soCity.attr('data-city-prop-val') + '"]').is('input')) {
      soCity.val(soModule.find('[name="' + soCity.attr('data-city-prop-val') + '"]').val());
      soCityID.val(soModule.find('[name="' + soCity.attr('data-city-prop') + '"]').val());
      soCity.attr('data-old', soModule.find('[name="' + soCity.attr('data-city-prop-val') + '"]').val());
      soCityID.attr('data-old', soModule.find('[name="' + soCity.attr('data-city-prop') + '"]').val());
      soCityAlt.val(soModule.find('[name="' + soCityAlt.attr('data-city-prop-val') + '"]').val());
      soCityAltID.val(soModule.find('[name="' + soCityAlt.attr('data-city-prop') + '"]').val());
      soCityAlt.attr('data-old', soModule.find('[name="' + soCityAlt.attr('data-city-prop-val') + '"]').val());
      soCityAltID.attr('data-old', soModule.find('[name="' + soCityAlt.attr('data-city-prop') + '"]').val());
    }
    if (soModule.find('[name="' + soCity.attr('data-city-prop-val-alt') + '"]').is('input')) {
      soCity.val(soModule.find('[name="' + soCity.attr('data-city-prop-val-alt') + '"]').val());
      soCityID.val(soModule.find('[name="' + soCity.attr('data-city-prop-alt') + '"]').val());
      soCity.attr('data-old', soModule.find('[name="' + soCity.attr('data-city-prop-val-alt') + '"]').val());
      soCityID.attr('data-old', soModule.find('[name="' + soCity.attr('data-city-prop-alt') + '"]').val());
      soCityAlt.val(soModule.find('[name="' + soCityAlt.attr('data-city-prop-val-alt') + '"]').val());
      soCityAltID.val(soModule.find('[name="' + soCityAlt.attr('data-city-prop-alt') + '"]').val());
      soCityAlt.attr('data-old', soModule.find('[name="' + soCityAlt.attr('data-city-prop-val-alt') + '"]').val());
      soCityAltID.attr('data-old', soModule.find('[name="' + soCityAlt.attr('data-city-prop-alt') + '"]').val());
    }
  }
  $.fn.updateSideInfo();
  soModule.find('.errortext').each(function() {
    return $.fn.setPushUp("Ошибка", $(this).text(), false, "message", false, 5000);
  });
  return soBlock.find('.preloaderCatalog').removeClass('preloaderCatalogActive');
};

$.refreshCartInfo = function() {
  return $.ajax({
    url: '/ajax/add_to_cart.php',
    type: 'POST',
    data: {
      METHOD_CART: 'refredh_cart_info',
      AJAX_CART_INFO: 'Y'
    },
    success: function(data) {
      $.fn.updateCartInfo(data);
      return $.fn.updateCartHeader();
    }
  });
};

$.fn.updateCartInfo = function(data) {
  var $ft;
  $ft = $('<div></div>').append(data);
  return $(document).find('#cart_info_block').html($ft.html());
};

$.fn.updateCartHeader = function() {
  var cartSum, prodCount;
  if ($(document).find('.shopcart-tab[for="shopcart-tab1"]').is('label')) {
    prodCount = $(document).find('#cart_count_prod').html();
    cartSum = $(document).find('#cart_sum_prod').html();
    return $(document).find('.shopcart-tab[for="shopcart-tab1"] span').html(prodCount + ' товара, ' + cartSum + ' руб.');
  }
};

$.fn.refreshMiniCart = function() {
  return $.ajax({
    url: '/ajax/add_to_cart.php',
    type: 'POST',
    data: {
      METHOD_CART: 'refredh_mini_cart',
      AJAX_MIN_CART: 'Y'
    },
    success: function(data) {
      return $.fn.updateMiniCart(data);
    }
  });
};

$.fn.refreshCart = function() {
  return $.ajax({
    url: '/ajax/add_to_cart.php',
    type: 'POST',
    data: {
      METHOD_CART: 'refredh_cart',
      AJAX_CART: 'Y'
    },
    success: function(data) {
      return $.fn.updateCart(data);
    }
  });
};

// TODO: vuex? это какой-то пздц
$.fn.updateMiniCompare = function(data) {
  var emptyMiniCompareClass = 'preview-heart--empty';
  var $miniCompare = $(document).find('#mini_compare_header');
  var $miniCompareCounter = $(document).find('#mini_compare_header_counter');
  var $ft = $('<div></div>').append(data);

  if ($ft.find('.preview-prod').length > 0) {
    $miniCompare.removeClass(emptyMiniCompareClass);
  } else {
    $miniCompare.addClass(emptyMiniCompareClass);
  }

  $miniCompareCounter.html($ft.find('#mini_compare_header_counter').html());
  return $miniCompare.html($ft.find('#mini_compare_header').html());
};

$.fn.updateMiniFavorite = function(data) {
  var emptyMiniFavoriteClass = 'preview-heart--empty';
  var $miniFavorite = $(document).find('#mini_favorite_header');
  var $miniFavoriteCounter = $(document).find('#mini_favorite_header_counter');
  var $ft = $('<div></div>').append(data);

  if ($ft.find('.preview-prod').length > 0) {
    $miniFavorite.removeClass(emptyMiniFavoriteClass);
  } else {
    $miniFavorite.addClass(emptyMiniFavoriteClass);
  }

  $miniFavoriteCounter.html($ft.find('#mini_favorite_header_counter').html());
  return $miniFavorite.html($ft.find('#mini_favorite_header').html());
};

$.fn.updateMiniCart = function(data) {
  var emptyMiniCartClass = 'preview-shopcart--empty';
  var $miniCart = $(document).find('#mini_cart_header');
  var $miniCartCounter = $(document).find('#mini_cart_header_counter');
  var $ft = $('<div></div>').append(data);

  if ($ft.find('.preview-prod').length > 0) {
    $miniCart.removeClass(emptyMiniCartClass);
  } else {
    $miniCart.addClass(emptyMiniCartClass);
  }

  $miniCartCounter.html($ft.find('#mini_cart_header_counter').html());
  return $miniCart.html($ft.find('#mini_cart_header').html());
};

$.fn.updGlobalCityInCart = function(cityID) {
  var soModule;
  soModule = $(document).find('#module_so');
  if (soModule.is('div')) {
    soModule.find('[name="ORDER_PROP_18"]').val(cityID);
    return $.fn.updateCart();
  }
};

$.fn.updateCart = function(data) {
  var soBlock;
  soBlock = $(document).find('#so_main_block');
  $(document).find('#shopcart-item1').html(data);
  soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
  $.refreshCartInfo();
  return submitForm();
};

$.fn.offersByPropData = {};

$.fn.checkPropParams = function() {
  var $activeCount, $activePropObj, dataJSON;
  dataJSON = $(document).find('.offers_by_prop_json').attr('data-json');
  $.fn.offersByPropData = JSON.parse(dataJSON);
  $(document).find('.offers_prop .offer_prop_item').removeClass('propDisabled');
  $activeCount = $(document).find('.offers_prop .offer_prop_item.active').length;
  $activePropObj = {};
  return $(document).find('.offers_prop .offer_prop_item.active').each(function() {
    var filtResultByProp, ind, itemID, newURL, propCode, results;
    propCode = $(this).attr('data-prop-code');
    itemID = $(this).attr('data-id');
    $activePropObj[propCode] = itemID;
    if (!--$activeCount) {
      $(document).find('.offers_prop .offer_prop_item').each(function() {
        var filtResult, itemIDAlt, propCodeAlt;
        propCodeAlt = $(this).attr('data-prop-code');
        itemIDAlt = $(this).attr('data-id');
        filtResult = $.fn.offersByPropData.filter((
          function(_this) {
            return function(item) {
              var ind, resBool;
              if (item.props.length <= 0) {
                return false;
              }
              resBool = true;
              for (ind in $activePropObj) {
                if (ind !== propCodeAlt) {
                  if (item.props[ind]) {
                    if (item.props[ind].id !== $activePropObj[ind]) {
                      resBool = false;
                    }
                  } else {
                    resBool = false;
                  }
                }
              }
              if (item.props[propCodeAlt]) {
                if (item.props[propCodeAlt].id !== itemIDAlt) {
                  resBool = false;
                }
              } else {
                resBool = false;
              }
              return resBool;
            };
          }
        )(this));
        if (filtResult.length < 1) {
          return $(this).addClass('propDisabled');
        }
      });
      filtResultByProp = $.fn.offersByPropData.filter((
        function(_this) {
          return function(item) {
            var ind, resBool;
            if (item.props.length <= 0) {
              return false;
            }
            resBool = true;
            for (ind in $activePropObj) {
              if (item.props[ind]) {
                if (item.props[ind].id !== $activePropObj[ind]) {
                  resBool = false;
                }
              } else {
                resBool = false;
              }
            }
            return resBool;
          };
        }
      )(this));
      $(document).find('.addToCartBtn_mainPage').css('display', 'inline-block');
      $(document).find('.goToFcnCart').addClass('dsb-hidden');
      if ((
        $(document).find('.offers_prop').length === Object.keys($activePropObj).length
      ) && filtResultByProp[0]) {
        newURL = $.fn.updateURLParameter(window.location.href, 'offer', filtResultByProp[0].id_offer);
        window.history.replaceState('', '', newURL);
        $(document).find('.addToCartBtn_mainPage').attr('data-id', filtResultByProp[0].id_offer);
        if ($(document).find('.goToFcnCart[data-id="' + filtResultByProp[0].id_offer + '"]').is('a')) {
          $(document).find('.addToCartBtn_mainPage').addClass('dsb-hidden');
          $(document).find('.goToFcnCart[data-id="' + filtResultByProp[0].id_offer + '"]').removeClass('dsb-hidden');
        } else {
          $(document).find('.addToCartBtn_mainPage').removeClass('dsb-hidden');
        }
        $(document).find('.BOC_btn').attr('data-id', filtResultByProp[0].id_offer);
        $(document).find('.addToCartBtn').attr('data-id', filtResultByProp[0].id_offer);
        $(document).find('.mainBlockPrice .product-sidebar__total-price-price').html(filtResultByProp[0].new_price);
        if (filtResultByProp[0].difference_price) {
          $(document).find('.mainBlockPrice .product-sidebar__right-price').css('display', 'block');
        } else {
          $(document).find('.mainBlockPrice .product-sidebar__right-price').css('display', 'none');
        }
        $(document).find('.article_code_field').html(filtResultByProp[0].article);
        $(document).find('.isElementName').html(filtResultByProp[0].name);
        $(document).find('.mainBlockPrice .product-sidebar__profit>span').html(filtResultByProp[0].difference_price);
        $(document).find('.mainBlockPrice .product-sidebar__old-price>span').html(filtResultByProp[0].old_price);
        $(document).find('#top_prop_model_prod span').html(filtResultByProp[0].model_top);
        if (!filtResultByProp[0].model_top) {
          $(document).find('#top_prop_model_prod').addClass('hidden_top_prop');
        } else {
          $(document).find('#top_prop_model_prod').removeClass('hidden_top_prop');
        }
        $(document).find('#top_prop_code_prod span').html(filtResultByProp[0].prod_code_top);
        if (!filtResultByProp[0].prod_code_top) {
          $(document).find('#top_prop_code_prod').addClass('hidden_top_prop');
        } else {
          $(document).find('#top_prop_code_prod').removeClass('hidden_top_prop');
        }
        $(document).find('.offersPropertiesList').html('');
        results = [];
        for (ind in filtResultByProp[0].props) {
          if (filtResultByProp[0].props[ind].title) {
            results.push(
              $(document).find('.offersPropertiesList').append('<p class="product-content__value">' + filtResultByProp[0].props[ind].title + '</p>'));
          } else {
            results.push(void 0);
          }
        }
        return results;
      } else {
        return $(document).find('.addToCartBtn_mainPage').css('display', 'none');
      }
    }
  });
};

$.fn.ajaxLoadCatalog = function() {
  var $data, countOnPage, filtParamCount, sort_by, styleBlock, urlForSend;
  filtParamCount = $(document).find('.cb-filter .cb-filter__param').length;
  if (filtParamCount > 0) {
    $(document).find('.cb-filter .cb-filter__clear').removeClass('dnd-hide');
  } else {
    $(document).find('.cb-filter .cb-filter__clear').addClass('dnd-hide');
  }
  $(document).find('.preloaderCatalog').css({
    opacity: 0,
    display: 'block'
  });
  $(document).find('.preloaderCatalog').animate({
    opacity: 1
  }, 300);
  urlForSend = $(document).find('.ajaxPageNav .cb-nav-pagination__item.active').attr('data-href');
  styleBlock = 'v-block';
  countOnPage = $(document).find('select[name="countOnPage"]').val();
  sort_by = $(document).find('select[name="sort_by"]').val();
  $(document).find('.cb-nav-style__block input[name="style"]').each(function() {
    if ($(this).prop('checked')) {
      return styleBlock = $(this).attr('id');
    }
  });
  $data = {
    ajaxCal: 'Y',
    styleBlock: styleBlock,
    countOnPage: countOnPage,
    sort_by: sort_by
  };
  $(document).find('.cb-filter .cb-filter__param input').each(function() {
    $data['set_filter'] = 'Y';
    return $data[$(this).attr('name')] = $(this).val();
  });
  if (urlForSend) {
    return $.ajax({
      url: urlForSend,
      type: 'GET',
      data: $data,
      success: function(data) {
        $(document).find('.preloaderCatalog').animate({
          opacity: 0
        }, 300, function() {
          return $(this).css({
            opacity: 0,
            display: 'none'
          });
        });
        $(document).find('#PROPDS_BLOCK').html(data);
        return $(document).find('.catTopCount .catTopCountValue').html($(document).find('#PROPDS_BLOCK .catTopCountValue').html());
      }
    });
  }
};

$.fn.checkCartSlide = function(numSlide) {
  var $count, $formIsValid, sBlock;
  if (numSlide === 1) {
    return true;
  } else if (numSlide === 2) {
    if ($(document).find('#sci-contact-tab1').prop('checked')) {
      sBlock = $(document).find('#sci-contact-content1');
    } else {
      sBlock = $(document).find('#sci-contact-content2');
    }
    $count = sBlock.find('input').length;
    $formIsValid = true;
    sBlock.find('input').each(function() {
      if (!$(this).val() && $(this).prop('required')) {
        $formIsValid = false;
      }
      if (!--$count) {
        return $formIsValid;
      }
    });
  } else if (numSlide === 3) {
    $formIsValid = true;
    if ($(document).find('#sci-delivery-tab1').prop('checked') || $(document).find('#sci-delivery-tab3').prop('checked') ||
      $(document).find('#sci-delivery-tab4').prop('checked') || $(document).find('#sci-delivery-tab5').prop('checked') ||
      $(document).find('#sci-delivery-tab6').prop('checked')) {
      if (!$(document).find('#so_city_val').val()) {
        $formIsValid = false;
      }
      if (!$(document).find('#sci-delivery-street').val()) {
        $formIsValid = false;
      }
      if (!$(document).find('#sci-delivery-building').val()) {
        $formIsValid = false;
      }
      if (!$(document).find('#sci-delivery-apartment').val()) {
        $formIsValid = false;
      }
    } else {
      if (!$(document).find('#so_city_alt_val').val()) {
        $formIsValid = false;
      }
    }
    return $formIsValid;
  }
  return false;
};

$.fn.updateURLParameter = function(url, param, paramVal) {
  var tmpAnchor;
  var TheParams;
  var TheAnchor, TheParams, additionalURL, baseURL, i, newAdditionalURL, rows_txt, temp, tempArray, tmpAnchor;
  TheAnchor = null;
  newAdditionalURL = '';
  tempArray = url.split('?');
  baseURL = tempArray[0];
  additionalURL = tempArray[1];
  temp = '';
  if (additionalURL) {
    tmpAnchor = additionalURL.split('#');
    TheParams = tmpAnchor[0];
    TheAnchor = tmpAnchor[1];
    if (TheAnchor) {
      additionalURL = TheParams;
    }
    tempArray = additionalURL.split('&');
    i = 0;
    while (i < tempArray.length) {
      if (tempArray[i].split('=')[0] !== param) {
        newAdditionalURL += temp + tempArray[i];
        temp = '&';
      }
      i++;
    }
  } else {
    tmpAnchor = baseURL.split('#');
    TheParams = tmpAnchor[0];
    TheAnchor = tmpAnchor[1];
    if (TheParams) {
      baseURL = TheParams;
    }
  }
  if (TheAnchor) {
    paramVal += '#' + TheAnchor;
  }
  rows_txt = temp + '' + param + '=' + paramVal;
  return baseURL + '?' + newAdditionalURL + rows_txt;
};

