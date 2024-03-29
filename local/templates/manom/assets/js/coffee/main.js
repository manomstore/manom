var app = {};

app.$doc = $(document);
app.$win = $(window);
app.dadataToken = "a6ed9b9ecf7cec4c7bccb4669a71bd7765e08cb0";

app.utils = {
  pluralize: function (number, variants) {
    var cases = [2, 0, 1, 1, 1];

    number = Math.abs(number);

    return variants[(number % 100 > 4 && number % 100 < 20) ? 2 : (number % 10 > 4) ? 2 : cases[number % 10]];
  },
};

app.formSender = {
  call: function (token) {
    var $this,
        form_id,
        name,
        phone;

    $this = $("#popap-call form");
    name = $this.find('input[name="name"]').val();
    phone = $this.find('input[name="phone"]').val();
    form_id = $this.find('input[name="form_id"]').val();
    if (name && phone && form_id) {
      app.loader.show();
      $.ajax({
        url: '/ajax/add_cb.php',
        type: 'POST',
        data: {
          name: name,
          phone: phone,
          form_id: form_id,
          recaptcha: token,
        },
        success: function (data) {
          app.loader.hide();
          $this.find('.form_msg').css('display', 'block');
          $this.find('.form_msg').html('Ваша заявка принята. Наши менеджеры свяжутся с вами, ожидайте.');
          return $this.find('button, label, input').css('display', 'none');
        },
        error: function (error) {
          app.loader.hide();
        },
      });
    } else {
      app.loader.hide();
      $this.find('.form_msg').css('display', 'block');
      $this.find('.form_msg').html('Заполните все поля для отправки.');
    }
  },
  oneClick: function (token) {
    var $this,
        formData,
        formFields,
        $messageField,
        $errorField,
        validFields;

    $this = $(".js-one-click-order");
    $messageField = $this.find('.js-message-field');
    $errorField = $this.find('.js-error-field');
    validFields = true;
    formData = {};
    formFields = $this.serializeArray();

    formFields.forEach(function (field) {
      if (field.value.length <= 0) {
        validFields = false;
        return false;
      }

      formData[field.name] = field.value;
    });

    formData.sessid = BX.bitrix_sessid();
    formData.recaptcha = token;
    formData.type = 'makeOrder';

    if (validFields) {
      app.loader.show();
      $.ajax({
        url: '/ajax/ajax_func.php',
        type: 'POST',
        dataType: 'json',
        data: formData,
        success: function (result) {
          app.loader.hide();
          $errorField.html("").hide();
          if (result.success === true) {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push(
                {
                  'event': 'fb-action-event',
                  'fb-action': 'Purchase',
                },
            );
            window.gtmActions.purchaseHandler(JSON.parse(result.transaction));
            $.fn.refreshMiniCart();

            result.orderId = Number(result.orderId);
            if (result.orderId) {
              $messageField.find(".js-orderId").text("#" + result.orderId);
            }
            $messageField.show();
            $('.sci-login__title').hide();
            return $this.find('button, label, input').hide();
          } else {
            $errorField.html('Произошла ошибка. Повторите попытку позднее.').show();
          }
        },
        error: function (error) {
          app.loader.hide();
        },
      });
    } else {
      app.loader.hide();
      $errorField.html('Заполните все поля для отправки.').show();
    }
  },
};

// ЧЕКАУТ
(function () {
  var classes = {
    hidden: 'sc-hidden',
  };

  var selectors = {
    title: '.js-shopcart-title',
  };

  app.shopcart = {
    _step: 1,
    setStep: function (step) {
      switch (step) {
        case 1: {
          break;
        }

        case 2: {
          break;
        }

        case 3: {
          break;
        }

        case 4: {
          break;
        }

        default:
          new Error('Указан неверный шаг');
          break;
      }

      if ([1, 2, 3, 4].indexOf(step) >= 0) {
        // this.$el - пока инициализируем ниже, потом перетянуть
        this.$el.find(selectors.title).text(SHOPCART_STEP_TITLES[step - 1]);
        this.$el.removeClass('shopcart--step-' + this._step);
        this.$el.addClass('shopcart--step-' + step);
        this._step = step;
      }
    },
  };

  app.deliveryService = {
    deliveryServices: {},
    setAll: function (services = {}) {
      this.deliveryServices = services;
    },
    get: function (code) {
      if (!(this.deliveryServices instanceof Object)
          || !this.deliveryServices.hasOwnProperty(code)) {
        return false;
      }

      return this.deliveryServices[code];
    },
    is: function (deliveryId, deliveryCode) {
      if (!this.get(deliveryCode)) {
        return false;
      }

      return parseInt(deliveryId) === parseInt(this.get(deliveryCode));
    },
    in: function (deliveryId, deliveryCodes = []) {
      var deliveries = [];

      deliveryCodes.forEach(function (deliveries, currentCode) {
        if (this.get(currentCode) !== false) {
          deliveries.push(this.get(currentCode));
        }
      }.bind(this, deliveries));

      return deliveries.indexOf(parseInt(deliveryId)) >= 0
    }
  };

  app.deliveryAddress = {
    addressField: null,
    mute: false,
    checkPreFilledFlag: false,
    getAddressField: function () {
      if (!(this.addressField instanceof jQuery) && $.isReady) {
        this.addressField = $(document).find(".js-delivery-street");
      }

      if (!(this.addressField instanceof jQuery)) {
        this.addressField = $()
      }

      return this.addressField;
    },
    checkPreFilled() {
      if (this.checkPreFilledFlag !== true) {
          return;
      }
      this.checkPreFilledFlag = false;

      if (!$.fn.isMoscow()) {
        return;
      }

      if (!this.getAddressField().length || !this.getAddressField().val().length) {
        return;
      }

      $(document).find('.preloaderCatalog').addClass('preloaderCatalogActive');
      var url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address";
      var token = app.dadataToken;

      var options = {
        method: "POST",
        mode: "cors",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "Authorization": "Token " + token
        },
        body: JSON.stringify({
          query: this.getAddressField().val(),
          locations: [{city: "Москва"}],
          restrict_value: true,
        })
      };

      fetch(url, options)
          .then(response => response.text())
          .then(function (result) {
            result = JSON.parse(result);
            var exist = false;
            this.mute = true;
            $(document).find('.preloaderCatalog').removeClass('preloaderCatalogActive');

            result.suggestions.forEach(function (suggestion) {
              if (this.getAddressField().val().length
                  && this.getAddressField().val().trim() === suggestion.value) {
                exist = true;
                this.process(suggestion.data);
              }
            }.bind(this));

            if (!exist) {
              this.setError(true);
            }
            this.mute = false;

          }.bind(this))
          .catch(function (error) {
            $(document).find('.preloaderCatalog').removeClass('preloaderCatalogActive');
          });
      this.mute = false;
    },
    setCheckPreFilledFlag: function () {
        this.checkPreFilledFlag = true;
    },
    process: function (locationData) {
      var error;
      if (!locationData.street) {
        error = new Error("Введите улицу и номер дома");
      }

      if (locationData.street && !locationData.house) {
        error = new Error("Введите дом и номер квартиры");
      }

      if (locationData.house && !locationData.flat) {
        error = new Error("Введите номер квартиры (“кв 1”, если в доме нет квартир)");
      }

      if (error instanceof Error) {
        this.setError(true, error.message);
        return false;
      } else {
        this.setError(false);
      }

      this.getAddressField().data("inside-beltway", locationData.beltway_hit === "IN_MKAD" ? "Y" : "N");

      var curDeliveryTab = this.getAddressField().closest('.sci-delivery-tab').find(".sci-delivery__tab");
      var curDeliveryRadio = this.getAddressField().closest('.sci-delivery-tab').find(".sci-delivery__radio.visually-hidden");
      if (curDeliveryTab.length && curDeliveryRadio.prop("checked")) {
        setDeliveryByLocation(curDeliveryTab, true);
      }

      return true;
    },
    setError: function (isError, errorText = "") {
      /* Только для Москвы */
      if (!$.fn.isMoscow()) {
        return;
      }

      if (isError) {
        this.getAddressField().addClass("invalid-address");
        this.getAddressField().addClass('is-error');
        this.getAddressField().removeClass('is-success');
        if (!this.mute) {
          $.fn.setPushUp("Некорректный адрес", errorText, false, "message", false, 5000);
        }
      } else {
        this.getAddressField().addClass('is-success');
        this.getAddressField().removeClass('is-error');
        this.getAddressField().removeClass("invalid-address");
      }
    }
  };

  app.loader = {
    show: function () {
      $(document).find('.preloaderCatalog').addClass('preloaderCatalogActive');
    },
    hide: function () {
      $(document).find('.preloaderCatalog').removeClass('preloaderCatalogActive');
    },
  };

  app.weekTools = {
    days: [
      'вс',
      'пн',
      'вт',
      'ср',
      'чт',
      'пт',
      'сб',
    ],
    currentDay: new Date().getDay(),
    currentHour: new Date().getHours(),
    calcDiffDay: function (startDay, endDay) {
      var sumDays = 0;
      var roundNum = 0;
      if (endDay > this.days.length) {
        return sumDays;
      }
      var i = startDay;
      while (i < this.days.length) {
        if (roundNum <= 0) {
          roundNum = 1;
        }
        if (i > startDay || roundNum > 1) {
          sumDays++;
        }

        if (i === endDay) {
          break;
        }
        if (i === (this.days.length - 1)) {
          i = 0;
          roundNum++;
        } else {
          i++;
        }

      }
      return sumDays;
    },
    getTextPeriod: function (deliveryObj) {
      var textNearestDate = '';
      var existRestDay = !(
          this.days.indexOf("пн") === deliveryObj.dates.start
          && this.days.indexOf("вс") === deliveryObj.dates.end
      );

      //fix for sunday
      var start = deliveryObj.dates.start === 0 ? 7 : deliveryObj.dates.start;
      var end = deliveryObj.dates.end === 0 ? 7 : deliveryObj.dates.end;
      var now = this.currentDay === 0 ? 7 : this.currentDay;
      //

      if (deliveryObj.isSdek) {
        var newCurPeriod = '';
        for (i = 0; i < deliveryObj.currentPeriod.length; i++) {
          if (!isNaN(parseInt(deliveryObj.currentPeriod[i])) || deliveryObj.currentPeriod[i] === '-') {
            newCurPeriod += deliveryObj.currentPeriod[i];
          }
        }
        deliveryObj.currentPeriod = newCurPeriod;

        var period = deliveryObj.currentPeriod.split('-');
        if (period.length <= 0) {
          return '';
        }
        var periodStart = period.shift(),
            periodEnd = period.shift(),
            cdekOffset = 0;

        if (!(now >= start && now <= end) && existRestDay) {
          cdekOffset = app.weekTools.calcDiffDay(this.currentDay, deliveryObj.dates.start);
        }

        cdekOffset += $.fn.getOrderAssemblyTimeObj(true).days;

        if (periodStart) {
          periodStart = cdekOffset + parseInt(periodStart);
          textNearestDate = String(periodStart);
        }
        if (periodEnd) {
          periodEnd = cdekOffset + parseInt(periodEnd);
          textNearestDate += '-' + String(periodEnd);
        }
        if (textNearestDate.length > 0) {
          textNearestDate += ' ' + app.utils.pluralize(parseInt(periodEnd), ['день', 'дня', 'дней']);
        }
        return textNearestDate;
      }

      var workingHour = true;
      var dayOffset = 0;

      if (deliveryObj.exist) {
        workingHour = this.currentHour < deliveryObj.time.end - 1;

        var lastWorkDay = this.currentDay === deliveryObj.dates.end;

        if (!(now >= start && now <= end) && existRestDay) {
          if (workingHour || !lastWorkDay) {
            dayOffset = app.weekTools.calcDiffDay(this.currentDay, deliveryObj.dates.start);
          }
        }
      } else {
        workingHour = this.currentHour < deliveryObj.time.end;
      }

      dayOffset += $.fn.getOrderAssemblyTimeObj().days;

      if (dayOffset === 0 && !workingHour) {
        dayOffset++;
      }

      switch (dayOffset) {
        case 0:
          if (deliveryObj.exist) {
            textNearestDate = 'Сегодня до ' + String(deliveryObj.time.end) + ':00';
          } else {
            textNearestDate = 'Сегодня';
          }
          break;
        case 1:
          textNearestDate = 'Завтра';
          break;
        case 2:
          textNearestDate = 'Послезавтра';
          break;
        default:
          textNearestDate = 'Через ' + dayOffset + ' ' + app.utils.pluralize(dayOffset, ['день', 'дня', 'дней']);
          break;
      }

      return textNearestDate;
    },
    parseScheduleShop: function (schedule) {
      var days,
          time,
          result = {}
      ;
      schedule = schedule.toString().split(' ');
      schedule = Array.isArray(schedule) ? schedule : [];
      time = schedule.pop();
      time = time.split('-');
      result.hourStart = parseInt(time.shift());
      result.hourEnd = parseInt(time.shift());
      days = schedule.pop();
      days = days.split('-');
      result.dayStart = app.weekTools.days.indexOf(days.shift());
      result.dayEnd = app.weekTools.days.indexOf(days.shift());

      return result;
    },
  };
})();

function showDeliveryPickUpContent(deliveryButton) {
  var deliveryId = parseInt(deliveryButton.data('prop').replace('ID_DELIVERY_ID_', ''));

  var pickupType;

  switch (deliveryId) {
    case app.deliveryService.get("cdekPickup"):
      pickupType = 'pvz';
      break;
    case app.deliveryService.get("ownPickup"):
      pickupType = 'shop';
      break;
    default:
      return;
  }
  $('.delivery-pickup-type[data-pickup-type=\'' + pickupType + '\']').addClass('current-type');
  $('.delivery-pickup-type').not('[data-pickup-type=\'' + pickupType + '\']').removeClass('current-type');
}

function setDeliveryByLocation($deliveryTab, refresh = false) {
  refresh = Boolean(refresh);
  var cityId = parseInt($(document).find('#so_main_block').find('#so_city').val());
  var deliveryId = parseInt($deliveryTab.data('prop').replace('ID_DELIVERY_ID_', ''));
  var newDeliveryId;
  newDeliveryId = getDeliveryByCity(deliveryId, cityId);
  refresh = refresh && newDeliveryId !== deliveryId ? refresh : false;

  $deliveryTab.data('prop', 'ID_DELIVERY_ID_' + newDeliveryId).attr('data-prop', 'ID_DELIVERY_ID_' + newDeliveryId);

  return refresh ? $deliveryTab.click() : null;
}

function getDeliveryByCity(deliveryId, cityId) {
  deliveryId = parseInt(deliveryId);
  cityId = parseInt(cityId);
  var newDeliveryId,
      moscowDelivery;
  var $streetField = $(document).find('.js-delivery-street');

  // Для Москвы, если проверяли вхожджение адреса во МКАД -
  // изменяем доставку на нужную, иначе - оставляем ту, которая была
  if ("insideBeltway" in ($streetField.data() || {})) {
    switch ($streetField.data("inside-beltway")) {
      case "Y":
      default:
        moscowDelivery = app.deliveryService.get("ownDelivery");
        break;
      case "N":
        moscowDelivery = app.deliveryService.get("ownDeliveryRegion");
        break;
    }
  } else {
    moscowDelivery = app.deliveryService.in(deliveryId, ["ownDelivery", "ownDeliveryRegion"])
        ? deliveryId : app.deliveryService.get("ownDelivery");
  }

  switch (deliveryId) {
    case app.deliveryService.get("cdekDelivery"):
    case app.deliveryService.get("ownDelivery"):
    case app.deliveryService.get("ownDeliveryRegion"):
      newDeliveryId = $.fn.isMoscow(cityId) ? moscowDelivery
          : app.deliveryService.get("cdekDelivery");
      break;
    case app.deliveryService.get("cdekPickup"):
    case app.deliveryService.get("ownPickup"):
      newDeliveryId = $.fn.isMoscow(cityId) ? app.deliveryService.get("ownPickup")
          : app.deliveryService.get("cdekPickup");
      break;
  }
  return newDeliveryId;
}

function setDeliveryDescription($delivery) {
  var deliveryId,
    shopSchedule;
  var deliveryPeriod;

  shopSchedule = app.weekTools.parseScheduleShop($delivery.find(".js-shop-schedule").text());

  var shop = {
    exist: false,
    time: {
      start: 0,
      end: 0,
    },
    dates: {
      start: 0,
      end: 0,
    },
  };

  deliveryId = parseInt($delivery.attr('for').replace('ID_DELIVERY_ID_', ''));
  shop.exist = $delivery.hasClass('is-shop');
  if (shop.exist && app.deliveryService.is(deliveryId, "ownPickup")) {
    shop.time.start = shopSchedule.hourStart;
    shop.time.end = shopSchedule.hourEnd;
    shop.dates.start = shopSchedule.dayStart;
    shop.dates.end = shopSchedule.dayEnd;
  }

  if (shop.exist && app.deliveryService.is(deliveryId, "ownPickup")) {
    deliveryPeriod = app.weekTools.getTextPeriod(shop);
  } else {
    if (app.deliveryService.in(deliveryId, ["ownDelivery", "ownDeliveryRegion"])) {
      var $deliveryTimes = $(document).find('#sci-delivery-time option'),
        courier = {
          time: {
            start: $deliveryTimes.first().data('start'),
            end: $deliveryTimes.last().data('start'),
          },
          dates: {
            start: !isNaN(shopSchedule.dayStart) ? shopSchedule.dayStart : 1,
            end: !isNaN(shopSchedule.dayStart) ? shopSchedule.dayStart : 5,
          },
        };
      deliveryPeriod = app.weekTools.getTextPeriod(courier);
    } else {
      if (app.deliveryService.in(deliveryId, ["cdekDelivery", "cdekPickup"])) {
        var sdek = {
          isSdek: true,
          currentPeriod: $delivery.find('.so_delivery_period').html(),
          time: {
            start: 0,
            end: 0,
          },
          dates: {
            start: !isNaN(shopSchedule.dayStart) ? shopSchedule.dayStart : 1,
            end: !isNaN(shopSchedule.dayEnd) ? shopSchedule.dayEnd : 5,
          },
        };
        deliveryPeriod = app.weekTools.getTextPeriod(sdek);
      } else {
        deliveryPeriod = $delivery.find('.so_delivery_period').html();
      }
    }
  }

  var deliveryPrice = $delivery.find('.prs_soa').html();

  if (parseInt(deliveryPrice.replace(/\D+/g, '')) <= 0) {
    deliveryPrice = 'Бесплатно';
  }
  return (deliveryPeriod ? deliveryPeriod + ', ' : '') + deliveryPrice;
}

$(document).ready(function () {
  $('.js-search-field .popup-block__input-clear').on('click', function () {
    var $input = $(this).parent().find('.popup-block__input--search');

    $input.val('');
    $input.closest('.popup-block__field').removeClass('popup-block__field--not-empty');
  });

  $('.js-search-field .popup-block__input--search').on('keydown paste input', function (e) {
    var $input = $(this);

    $input.closest('.popup-block__field').toggleClass('popup-block__field--not-empty', $input.val() !== '');

    if (e.keyCode === 13 && $input.val().trim().length > 0) {
      $(this).closest('form').submit();
    }
  });

  $('.js-search-field .search-block__submit-button').on('click', function () {
    var $input = $(this).parent().find('.popup-block__input--search');

    if ($input.val().trim().length <= 0) {
      return false;
    }

    $(this).closest('form').submit();
  });

  $('.js-shopcart-datepicker').on('change', function () {
    checkDeliveryTime();
  });

  $('.popup-login__toggle[for=\'sing-up\']').on('click', function (event) {
    event.preventDefault();
    window.location.href = '/auth/registration.php';
  });

  var $clearAll,
    $maxPrice,
    $minPrice,
    $minPriceValue,
    $maxPriceValue;

  var REG_EXP_EMAIL = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;

  app.shopcart.$el = $('.shopcart');

  initCartRecommendationsSlider();
  $(document).bind("ajaxSuccess", function(){
    initCartRecommendationsSlider();
  });

  function initCartRecommendationsSlider() {
    $('.sci-add__products').each(function () {
      var slider = $(this);
      try {
        slider.slick('unslick');
      } catch (error) {
      }
    });

    if (shouldShowSlider()) {
      $('.sci-add__products').each(function () {
        if ($('.sci-add__products').length === 0) {
          return;
        }
        var $slider = $(this),
          slidesWidth = 0;

        $slider.slick({
          dots: false,
          infinite: true,
          speed: 300,
          slidesToShow: 5,
          responsive: [
            {
              breakpoint: 678,
              settings: {
                slidesToShow: 4,
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 3,
              }
            },
            {
              breakpoint: 374,
              settings: {
                slidesToShow: 2,
              }
            }
          ]
        });

        $slider.find('.slick-slide').each(function () {
          var $slide = $(this);
          slidesWidth += $slide.outerWidth() + parseInt($slide.css('margin-right'));
        });

        if (slidesWidth - parseInt($slider.find('.slick-slide').css('margin-right')) <= $slider.width()) {
          $slider.find('.slick-arrow').hide();
        }
        var btnPrev = $(".sci-add .slick-prev");
        var btnNext = $(".sci-add .slick-next");
        btnPrev.addClass('show');
        btnNext.addClass('show');
      });
    }
  }

  function shouldShowSlider() {
    var length = $(".sci-add__prod").length;
    if (window.isMobileSwiper()) {
      return length > 3;
    }
    if (window.isMobile()) {
      return length > 4;
    }
    return length > 5
  }

  $('.top-nav__button-show').click(function (evt) {
    evt.preventDefault();
    $('.top-nav__button-show').toggleClass('top-nav__button-show--active');
    $('.top-nav__request').toggleClass('top-nav__request--show');
  });

  var lastTimeout = null;

  var debounce = function (callback, time) {
    if (lastTimeout) {
      clearTimeout(lastTimeout);
    }
    lastTimeout = setTimeout(callback, time);
  };

  // $('.sub-menu__list').slick({
  //   dots: false,
  //   infinite: false,
  //   prevArrow: false,
  //   nextArrow: false,
  //   variableWidth: true
  // });

  $('.search-block__list').slick({
    dots: false,
    infinite: false,
    speed: 300,
    prevArrow: false,
    nextArrow: false,
    variableWidth: true,
  });

  var userLink = document.querySelector('.js-user-block');
  var userBlock = document.querySelector('.personal-preview--user');
  if (userLink) {
    userLink.addEventListener('click', function (evt) {
      evt.preventDefault();

      userBlock.classList.toggle('personal-preview--show');
    });
  }

  var header = $('.header');
  var headerWrapper = $('.header__wrapper');
  $(window).scroll(function () {
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

  $(document).on('click', '#cart_btn_open_login', function () {
    return $(document).find('.top-sign .top-sign__on').click();
  });

  $(document).find('form[is-search-form] input[type="text"]').prop('required', true);

  $(document).on('click', '.shopcart-nav1 label', function () {
    if (!$(document).find('#' + $(this).attr('for')).hasClass('slide-disable')) {
      console.log($(this).position().left);
      return $(document).find('.layout_cart_menu').animate({
        width: $(this).position().left + 'px',
      }, 500);
    }
  });

  $(document).on('click', '.js-shopcart-next', function (e) {
    var inputCount = $(document).find('.shopcart-nav1 input').length,
      slideNum = 1;

    $(document).find('.shopcart-nav1 input[type="radio"]').each(function () {
      var $count,
          requiredError,
          $formIsValid,
          sBlock,
          isEmailValid,
          addressEmpty,
          addressInvalid = false;

      if ($(this).prop('checked')) {
        slideNum = $(this).attr('data-num');
      }
      requiredError = 0;
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
          window.gtmActions.setCheckoutStep('contacts');
        } else {
          if (parseInt(slideNum) === 2) {
            if ($(document).find('#sci-contact-tab1').prop('checked')) {
              sBlock = $(document).find('#sci-contact-content1');
            } else {
              sBlock = $(document).find('#sci-contact-content2');
            }
            $count = sBlock.find('input').length;
            $formIsValid = true;
            var phoneIsValid = false;
            sBlock.find('input').each(function () {
              var $input = $(this);
              var inputValue = $input.val();

              if (!inputValue && $input.prop('required')) {
                $formIsValid = false;
                requiredError++;
                $input.addClass('is-error');
              } else {
                $input.removeClass('is-error');

                if ($input.attr('name') === 'sci-contact__email') {
                  isEmailValid = true;
                }
              }

              if (
                $input.attr('name') === 'sci-contact__email'
                && inputValue !== ''
                && inputValue.match(REG_EXP_EMAIL) === null
              ) {
                $input.addClass('is-error');
                $formIsValid = false;
                isEmailValid = false;
              }

              if (['sci-contact__tel', 'sci-contact__ur-phone'].indexOf($(this).attr('name')) >= 0) {
                if (!numberPhoneValidation($(this).val().replace(/[- )(]/g, '').replace('+7', ''))) {
                  $(this).addClass('is-error');
                } else {
                  phoneIsValid = true;
                  $(this).removeClass('is-error');
                }
              }

              if (!--$count) {
                  if (requiredError > 1) {
                      $(document).find('.push_up_item').addClass('push_up_item--error');
                      return $.fn.setPushUp('Не заполнены поля', 'Поля, обязательные к заполнению, не были заполнены', false, 'message', false, 5000, undefined, 'push_up_item--error');
                  }

                if (!phoneIsValid) {
                  $(document).find('.push_up_item').addClass('push_up_item--error');
                  return $.fn.setPushUp('Ошибка', 'Введён некорректный номер телефона', false, 'message', false, 5000, undefined, 'push_up_item--error');
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
                    return $(document).find('#btnNextSlide').addClass('hidden');
                  }
                } else {
                  var $emailField = sBlock.find('#sci-contact__email');

                  if ($emailField.length >= 1) {

                    if (!$emailField.val() && $emailField.prop('required')) {
                      isEmailValid = false;
                    } else {
                      isEmailValid = true;
                    }

                    if ($emailField.val() !== '' && $emailField.val().match(REG_EXP_EMAIL) === null) {
                      isEmailValid = false;
                    }
                  } else {
                    isEmailValid = true;
                  }

                  if (!isEmailValid) {
                    return $.fn.setPushUp('Ошибка валидации E-mail', 'Неверно заполнено поле E-mail', false, 'message',
                      false, 5000, undefined, 'push_up_item--error');
                  } else {
                    return $.fn.setPushUp('Не заполнены поля', 'Поля, обязательные к заполнению, не были заполнены', false,
                      'message', false, 5000, undefined, 'push_up_item--warning');
                  }
                }
              }
            });
            window.gtmActions.setCheckoutStep('delivery');
          } else {
            if (parseInt(slideNum) === 3) {
              $formIsValid = true;

              if (
                $(document).find('#sci-delivery-tab1').prop('checked')
                // || $(document).find('#sci-delivery-tab3').prop('checked')
                // || $(document).find('#sci-delivery-tab4').prop('checked')
                || $(document).find('#sci-delivery-tab5').prop('checked')
                || $(document).find('#sci-delivery-tab6').prop('checked')
              ) {
                if (!$(document).find('#so_city_val').val()) {
                  $formIsValid = false;
                }

                var $addressField = $(document).find('#sci-delivery-street');
                if (!$addressField.val()) {
                  $formIsValid = false;
                  addressEmpty = true;
                }

                if ($addressField.val() && $addressField.hasClass("invalid-address")) {
                  $formIsValid = false;
                  addressInvalid = true;
                }
                // if (!$(document).find('#sci-delivery-building').val()) {
                //   $formIsValid = false;
                // }
                // if (!$(document).find('#sci-delivery-apartment').val()) {
                //   $formIsValid = false;
                // }
              } else {
                if (
                  !$(document).find('#so_city_alt_val').val()
                  && !$(document).find('#sci-delivery-tab4').prop('checked')
                ) {
                  $formIsValid = false;
                }
              }

              if ($('#ID_DELIVERY_ID_6').prop('checked')) {
                var $pickupAddress = $('.pickup_address span');

                $pickupAddress.removeClass('is-error');

                if (
                  typeof window.IPOLSDEK_pvz !== 'undefined'
                  && !window.IPOLSDEK_pvz.pvzId
                ) {
                  $formIsValid = false;
                  $pickupAddress.addClass('is-error');
                }
              }

              var isDeliveryChecked = true;

              if ($('.sci-delivery__radio:checked').length === 0) {
                $formIsValid = false;
                isDeliveryChecked = false;
              }

              if ($(document).find('#sci-contact-tab1').prop('checked')) {
                sBlock = $(document).find('#sci-contact-content1');
              } else {
                sBlock = $(document).find('#sci-contact-content2');
              }

              var $emailField = sBlock.find('#sci-contact__email');

              if ($emailField.length >= 1) {

                if (!$emailField.val() && $emailField.prop('required')) {
                  isEmailValid = false;
                } else {
                  isEmailValid = true;
                }

                if ($emailField.val() !== '' && $emailField.val().match(REG_EXP_EMAIL) === null) {
                  isEmailValid = false;
                }
              } else {
                isEmailValid = true;
              }

              $(document).find('#shopcart-item3').find('.sci-delivery__radio:checked').parent().find(
                '.sci-delivery-content').find('input').each(function () {
                  if (!$(this).val() && $(this).prop('required')) {
                    $(this).addClass('is-error');
                  } else if (!$(this).hasClass("invalid-address")) {
                    $(this).removeClass('is-error');
                  }
                });

              if ($formIsValid) {
                if (!isValidDeliveryTime() && $('#ID_DELIVERY_ID_8').prop('checked')) {
                  return $.fn.setPushUp('Ошибка', 'Дата или время доставки указаны некорректно', false, 'message',
                      false, 5000, undefined, 'push_up_item--error');
                }

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
                if (!isDeliveryChecked) {
                  $.fn.setPushUp('Не выбрана доставка', 'Необходимо выбрать доставку из списка', false, 'message',
                    false, 5000, undefined, 'push_up_item--warning');
                } else {
                  if (!isEmailValid) {
                    $.fn.setPushUp('Ошибка валидации E-mail', 'Неверно заполнено поле E-mail', false, 'message', false,
                      5000, undefined, 'push_up_item--error');
                  } else {
                    if (addressEmpty) {
                      $.fn.setPushUp('Не указан адрес', 'Укажите, пожалуйста, адрес доставки', false,
                          'message', false, 5000, undefined, 'push_up_item--warning');
                    } else if (addressInvalid) {
                      $.fn.setPushUp('Некорректный адрес', 'Адрес доставки указан некорректно', false,
                          'message', false, 5000, undefined, 'push_up_item--warning');
                    } else {
                      $.fn.setPushUp('Не заполнены поля', 'Поля, обязательные к заполнению не были заполнены', false,
                          'message', false, 5000, undefined, 'push_up_item--warning');
                    }
                  }
                }
              }
              window.gtmActions.setCheckoutStep('payment');
            }
          }
        }
      }

      if (parseInt(slideNum) === 2) {
        if (!$(document).find('.shopcart-nav1 input#shopcart-tab' + (
          parseInt(slideNum) + 1
        ) + '').hasClass('slide-disable')) {
          return $(document).find('.shopcart-sidebar__delivery').removeClass('dsb-hidden');
        }
      }
    });

    e.preventDefault();
  });

  $(document).on('click', '.js-shopcart-back', function (e) {
    $(document).find('.shopcart-nav1 label[for="shopcart-tab' + (app.shopcart._step - 1) + '"]').click();
    e.preventDefault();
  });

  $(document).on('click', '.shopcart-nav1 label', function () {
    var slideNum;
    slideNum = $(document).find('.shopcart-nav1 input[id="' + $(this).attr('for') + '"]').attr('data-num');

    if (parseInt(slideNum) === 1) {
      $('.js-shopcart-amount').show();
    } else {
      $('.js-shopcart-amount').hide();
    }

    if (parseInt(slideNum) === 4) {
      $(document).find('#btnSubmitOrder').removeClass('hidden');
      return $(document).find('#btnNextSlide').addClass('hidden');
    } else {
      $(document).find('#btnSubmitOrder').addClass('hidden');
      return $(document).find('#btnNextSlide').removeClass('hidden');
    }
  });

  $(document).on('change', '.shopcart-nav1 input', function () {
    var $stepRadio = $(this),
      step = $stepRadio.data('num'),
      scrollTopPosition = $('.shopcart__wrapper').offset().top;
    $fullTotalPrice = $('#sale-order-full-total-price');

    if ($stepRadio.prop('checked')) {
      app.shopcart.setStep(step);
    }

    if (
      (step === 3 || step === 4)
      && $('.sci-delivery-tab:not(.rb_so__hide)').find('.sci-delivery__radio:checked').length > 0
    ) {
      if ($fullTotalPrice.length > 0) {
        $('#total_price_cart').html($fullTotalPrice.html().replace('руб.', ''));
      }
    }

    $('html, body').stop().animate({ scrollTop: scrollTopPosition }, 600);
  });

  $(document).on('submit', '.js-one-click-order,#popap-call form', function (e) {
    e.preventDefault();
    app.loader.show();
    var that = this;

    grecaptcha.execute($(that).data("g-recaptcha-id"))
        .then(function () {
          var token = grecaptcha.getResponse($(that).data("g-recaptcha-id"));
          if (!token) {
            return;
          }

          if ($(that).is("#popap-call form")) {
            app.formSender.call(token);
          } else if ($(that).is(".js-one-click-order")) {
            app.formSender.oneClick(token);
          }
        })
        .catch(function () {
          app.loader.hide()
        });
  });

  $(document).on('click', '.js-one-click-order .js-close-popup', function (e) {
    e.preventDefault();
    $.fancybox.close()
  });

  $(document).on('submit', '#popap-buy-one-click-cart form', function () {
    var $this,
      email,
      form_id,
      name,
      phone;
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
          email: email,
        },
        success: function (data) {
          $this.find('.form_msg').css('display', 'block');
          $this.find('.form_msg').html('Ваша заявка принята. Наши менеджеры свяжутся с вами в течении 15 минут.');
          $this.find('button, label, input').css('display', 'none');
          return setTimeout(function () {
            return $.fn.refreshCart();
          }, 3000);
        },
      });
    } else {
      $this.find('.form_msg').css('display', 'block');
      $this.find('.form_msg').html('Заполните все поля для отправки.');
    }
    return false;
  });

  $(document).on("change", "#price-start-alt", function () {
    var $priceEnd = $(document).find("#price-end-alt");

    $minPrice = parseInt($priceEnd.attr('min'));
    $maxPrice = parseInt($priceEnd.attr('max'));
    $maxPriceValue = parseInt($priceEnd.val());

    var inputStart;

    inputStart = $(this).val();
    var max = $maxPriceValue;

    if (!max) {
      max = $maxPrice;
    }

    if (inputStart > max) {
      inputStart = max;
    }
    if (inputStart < $minPrice) {
      inputStart = "";
    }
    $(this).val(inputStart);
  });

  $(document).on("change", "#price-end-alt", function () {
    var $priceStart = $(document).find("#price-start-alt");

    $minPrice = parseInt($priceStart.attr('min'));
    $minPriceValue = parseInt($priceStart.val());
    $maxPrice = parseInt($priceStart.attr('max'));

    var inputEnd;

    var min = $minPriceValue;

    if (!min) {
      min = $minPrice;
    }

    inputEnd = $(this).val();

    if (inputEnd < min) {
      inputEnd = min;
    }

    if (inputEnd > $maxPrice) {
      inputEnd = "";
    }

    $(this).val(inputEnd);
  });

  $(document).on('click', '.offer_prop_item', function () {
    var itemID,
      propCode,
      propID,
      title;
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
        if (propCode === 'HDD' && $(document).find('.offers_prop[data-code="graphics_mac"]').length > 0) {
          $(document).find('.offers_prop .offer_prop_item').not($(this)).each(function (index, elem) {
            $(elem).removeClass('active');
            $(elem).removeClass('propDisabled');
          });
        }
      }
      return $.fn.checkPropParams();
    }
  });

  $(document).on('change', 'input.catalog-filter__checkbox, input.catalog-filter__price', function () {
    var dataMaxName,
      dataMaxValue,
      dataMinName,
      dataMinValue,
      dataName,
      dataPropTitle,
      dataValue,
      dataValueTitle,
      elementFilter;
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
        $(document).find('.cb-filter').prepend($(elementFilter));
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
      elementFilter = '<div class="cb-filter__param cb-filter__param-hidden" data-id="' + dataMinName + dataMaxName + '">';
      elementFilter += dataPropTitle + 'от: ' + dataMinValue + ' до: ' + dataMaxValue;
      elementFilter += '<input type="hidden" name="' + dataMinName + '" value="' + dataMinValue + '">';
      elementFilter += '<input type="hidden" name="' + dataMaxName + '" value="' + dataMaxValue + '">';
      elementFilter += '<span>×</span>';
      elementFilter += '</div>';
      $(document).find('.cb-filter').prepend($(elementFilter));
    }
    if ($('.catalog-filter__list-item input').prop('checked')) {
      $(this).closest('.catalog-filter__list-item').addClass('top')
    }
    return $.fn.ajaxLoadCatalog();

  });

  $clearAll = false;

  $(document).on('click', '.cb-filter__param>span', function () {
    var $el;
    $el = $(this).parent('div');
    $(document).find('.catalog-filter input[name="' + $el.attr('data-id') + '"]').prop('checked', false);
    $el.remove();
    if ($clearAll === false) {
      return $.fn.ajaxLoadCatalog();
    }
  });

  $(document).on('click', '.cb-filter__clear', function () {
    $clearAll = true;
    $(document).find('.cb-filter__param>span').click();
    $clearAll = false;
    return $.fn.ajaxLoadCatalog();
  });

  $(document).on('click', '.ajaxPageNav .cb-nav-pagination__item a', function (e) {
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

  $(document).on('mouseenter', '.product-card:not(.no-hover)', function () {
    $(this).children('.product-card__img').slick({
      arrows: true,
      dots: false,
      infinite: true,
      speed: 1000,
    });
    return $(this).children('.p-nav-top').fadeIn(200);
  });

  $(document).on('mouseenter', '.product-card.no-hover', function () {
    return $(this).children('.p-nav-top').fadeIn(200);
  });

  $(document).on('mouseleave', '.product-card:not(.no-hover)', function () {
    $(this).children('.product-card__img').slick('unslick');
    $(this).children('.p-nav-top').fadeOut(200);
  });

  $(document).on('mouseleave', '.product-card.no-hover', function () {
    $(this).children('.p-nav-top').fadeOut(200);
  });

  $(document).on('change', 'select[name="countOnPage"], select[name="sort_by"]', function () {
    return $.fn.ajaxLoadCatalog();
  });

  $(document).on('click', '.addToCartBtn', function () {
    var $this,
      productID;
    if (!$(this).hasClass('addToCartBtn_dis')) {
      $this = $(this);
      $(this).addClass('addToCartBtn_dis');
      productID = $(this).attr('data-id');
      if (productID) {
        window.gtmActions.addToCartHandler(productID, 'cart');
        return $.ajax({
          url: '/ajax/add_to_cart.php',
          type: 'POST',
          data: {
            PRODUCT_ID: productID,
            METHOD_CART: 'add',
            AJAX_MIN_CART: 'Y',
          },
          success: function (data) {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push(
              {
                'event': 'fb-action-event',
                'fb-action': 'AddToCart',
              },
            );
            $this.removeClass('addToCartBtn_dis');
            var previewCart = $('.preview-shopcart');
            var showCartClass = 'preview-card--show';

            previewCart.addClass(showCartClass);
            $('#mini_compare_header').removeClass('preview-heart--show');
            $('#mini_favorite_header').removeClass('preview-heart--show');

            var showMiniCart = function () {
              if (previewCart.hasClass(showCartClass)) {
                previewCart.removeClass(showCartClass);
              }
            };
            debounce(showMiniCart, 3500);
            if ($this.hasClass('product-sidebar__button')) {
              $this.addClass('dsb-hidden');
              $this.after(
                '<a class="product-sidebar__button goToFcnCart" href="/cart/" data-id="' + productID + '">В корзину</a>');
            }
            $.fn.updateMiniCart(data);
            if ($this.hasClass('addToCartBtn_inCart')) {
              return $.fn.refreshCart();
            }
          },
        });
      }
    }
  });

  $(document).on('click', function (event) {
    var showMiniCartClass = 'preview-card--show';
    var showMiniPreviewClass = 'preview-heart--show';

    var $miniCart = $('.preview-shopcart');
    var $miniCompare = $('#mini_compare_header');
    var $miniFavorite = $('#mini_favorite_header');

    // Закрываем выпадающую корзину по клику за пределами
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

    // Закрываем выпадающее сравнение по клику за пределами
    if (
      !$(event.target).closest('.addToCompareList').length > 0
      && !$(event.target).closest('#mini_compare_header').length > 0
      && $miniCompare.hasClass(showMiniPreviewClass)
    ) {
      if (lastTimeout) {
        clearTimeout(lastTimeout);
      }

      $miniCompare.removeClass(showMiniPreviewClass);
    }

    // Закрываем выпадающее избранное по клику за пределами
    if (
      !$(event.target).closest('.addToFavoriteList').length > 0
      && !$(event.target).closest('#mini_favorite_header').length > 0
      && $miniFavorite.hasClass(showMiniPreviewClass)
    ) {
      if (lastTimeout) {
        clearTimeout(lastTimeout);
      }

      $miniFavorite.removeClass(showMiniPreviewClass);
    }
  });

  $(document).on('click', '.preview-prod-bottom__button-cart', function () {
    var $cartItemID;
    $cartItemID = $(this).attr('data-cart-item');
    var productId = $(this).data('product-id');
    if ($cartItemID) {
      $(document).find('#mini_cart_header .preview-prod[data-cart-item="' + $cartItemID + '"]').remove();
      return $.ajax({
        url: '/ajax/add_to_cart.php',
        type: 'POST',
        data: {
          PRODUCT_ID: $cartItemID,
          METHOD_CART: 'delete',
          AJAX_MIN_CART: 'Y',
        },
        success: function (data) {
          window.gtmActions.removeFromCartHandler(productId);
          return $.fn.updateMiniCart(data);
        },
      });
    }
  });

  $(document).on('click', '.js-clear-cart', function () {
    var productIds = $(document).find('#mini_cart_header .preview-prod-bottom__button-cart').map(function () {
      return Number($(this).data('product-id'));
    }).get();

    return $.ajax({
      url: '/ajax/add_to_cart.php',
      type: 'POST',
      data: {
        clear_all: 'Y',
        METHOD_CART: 'delete',
        PRODUCT_ID: 1,
        AJAX_MIN_CART: 'Y',
      },
      success: function (data) {
        window.gtmActions.removeFromCartHandler(productIds);
        return $.fn.updateMiniCart(data);
      },
    });
  });

  $(document).on('click', '.preview-prod-bottom__button-favorite', function () {
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
          AJAX_MIN_FAVORITE: 'Y',
        },
        success: function (data) {
          window.gtmActions.changeWishListHandler($cartItemID, data);
          $('.addToFavoriteList[data-id="' + $cartItemID + '"]').addClass('notActive').prev('input:checkbox').attr(
            'checked', false).prop('checked', false);

          $.fn.updateMiniFavorite(data);
          if ($(document).find('.addToFavoriteListOnFP').is('div')) {
            $.fn.ajaxLoadCatalog();
          }
          if ($(document).find('.addToFavoriteListOnFP_NOT_ITEM').is('div')) {
            return location.href = '/user/favorite/';
          }
        },
      });
    }
  });

  $(document).on('click', '.js-clear-favorite', function () {
    var favoriteList = $(this).closest('#mini_favorite_header').find('.preview-prod').map(function () {
      return Number($(this).data('cart-item'));
    }).get();

    return $.ajax({
      type: 'POST',
      url: '/ajax/ajax_func.php',
      data: {
        change_favorite_list: 'Y',
        clear_all: 'Y',
        AJAX_MIN_FAVORITE: 'Y',
      },
      success: function (data) {
        window.gtmActions.changeWishListHandler(favoriteList, data, true);
        $.fn.updateMiniFavorite(data);
      },
    });
  });

  $(document).on('click', '.preview-prod-bottom__button-compare', function () {
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
          AJAX_MIN_COMPARE: 'Y',
        },
        success: function (data) {
          $('.addToCompareList[data-id="' + $cartItemID + '"]').addClass('notActive');
          $(document).find('.compare-page-item[data-id="' + $cartItemID + '"] .compare__basket.hidden-remove').click();
          return $.fn.updateMiniCompare(data);
        },
      });
    }
  });

  $(document).on('click', '.js-clear-compare', function () {
    $(document).find('.compare-page-item .compare__basket.hidden-remove').click();
    return $.ajax({
      type: 'POST',
      url: '/ajax/ajax_func.php',
      data: {
        change_compare_list: 'Y',
        clear_all: 'Y',
        AJAX_MIN_COMPARE: 'Y',
      },
      success: function (data) {
        $('.addToCompareList').addClass('notActive');
        return $.fn.updateMiniCompare(data);
      },
    });
  });

  var cartUpdateCallback = function () {
    if ($(this).hasClass('sci-top__count-change')) {
      $(this).attr('data-q', $(this).val())
    }

    var that = this;
    var cartItemID = $(this).attr('data-id');
    var countProd = parseInt($(this).attr('data-q'));
    var soBlock = $('#so_main_block');

    if ($(this).hasClass('sci-top__count-up')) {
      countProd = countProd + 1;
    } else if ($(this).hasClass('sci-top__count-down')) {
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
          COUNT: countProd,
        },
        beforeSend: function () {
          soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
        },
        success: function (data) {
          // soBlock.find('.preloaderCatalog').removeClass('preloaderCatalogActive');
          var productId = $(that).closest('.sci-product__info').find('[data-product-list]').data('product-id');

          if ($(that).hasClass('sci-top__count-up')) {
            window.gtmActions.addToCartHandler(productId, 'cart');
          }

          if ($(that).hasClass('sci-top__count-down')) {
            window.gtmActions.removeFromCartHandler(productId);
          }
          $.fn.updateCart(data);
          return $.fn.refreshMiniCart();
        },
        error: function () {
          soBlock.find('.preloaderCatalog').removeClass('preloaderCatalogActive');
        },
      });
    }
  };

  $(document).on('click', '.sci-top__count-up, .sci-top__count-down', cartUpdateCallback);
  $(document).on('change', '.sci-top__count-change', cartUpdateCallback);

  $(document).on('click', '.sci-add__button-hide', function () {
    var collapsedClass = 'sci-add--collapsed';
    var hideButtonOnClass = 'sci-add__button-hide--on';
    var $hideButton = $(this);
    var $collapsableBlock = $hideButton.closest('.sci-add__block');

    if ($collapsableBlock.hasClass(collapsedClass)) {
      $collapsableBlock.removeClass(collapsedClass);
      $hideButton.removeClass(hideButtonOnClass);
    } else {
      $collapsableBlock.addClass(collapsedClass);
      $hideButton.addClass(hideButtonOnClass);
    }
  });

  $(document).on('click', '.sci-top__remove', function () {
    var $cartItemID = $(this).attr('data-id'),
      productId = $(this).data('product-id'),
      outOfStock = $(this).data('out-of-stock');

    if ($cartItemID) {
      $(document).find('#shopcart-item1 .sci-product[data-id="' + $cartItemID + '"]').remove();
      if ($(document).find('#shopcart-item1 .sci-product').length <= 0) {
        $(document).find('.js-basket-clear').remove();
      }
      return $.ajax({
        url: '/ajax/add_to_cart.php',
        type: 'POST',
        data: {
          PRODUCT_ID: $cartItemID,
          METHOD_CART: 'delete',
          AJAX_CART: 'Y',
          'productId': productId,
          'outOfStock': outOfStock,
        },
        beforeSend: function () {
          $(document).find('#so_main_block').find('.preloaderCatalog').addClass('preloaderCatalogActive');
        },
        success: function (data) {
          window.gtmActions.removeFromCartHandler(productId);

          if (outOfStock === 1) {
            window.location.replace('/cart/');
          } else {
            $.fn.updateCart(data);
          }
        },
      });
    }
  });

  $(document).on('click', '.js-basket-clear', function () {
    var productIds = $(document).find('#shopcart-item1 .sci-top__remove').map(function () {
      return Number($(this).data('product-id'));
    }).get();

    $(document).find('#shopcart-item1 .sci-product').remove();
    $(document).find('.js-basket-clear').remove();
    return $.ajax({
      url: '/ajax/add_to_cart.php',
      type: 'POST',
      data: {
        METHOD_CART: 'clear',
        AJAX_CART: 'Y',
      },
      success: function (data) {
        window.gtmActions.removeFromCartHandler(productIds);
        $.fn.updateCart(data);
      },
    });
  });

  $(document).on('click', '.square-color', function (e) {
    var activePhoto,
      dcolor;
    if (!$(this).hasClass('propDisabled')) {
      dcolor = $(this).attr('data-color');
      $(document).find('.product-photo__left img').removeClass('active');
      $(document).find('.product-photo__left .pp__is_offer').addClass('pp__is_offer__disable');
      $(document).find('.product-photo__left .pp__is_offer[data-color="' + dcolor + '"]').removeClass(
        'pp__is_offer__disable');
      $(document).find('.product-photo__right .pp__big_photo').removeClass('active');
      $(document).find('.product-photo__right .pp__is_offer').addClass('pp__is_offer__disable');
      $(document).find('.product-photo__right .pp__is_offer').attr('data-fancybox', '');
      $(document).find('.product-photo__right .pp__is_offer[data-color="' + dcolor + '"]').removeClass(
        'pp__is_offer__disable');
      $(document).find('.product-photo__right .pp__is_offer[data-color="' + dcolor + '"]').attr('data-fancybox',
        'gallery-prod');
      activePhoto = null;
      if ($(document).find('.product-photo__left .pp__is_offer[data-color="' + dcolor + '"]').is('img')) {
        activePhoto = $(document).find('.product-photo__left .pp__is_offer[data-color="' + dcolor + '"]').eq(0);
      } else {
        if ($(document).find('.product-photo__left .pp__is_prod').is('img')) {
          activePhoto = $(document).find('.product-photo__left .pp__is_prod').eq(0);
        }
      }
      if (activePhoto) {
        activePhoto.addClass('active');
        return $(document).find(
          '.product-photo__right .pp__big_photo[data-photo-id="' + activePhoto.attr('data-photo-id') + '"]').addClass(
            'active');
      }
    }
  });

  $(document).on('click', '.product-photo__left img', function () {
    $(document).find('.product-photo__left img').removeClass('active');
    $(document).find('.product-photo__right .pp__big_photo').removeClass('active');
    $(this).addClass('active');
    return $(document).find(
      '.product-photo__right .pp__big_photo[data-photo-id="' + $(this).attr('data-photo-id') + '"]').addClass('active');
  });

  $(document).on('click', '.addToFavoriteList', function () {
    var $this,
      prodID;
    prodID = $(this).attr('data-id');
    $this = $(this);
    return $.ajax({
      type: 'POST',
      url: '/ajax/ajax_func.php',
      data: {
        change_favorite_list: 'Y',
        product_id: prodID,
        AJAX_MIN_FAVORITE: 'Y',
      },
      success: function (data) {
        window.gtmActions.changeWishListHandler(prodID, data);
        var $miniFavorite = $('#mini_favorite_header');
        var showMiniFavoriteClass = 'preview-heart--show';

        $miniFavorite.addClass(showMiniFavoriteClass);
        $('#mini_compare_header').removeClass('preview-heart--show');
        $('#mini_cart_header').removeClass('preview-card--show');

        var showMiniFavorite = function () {
          if ($miniFavorite.hasClass(showMiniFavoriteClass)) {
            $miniFavorite.removeClass(showMiniFavoriteClass);
          }
        };

        debounce(showMiniFavorite, 3500);

        $.fn.updateMiniFavorite(data);
        if ($this.hasClass('notActive')) {
          $this.removeClass('notActive');
          $(document).find('.addToFavoriteList[data-id="' + prodID + '"]').parent('label').find(
            'input[type="checkbox"]').prop('checked', true);
        } else {
          $this.addClass('notActive');
          $(document).find('.addToFavoriteList[data-id="' + prodID + '"]').parent('label').find(
            'input[type="checkbox"]').prop('checked', false);
        }
        if ($(document).find('.addToFavoriteListOnFP').is('div')) {
          $.fn.ajaxLoadCatalog();
        }
        if ($(document).find('.addToFavoriteListOnFP_NOT_ITEM').is('div')) {
          return location.href = '/user/favorite/';
        }
      },
    });
  });

  $(document).on('click', '.addToCompareList', function () {
    var $this,
      prodID;
    prodID = $(this).attr('data-id');
    $this = $(this);
    return $.ajax({
      type: 'POST',
      url: '/ajax/ajax_func.php',
      data: {
        change_compare_list: 'Y',
        product_id: prodID,
        AJAX_MIN_COMPARE: 'Y',
      },
      success: function (data) {
        var $miniCompare = $('#mini_compare_header');
        var showMiniCompareClass = 'preview-heart--show';

        $miniCompare.addClass(showMiniCompareClass);
        $('#mini_favorite_header').removeClass('preview-heart--show');
        $('#mini_cart_header').removeClass('preview-card--show');

        var showMiniCompare = function () {
          if ($miniCompare.hasClass(showMiniCompareClass)) {
            $miniCompare.removeClass(showMiniCompareClass);
          }
        };

        debounce(showMiniCompare, 3500);

        $.fn.updateMiniCompare(data);

        if ($this.hasClass('notActive')) {
          return $(document).find('.addToCompareList[data-id="' + prodID + '"]').removeClass('notActive');
        } else {
          return $(document).find('.addToCompareList[data-id="' + prodID + '"]').addClass('notActive');
        }
      },
    });
  });

  // Инициализация календаря
  (function () {
    var timeRanges = window.paramTimeRanges;
    if (!timeRanges) {
      return;
    }
    var startDate = new Date();
    var assemblyDays = $.fn.getOrderAssemblyTimeObj().days;
    var nextDay = false;
    if (startDate.getHours() >= timeRanges[timeRanges.length - 1].fromHour && assemblyDays <= 0) {
      startDate = new Date(startDate.setDate(startDate.getDate() + 1));
      nextDay = true;
    }

    // К базовой дате прибавляем срок сборки
    startDate.setDate(startDate.getDate() + assemblyDays);

    $('.js-shopcart-datepicker').datepicker({
      language: 'ru',
      startDate: startDate,
      beforeShowDay: function (date) {
        var currentDate = new Date;

        var allowedDays = [];
        if (nextDay) {
          currentDate.setDate(currentDate.getDate() + 1);
        }

        // К базовой дате прибавляем срок сборки
        currentDate.setDate(currentDate.getDate() + assemblyDays);
        allowedDays.push(currentDate.toLocaleDateString());
        currentDate.setDate(currentDate.getDate() + 1);
        allowedDays.push(currentDate.toLocaleDateString());
        return allowedDays.indexOf(date.toLocaleDateString()) >= 0;
      },
    }).datepicker("setDate", startDate);

    checkDeliveryTime();
  })();

  $(document).on('change', '.sci-delivery__radio', function () {
    var $this = $(this);
    var $thisParent = $this.closest('.sci-delivery-tab');

    if (
      (
        $this.next('[data-prop="ID_DELIVERY_ID_5"]').length > 0
        || $this.next('[data-prop="ID_DELIVERY_ID_8"]').length > 0
        || $this.next('[data-prop="ID_DELIVERY_ID_9"]').length > 0
        || $this.next('[data-prop="ID_DELIVERY_ID_10"]').length > 0
        || $this.next('[data-prop="ID_DELIVERY_ID_11"]').length > 0
      )
      && $this.prop('checked')
    ) {
      $thisParent.append($('#sci-delivery-content1'));
    }

    $('#sci-delivery-street')
        .val('')
        .removeClass("is-error")
        .removeClass("is-success")
        .removeClass("invalid-address");

    $.fn.toggleDeliveryPriceInfoVisibility();
  });

  app.deliveryAddress.setCheckPreFilledFlag();
  $.fn.updateDateSaleOrder();

  $(document).on('click', '#soDelivPopUp', function () {
    return $(document).find('.SDEK_selectPVZ').click();
  });

  $(document).on('click', '.rb_so', function (event) {

    if ($(this).attr('data-prop')) {
      if ($(this).closest('.sci-delivery').length) {
        $(document).find('#module_so').find('[name=\'isChangeLocation\']').remove();
      }
      var result = $.fn.changeRadioButtonSaleOrder($(this).attr('data-prop'));

      if ($(this).attr('data-prop').indexOf("PERSON_TYPE_") >= 0
          && event.hasOwnProperty('originalEvent')
          && event.originalEvent.isTrusted === true) {
          app.deliveryAddress.setCheckPreFilledFlag();
      }

      return result;
    }
  });

  $(document).find('#module_so').bind('DOMSubtreeModified', function () {
    var soModule;
    soModule = $(document).find('#module_so');
    if (soModule.find('.wrewfwer .wrewfwer_ajax').is('span')) {
      var parentElement = document.querySelector(".wrewfwer");
      parentElement.innerHTML = '';
      return $.fn.updateDateSaleOrder();
    }
  });

  $(document).on('change', '#so_city_val', function () {
    var $this,
      soBlock,
      soCityAlt,
      soCityAltID,
      soCityID,
      soModule;
    soCityID = $(document).find('#so_city');
    soCityAltID = $(document).find('#so_city_alt');
    soCityAlt = $(document).find('#so_city_alt_val');
    soBlock = $(document).find('#so_main_block');
    soModule = $(document).find('#module_so');
    $this = $(this);
    var $checkedDeliveryRadio = $('.sci-delivery__radio:checked');
    // $checkedDeliveryRadio.prop('checked', false);
    soModule.find('#' + $checkedDeliveryRadio.next('.sci-delivery__tab').data('prop')).prop('checked', false);

    var cityName = $this.val() ? $this.val().split(', ')[0] : '';

    $('#sci-delivery-street').suggestions('setOptions', {
      constraints: {
        locations: {
          city: cityName,
        },
      },
    }).val('');
    return setTimeout(function () {
      if (soCityID.val() === soCityID.attr('data-old')) {
        if (document.hasOwnProperty('holdChangeCity') && document.holdChangeCity === true) {
          delete document.holdChangeCity;
        } else {
          $this.val($this.attr('data-old'));
        }

        return soCityID.val(soCityID.attr('data-old'));
      } else {
        soBlock.find('.sci-delivery__radio:checked').prop('checked', false);
        soModule.find('input[name="DELIVERY_ID"]:checked').prop('checked', false);
        soCityID.attr('data-old', $this.val());
        $this.attr('data-old', soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop') + '"]').val(soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop-val') + '"]').val($this.val());
        soModule.find('[name="' + $this.attr('data-city-prop-alt') + '"]').val(soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop-val-alt') + '"]').val($this.val());
        soCityAltID.val(soCityID.val());
        soCityAlt.val($this.val());

        var orderForm = soModule.find('#ORDER_FORM');

        if (orderForm.find('[name=\'isChangeLocation\']').length <= 0) {
          orderForm.prepend('<input type=\'hidden\' name=\'isChangeLocation\' value=\'Y\'>');
        }
        soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');

        return submitForm();
      }
    }, 300);
  });

  $(document).on('change', '#so_city_alt_val', function () {
    var $this,
      soBlock,
      soCityAlt,
      soCityAltID,
      soCityID,
      soModule;
    soCityID = $(document).find('#so_city_alt');
    soCityAltID = $(document).find('#so_city');
    soCityAlt = $(document).find('#so_city_val');
    soBlock = $(document).find('#so_main_block');
    soModule = $(document).find('#module_so');
    $this = $(this);
    return setTimeout(function () {
      if (soCityID.val() === soCityID.attr('data-old')) {
        $this.val($this.attr('data-old'));
        return soCityID.val(soCityID.attr('data-old'));
      } else {
        soBlock.find('.sci-delivery__radio:checked').prop('checked', false);
        soModule.find('input[name="DELIVERY_ID"]:checked').prop('checked', false);
        soCityID.attr('data-old', $this.val());
        $this.attr('data-old', soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop') + '"]').val(soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop-val') + '"]').val($this.val());
        soModule.find('[name="' + $this.attr('data-city-prop-alt') + '"]').val(soCityID.val());
        soModule.find('[name="' + $this.attr('data-city-prop-val-alt') + '"]').val($this.val());
        soCityAltID.val(soCityID.val());
        soCityAlt.val($this.val());

        var orderForm = soModule.find('#ORDER_FORM');

        if (orderForm.find('[name=\'isChangeLocation\']').length <= 0) {
          orderForm.prepend('<input type=\'hidden\' name=\'isChangeLocation\' value=\'Y\'>');
        }
        soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
        return submitForm();
      }
    }, 300);
  });

  $(document).on('change',
    '[name="sci-contact__fio"], [name="sci-contact__tel"], [name="sci-contact__ur-name"], [name="sci-contact__ur-phone"], [name="so_city_val"], [name="so_city_alt_val"], [name="sci-delivery-street"], [name="sci-delivery-building"], [name="sci-delivery-apartment"], [name="sci-delivery-date"], [name="sci-delivery-time"], [name="ORDER_PROP_37"], [name="ORDER_PROP_36"]',
    function () {
      return $.fn.updateSideInfo();
    });

  $(document).on('change', '.sci-contact__input', function () {
    if (['sci-contact__tel', 'sci-contact__ur-phone'].indexOf($(this).attr('name')) >= 0) {
      var phone = $(this).val().replace(/[- )(]/g, '').replace('+7', '');
      if (phone.length <= 0) {
        $(this).removeClass('is-error');
        $(this).removeClass('is-success');
      } else {
        if (!numberPhoneValidation(phone)) {
          $(this).removeClass('is-success');
          $(this).addClass('is-error');
          return $.fn.setPushUp('Ошибка', 'Введён некорректный номер телефона', false, 'message', false, 5000, undefined, 'push_up_item--error');
        } else {
          $(this).removeClass('is-error');
          $(this).addClass('is-success');
        }
      }
    }

    if ($(this).prop('required')) {
      if ($(this).val() !== '' && !$(this).hasClass("invalid-address")) {
        $(this).addClass('is-success');
        $(this).removeClass('is-error');
      } else {
        $(this).removeClass('is-success');
      }
    }
  });

  $('.sci-contact__input[type=\'email\']').on('blur', function () {
    var $parentBlock = $(this).closest('.sci-contact-content');
    if ($parentBlock.find('.js-error-msg').length <= 0) {
      $(this).after('<p class=\'js-error-msg\' style=\'width:100%\'></p>');
    }

    var $errorBlock = $parentBlock.find('.js-error-msg');

    let email = $(this).val();
    var $passBlock = $parentBlock.find('.js-password-block');

    if (
      email.length >= 0
      && email.match(REG_EXP_EMAIL) === null
    ) {
      $(this).addClass('is-error').removeClass('is-success');

      // $errorBlock.text("Не правильно введен email").css("color", "red");
      $passBlock.addClass('dsb-hidden');
    } else {
      $(this).removeClass('is-error').addClass('is-success');
      // $errorBlock.text("Email введен верно").css("color", "green");

      var isUserExists = false;

      $(document).find('.preloaderCatalog').addClass('preloaderCatalogActive');

      $.ajax('/ajax/checkout.php',
        {
          method: 'GET',
          dataType: 'JSON',
          data: {
            type: 'checkEmail',
            email: email,
            sessid: BX.bitrix_sessid(),
          },
          success: function (result) {
            $(document).find('.preloaderCatalog').removeClass('preloaderCatalogActive');

            if (result.type === 'ok') {
              isUserExists = Number(result.exist) === 1;
            }

            if (isUserExists) {
              $passBlock.removeClass('dsb-hidden');
            } else {
              $passBlock.addClass('dsb-hidden');
            }
          },
        },
      );
    }
  });

  app.$doc.on('click', '#SDEK_button', function () {
    $(document).find('#so_main_block').find('.preloaderCatalog').addClass('preloaderCatalogActive');
  });

  app.$doc.on('click', '.js-shopcart-cart-link', function (e) {
    app.$doc.find('.shopcart-nav1 label[for="shopcart-tab1"]').click();

    e.preventDefault();
  });

  $('').on('blur', function () {
    var $parentBlock = $(this).closest('.sci-contact-content');
    if ($parentBlock.find('.js-error-msg').length <= 0) {
      $(this).after('<p class=\'js-error-msg\' style=\'width:100%\'></p>');
    }

    var $errorBlock = $parentBlock.find('.js-error-msg');

    let email = $(this).val();
    var $passBlock = $parentBlock.find('.js-password-block');

    if (
      email.length >= 0
      && email.match(REG_EXP_EMAIL) === null
    ) {
      $(this).addClass('is-error').removeClass('is-success');

      // $errorBlock.text("Не правильно введен email").css("color", "red");
      $passBlock.addClass('dsb-hidden');
    } else {
      $(this).removeClass('is-error').addClass('is-success');
      // $errorBlock.text("Email введен верно").css("color", "green");

      var isUserExists = false;

      $.ajax('/ajax/checkout.php',
        {
          method: 'GET',
          dataType: 'JSON',
          data: {
            type: 'checkEmail',
            email: email,
            sessid: BX.bitrix_sessid(),
          },
          success: function (result) {
            if (result.type === 'ok') {
              isUserExists = Number(result.exist) === 1;
            }

            if (isUserExists) {
              $passBlock.removeClass('dsb-hidden');
            } else {
              $passBlock.addClass('dsb-hidden');
            }
          },
        },
      );
    }
  });

  $(document).on('change', '#module_so [name="PERSON_TYPE"]', function () {
    $('#so_main_block .sci-delivery [data-change="Y"]').removeAttr('data-change');
  });

  $(document).on('change', '#so_main_block [data-prop]', function () {
    var soModule = $(document).find('#module_so');
    var valEl = $(this).val();

    if ($(this).is('select')) {
      soModule.find('[name="' + $(this).attr('data-prop') + '"] option[value="' + valEl + '"]').prop('selected', true);

      if ($(this).attr('data-prop-alt')) {
        soModule.find('[name="' + $(this).attr('data-prop-alt') + '"] option[value="' + valEl + '"]').prop('selected',
          true);
      }
    } else {
      soModule.find('[name="' + $(this).attr('data-prop') + '"]').val(valEl);

      if ($(this).attr('data-prop-alt')) {
        soModule.find('[name="' + $(this).attr('data-prop-alt') + '"]').val(valEl);
      }
    }
  });

  $(document).on('click', '#btnSubmitOrder', function (e) {
    e.preventDefault;

    $.ajax({
      type: 'POST',
      url: '/ajax/checkQuantity.php',
      data: {},
      beforeSend: function () {
        $(document).find('#so_main_block').find('.preloaderCatalog').addClass('preloaderCatalogActive');
      },
    }).done(function (data) {
      data = jQuery.parseJSON(data);
      if (data['productsOutOfStock']) {
        window.location.replace('/cart/');
      } else {
        $(document).trigger('checkoutEvent');
      }
    });
  });

  $(document).on('checkoutEvent', function () {
    if (!document.querySelector('.js-shopcart-agree').checked) {
      $(document).find('.preloaderCatalog').removeClass('preloaderCatalogActive');
      $.fn.setPushUp('Ошибка', 'Нужно ваше согласие на обработку персональных данных', false, 'message', false,
        5000, undefined, 'push_up_item--warning');
      return false;
    }

    var firstBlock,
      hasError,
      inputCount,
      secondBlock,
      soBlock,
      soModule;

    soBlock = $(document).find('#so_main_block');
    soModule = $(document).find('#module_so');
    firstBlock = '#shopcart-item2 #sci-contact-content1';
    secondBlock = '#shopcart-item3 #sci-delivery-content1';

    if ($(document).find('#sci-contact-tab2').prop('checked')) {
      firstBlock = '#shopcart-item2 #sci-contact-content2';
    }

    if ($(document).find('#sci-delivery-tab2').prop('checked')) {
      var deliveryTabSiblings = $('#sci-delivery-tab2').siblings('.sci-delivery__tab.rb_so');
      // deliveryTabSiblings.data("prop","ID_DELIVERY_ID_13");
      secondBlock = '#shopcart-item3 #sci-delivery-content2';
    }

    if ($(document).find('#sci-delivery-tab3').prop('checked')) {
      secondBlock = '';
    }

    inputCount = $(document).find(firstBlock + ' input, ' + firstBlock + ' textarea, ' + firstBlock + ' select').length;
    hasError = false;

    return $(document).find(firstBlock + ' input, ' + firstBlock + ' textarea, ' + firstBlock + ' select').each(
      function () {
        var valEl;
        if ($(this).attr('data-prop')) {
          valEl = $(this).val();
          if ($(this).prop('required') && !valEl) {
            $(document).find('#shopcart-tab2').click();
            $(this).css({
              'border-color': '#ef0000',
            });
            $(this).on('focus', function () {
              return $(this).css({
                'border-color': '#C4C4C4',
              });
            });
            hasError = true;
          }
          if ($(this).is('select')) {
            soModule.find('[name="' + $(this).attr('data-prop') + '"] option[value="' + valEl + '"]').prop('selected',
              true);
          } else if ($(this).is('[type="checkbox"]')) {
            var $soModuleField = soModule.find('[name="' + $(this).attr('data-prop') + '"]');
            if ($soModuleField.is('[type="checkbox"]')) {
              $soModuleField.prop("checked", $(this).prop('checked'));
            } else {
              $soModuleField.val($(this).prop('checked') ? "Да" : "Нет");
            }
          }else {
            soModule.find('[name="' + $(this).attr('data-prop') + '"]').val(valEl);
          }
        }

        var fizPerson = soModule.find('#PERSON_TYPE_1').prop('checked');

        if (!--inputCount) {
          if (!hasError) {
            if (secondBlock) {
              inputCount = $(document).find(
                secondBlock + ' input, ' + secondBlock + ' textarea, ' + secondBlock + ' select').length;
              return $(document).find(
                secondBlock + ' input, ' + secondBlock + ' textarea, ' + secondBlock + ' select').each(function () {
                  var propertyName = fizPerson ? $(this).attr('data-prop') : $(this).attr('data-prop-alt');
                  if (propertyName) {
                    valEl = $(this).val();
                    if ($(this).prop('required') && !valEl) {
                      $(document).find('#shopcart-tab3').click();
                      $(this).css({
                        'border-color': '#ef0000',
                      });
                      $(this).on('focus', function () {
                        return $(this).css({
                          'border-color': '#C4C4C4',
                        });
                      });
                      hasError = true;
                    }
                    soModule.find('[name="' + propertyName + '"]').val(valEl);
                  }

                  if (!--inputCount) {
                    if (!hasError) {
                      soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
                      return soModule.find('[name="submitbutton"]').click();
                    }
                  }
                });
            } else {
              if (!hasError) {
                soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
                return soModule.find('[name="submitbutton"]').click();
              }
            }
          }
        }
      });
  });

  var suggestions = {
    selected: false,
    lastSuggestionsItems: false,
    nothing: false,
  };

  var $deliveryStreetField = $('.js-delivery-street');

  $deliveryStreetField.on('change', function () {
    if (suggestions.selected) {
      suggestions.selected = false;
      return true;
    }

    var that = this;
    var isSetZip = false;
    if (suggestions.lastSuggestionsItems.length > 0 && !suggestions.nothing) {
      suggestions.lastSuggestionsItems.forEach(function (item) {
        if (suggestions.lastSuggestionsItems.length > 1) {
          var address = item.data.city_with_type ?
            item.value.replace(item.data.city_with_type + ', ', '')
            : item.value;

          if (address !== $(that).val().trim()) {
            return false;
          }

        }
        setZipCode(item.data.postal_code);
        isSetZip = true;
        return true;
      });
    }

    if (suggestions.lastSuggestionsItems.length <= 0 || suggestions.nothing || !isSetZip) {
      setZipCode(0);
    }
  });

  $deliveryStreetField.suggestions({
    token: app.dadataToken,
    type: 'ADDRESS',
    placeholder: 'Введите город доставки',
    constraints: {
      locations: {
        city: $('#so_city_val').val() ? $('#so_city_val').val().split(', ')[0] : '',
      },
    },
    onSelect: function (result) {
      /* Для Москвы */
      if ($.fn.isMoscow()) {
        if (!app.deliveryAddress.process(result.data)) {
          return false;
        }
      }
      setZipCode(result.data.postal_code);
      suggestions.selected = true;
      suggestions.nothing = false;
    },
    onSelectNothing: function () {
      suggestions.nothing = true;
      suggestions.selected = false;
      app.deliveryAddress.setError(true, "Пожалуйста, выберите адрес из списка");
    },
    onSuggestionsFetch: function (items) {
      suggestions.lastSuggestionsItems = items;
    },
    // в списке подсказок не показываем область
    restrict_value: true,
  }).attr('autocomplete', 'none');

  $.fn.toggleDeliveryPriceInfoVisibility();

  $('.js-auth').on('click', function (e) {
    e.preventDefault();
    var password = $(this).closest('.js-password-block').find('.js-password-field').val();
    var email = $(this).closest('section').find('.js-email-field').val();

    if (email.length <= 0) {
      $.fn.setPushUp('Ошибка авторизации', 'Не указан e-mail', false, 'message', false, 5000, undefined, 'push_up_item--error');
      return false;
    }

    if (password.length <= 0) {
      $.fn.setPushUp('Ошибка авторизации', 'Не указан пароль', false, 'message', false, 5000, undefined, 'push_up_item--error');
      return false;
    }

    $(document).find('.preloaderCatalog').addClass('preloaderCatalogActive');

    $.ajax('/ajax/checkout.php',
      {
        method: 'POST',
        dataType: 'JSON',
        data: {
          type: 'authorize',
          email: email,
          password: password,
          sessid: BX.bitrix_sessid(),
        },
        success: function (result) {
          if (result.success) {
            $('.js-email-block').addClass('dsb-hidden');
            $('.js-password-block').addClass('dsb-hidden');
            BX('profile_change').value = 'Y';

            $.ajax('/ajax/checkout.php',
              {
                method: 'GET',
                dataType: 'JSON',
                data: {
                  type: 'updateBasket',
                  sessid: BX.bitrix_sessid(),
                },
                success: function (result) {
                  if (result.html) {
                    $('.js-shopcart-items').html(result.html);
                  }
                },
              });

            submitForm();

            $(document).find('#module_so').one('DOMSubtreeModified', function () {
              // HACK: Удаляем data-change, чтобы можно было обновить значения полей
              $('#so_main_block').find('[data-change="Y"]').removeAttr('data-change');
              // Обновляем значения полей из дефолтного модуля
              $.fn.updateDateSaleOrder();
              // Обновляем товары в корзине и стоимость
              $.fn.updateSideInfo();

              // Обновляем маску телефона
              setTimeout(function () {
                $('#so_main_block').find('input[name="sci-contact__tel"]').unmask().mask('+7 (999) 999-99-99');
              }, 0);
            });

            $.fn.setPushUp(
              'Авторизация',
              'Вы успешно авторизовались',
              false,
              'message',
              false,
              5000,
              undefined,
              'push_up_item--success'
            );
          } else {
            $(document).find('.preloaderCatalog').removeClass('preloaderCatalogActive');

            if (result.errorMessage) {
              $.fn.setPushUp(
                'Ошибка авторизации',
                result.errorMessage,
                false,
                'message',
                false,
                5000,
                undefined,
                'push_up_item--error'
              );
            }
          }
        },
      },
    );
  });

  $(document).on('change', '.sci-contact', function () {
    var $inputs = $('#sci-contact__email, #sci-contact__fio, #sci-contact__tel');
    var persistData = JSON.parse(localStorage.getItem('checkout:contacts')) || {};

    $inputs.each(function () {
      persistData[this.name] = this.value;
    });

    localStorage.setItem('checkout:contacts', JSON.stringify(persistData));
  });

  $(document).on('click', '[data-product-list]', window.gtmActions.productClickHandler);

  $(document).on('click', '[data-product-list]', function () {
    if ($(this).data('href')) {
      location.href = $(this).data('href');
    }
  });

  $(document).on('click', '[data-src="#popap-buy-one-click"]', function () {
    window.gtmActions.addToCartHandler($(this).data('id'), 'oneClick');
  });

  var searchPageBlock = $(document).find('.catalog-main.catalog-main-sr').parent();
  //TODO search page pagination
  //observation on change pagination on search page
  if (searchPageBlock.length) {
    (new MutationObserver(function (mutationsList) {
      var searchBlock = $(document).find('.catalog-main.catalog-main-sr').parent();
      window.gtmActions.setProducts(searchBlock.find('[data-gtm-products]').data('gtm-products'));
    })).observe(
      searchPageBlock[0],
      { childList: true },
    );
  }

  restorePersistData();

  $.fn.updateSideInfo();

  function setZipCode(zip) {
    zip = Number(zip) > 0 ? zip : '000000';
    var soModule;
    soModule = $(document).find('#module_so');
    if (soModule.is('div')) {
      if (soModule.find('#PERSON_TYPE_2').prop('checked')) {
        soModule.find('[name="ORDER_PROP_39"]').val(zip);
      } else {
        soModule.find('[name="ORDER_PROP_38"]').val(zip);
      }
    }
  }

  function numberPhoneValidation(numberPhone) {
    var isValid = false;
    var regs = [
      /^(?:3(?:0[12]|4[1-35-79]|5[1-3]|65|8[1-58]|9[0145])|4(?:01|1[1356]|2[13467]|7[1-5]|8[1-7]|9[1-689])|8(?:1[1-8]|2[01]|3[13-6]|4[0-8]|5[15]|6[1-35-79]|7[1-37-9]))\d{7}$/,
      /^9\d{9}$/,
      /^80[04]\d{7}$/,
      /^80[39]\d{7}$/,
      /^808\d{7}$/,
    ];

    var obRegex = new RegExp(/^(?:[3489]\d{9})$/);

    isValid = obRegex.exec(numberPhone) !== null;

    if (isValid) {
      regs.forEach(function (regex) {
        obRegex = new RegExp(regex);

        if (obRegex.exec(numberPhone) !== null) {
          isValid = true;
          return false;
        }
      });
    }
    return isValid;
  }

  function restorePersistData() {
    var persistData = JSON.parse(localStorage.getItem('checkout:contacts')) || {};

    for (var key in persistData) {
      var $input = $('[name="' + key + '"]');

      $input.val(persistData[key]);
    }
  }

    function checkDeliveryTime() {
        var $deliveryTime = $('#sci-delivery-time');
        var $deliveryDate = $('.js-shopcart-datepicker');

        var timeRanges = window.paramTimeRanges;
        if (!timeRanges) {
            timeRanges = {};
        }

        var selectedDate = $deliveryDate.datepicker('getDate');
        var startDate = $deliveryDate.datepicker('getStartDate');
      $deliveryTime.find("option").remove();
        if (!selectedDate || (selectedDate.hasOwnProperty('length') && selectedDate.length <= 0)) {
            timeRanges.forEach(function (range) {
                $deliveryTime.append(getDeliveryTimeOption(range));
            });
            $deliveryTime.val($deliveryTime.find('option').first().attr('value'));
            $deliveryTime.attr('disabled', true);
            return true;
        }

        $deliveryTime.attr('disabled', false);

      var currentDate = new Date();
      var isToday = selectedDate.toLocaleDateString() === currentDate.toLocaleDateString();
      var isStartDate = selectedDate.toLocaleDateString() === startDate.toLocaleDateString();
      var needReduceIntervals = $.fn.getOrderAssemblyTimeObj().limitStartDate && isStartDate;

      timeRanges.forEach(function (range, index, array) {
        if (isToday && currentDate.getHours() >= range.fromHour) {
          return true;
        }

        if (needReduceIntervals && index < array.length - 1) {
          return true;
        }

        $deliveryTime.append(getDeliveryTimeOption(range));
      });
      $deliveryTime.val($deliveryTime.find('option').first().attr('value'));
    }

  function isValidDeliveryTime() {
    var timeRangeId = parseInt($('#sci-delivery-time').val());
    var deliveryDate = $('#sci-delivery-date').datepicker('getDate');
    var currentDate = new Date();
    if (timeRangeId <= 0) {
      return true;
    }
    var rangeIndex = window.paramTimeRanges.findIndex(function (element) {
          return parseInt(element.variantId) === timeRangeId;
        }
    );
    if (rangeIndex < 0) {
      return false;
    }
    var currentRange = window.paramTimeRanges[rangeIndex];

    if (!deliveryDate || deliveryDate.toLocaleDateString() !== currentDate.toLocaleDateString()) {
      return true;
    }

    return currentDate.getHours() < currentRange.fromHour;
  }

    function getDeliveryTimeOption(timeRange) {
        var $option = $("<option></option>");
        $option.val(timeRange.variantId);
        $option.data("start", timeRange.fromHour);
        $option.data("end", timeRange.toHour);
        $option.text(timeRange.fromHour + ":00 - " + timeRange.toHour + ":00");
        return $option;
    }
});

var isSideInfoInited = false;

$.fn.toggleDeliveryPriceInfoVisibility = function () {
  var isDeliveryChecked = $('.sci-delivery-tab:not(.rb_so__hide) .sci-delivery__radio:checked').length > 0;

  $('.shopcart-sidebar__info--delivery, .shopcart-sidebar__sum-price--delivery').toggleClass('sc-hidden',
    !isDeliveryChecked);
};

$.fn.getOrderAssemblyTimeObj = function (isExtDelivery = false) {
  var orderAssembly = {
    days: 0,
    limitStartDate: false,
  };

  $(".js-basket-item").each(function (idx, basketItem) {
    var assemblyTime = parseInt($(basketItem).data("assembly-time")) || 0;
    if (assemblyTime > orderAssembly.days) {
      orderAssembly.days = assemblyTime;
    }
  });

  if (isExtDelivery) {
    return orderAssembly;
  }

  if (orderAssembly.days <= 0) {
    return orderAssembly;
  }

  //Добавляем текущий день к сроку сборки, если сейчас больше 9 утра
  if (app.weekTools.currentHour >= 9) {
    orderAssembly.days++;
  } else {
    //Или урезаем интервалы для следующего дня
    orderAssembly.limitStartDate = true
  }

  return orderAssembly;
};

$.fn.isMoscow = function (cityId = false) {
  if (!cityId) {
    cityId = parseInt($(document).find('#so_main_block #so_city').val());
  }

  return cityId === 84;
};

$.fn.updateSideInfo = function () {
  var deliveryPrice,
    soBlock,
    soModule,
    totalPrice,
    uAddress,
    uCity,
    uDeliveryDate,
    uDeliveryTime,
    uName,
    uPhone;
  soBlock = $(document).find('#so_main_block');
  soModule = $(document).find('#module_so');
  uName = '[name="sci-contact__fio"]';
  uPhone = '[name="sci-contact__tel"]';
  uEmail = '[name="sci-contact__email"]';
  if (soModule.find('#PERSON_TYPE_2').prop('checked')) {
    uName = '[name="sci-contact__ur-name"]';
    uPhone = '[name="sci-contact__ur-phone"]';
    uEmail = '[name="sci-contact__ur-email"]';
  }
  soBlock.find('.shopcart-sidebar__buyer-fio').html(soBlock.find(uName).val());
  soBlock.find('.shopcart-sidebar__buyer-tel').html(soBlock.find(uPhone).val());
  soBlock.find('.shopcart-sidebar__buyer-email').html(soBlock.find(uEmail).val());

  uCity = soBlock.find('[name="so_city_val"]').val() + ", ";
  uAddress = '';
  if (soBlock.find('[name="sci-delivery-street"]').val()) {
    uAddress += soBlock.find('[name="sci-delivery-street"]').val() + ' ';
  }
  if (soBlock.find('[name="sci-delivery-building"]').val()) {
    uAddress += 'д. ' + soBlock.find('[name="sci-delivery-building"]').val() + ' ';
  }
  if (soBlock.find('[name="sci-delivery-apartment"]').val()) {
    uAddress += 'кв. ' + soBlock.find('[name="sci-delivery-apartment"]').val() + ' ';
  }
  uDeliveryDate = soBlock.find('[name="sci-delivery-date"]').val();
  uDeliveryPeriod = soBlock.find('[name="sci-delivery-time"]').val();
  uDeliveryTime = ($('.sci-delivery-tab:not(.rb_so__hide)').find('.sci-delivery__radio:checked').parent().find(
    '#sci-delivery-time option:selected').text() || ''
  ).trim();

  deliveryPrice = '0';
  totalPrice = '0';
  if ([3, 4].indexOf(app.shopcart._step) !== -1) {
    soModule.find('.sale_order_full tfoot tr').each(function () {
      if ($(this).find('td').eq(0).find('b').is('b')) {
        if ($(this).find('td').eq(0).find('b').html().toString() === 'Доставка:') {
          deliveryPrice = $(this).find('td').eq(1).html().toString().replace('руб.', '');
        }
        if ($(this).find('td').eq(0).find('b').html().toString() === 'Итого:') {
          totalPrice = $(this).find('td').eq(1).find('b').html().toString().replace('руб.', '');
        }
      }
    });
  } else {
    if ($(document).find('#sale-order-full-price').length > 0) {
      totalPrice = $(document).find('#sale-order-full-price').html().replace('руб.', '');
    }
  }
  if (soModule.find('.sale_order_full tfoot #sale-order-full-delivery-price').length > 0) {
    deliveryPrice = soModule.find('.sale_order_full tfoot #sale-order-full-delivery-price').html().toString().replace(
      'руб.', '');
  }
  uDeliveryPeriod = soModule.find('[for="ID_DELIVERY_ID_6"] .so_delivery_period').html();
  if (soModule.find('#ID_DELIVERY_ID_6').prop('checked')) {
    if (!isSideInfoInited) {
      if (soModule.find('#PERSON_TYPE_2').prop('checked')) {
        uAddress = soModule.find('[name="ORDER_PROP_37"]').val();
      } else {
        uAddress = soModule.find('[name="ORDER_PROP_36"]').val();
      }
    } else {
      uAddress = '';
      if (
        typeof window.IPOLSDEK_pvz !== 'undefined'
        && window.IPOLSDEK_pvz.pvzId
      ) {
        uCity = '';
        uAddress = window.IPOLSDEK_pvz.pvzAdress;
      }
    }

    uDeliveryDate = '';
    uDeliveryPeriod = '';
    uDeliveryPeriod = soModule.find('[for="ID_DELIVERY_ID_6"] .so_delivery_period').html();
    if (uAddress) {
      soBlock.find('.pickup_address span').html(uAddress);
    }
    soBlock.find('.pickup_summ span').html(deliveryPrice + '₽');
    soBlock.find('.pickup_date span').html(uDeliveryPeriod);
  }
  if (soModule.find('#ID_DELIVERY_ID_13').prop('checked')) {
    uAddress = $(document).find('label[for="ID_DELIVERY_ID_13"] .address_soa').html();
    deliveryPrice = $(document).find('label[for="ID_DELIVERY_ID_13"] .prs_soa').html().replace('руб.', '');
    uDeliveryPeriod = $(document).find('label[for="ID_DELIVERY_ID_13"] .so_delivery_period').html();
    soBlock.find('.js-shop-address').html($(document).find('label[for="ID_DELIVERY_ID_13"] .address_soa').html());
    soBlock.find('.js-shop-schedule').html($(document).find('label[for="ID_DELIVERY_ID_13"] .schedule_soa').html());
    soBlock.find('.sv_price span').html(
      $(document).find('label[for="ID_DELIVERY_ID_13"] .prs_soa').html().replace('руб.', '') + '₽');
    soBlock.find('.sv_time span').html($(document).find('label[for="ID_DELIVERY_ID_13"] .so_delivery_period').html());
  }
  soBlock.find('.pickup_summ_alt span').html(deliveryPrice + '₽');
  soBlock.find('.shopcart-sidebar__delivery-price span').html(deliveryPrice);
  soBlock.find('#total_price_cart').html(totalPrice);
  if (soModule.find('#sale-order-full-price').length > 0) {
    soBlock.find('#cart-price').html(
      soModule.find('#sale-order-full-price').html().toString().replace('руб.', ''),
    );
  }
  if (soModule.find('#sale-order-full-discount-price').length > 0) {
    soBlock.find('.shopcart-sidebar__sum-price--sale').removeClass('sc-hidden');
    soBlock.find('#cart-discount-price').html(
      soModule.find('#sale-order-full-discount-price').html().toString().replace('руб.', ''),
    );
  } else {
    soBlock.find('.shopcart-sidebar__sum-price--sale').addClass('sc-hidden');
  }
  if (soModule.find('#sale-order-full-delivery-price').length > 0) {
    soBlock.find('#cart-delivery-price').html(
      soModule.find('#sale-order-full-delivery-price').html().toString().replace('руб.', ''),
    );
  }

  uDeliveryDate = ($('.sci-delivery-tab:not(.rb_so__hide)').find('.sci-delivery__radio:checked').parent().find(
    '#sci-delivery-date').val() || ''
  ).trim();

  var currentDelivery = $(document).find('.sale_order_full_table.delivery-block input[type=\'radio\']:checked');
  var currentDeliveryId = currentDelivery.length > 0 ? parseInt(currentDelivery.val()) : 0;

  if (app.deliveryService.in(currentDeliveryId, ["ownPickup", "cdekPickup"])) {
    soBlock.find('.shopcart-sidebar__delivery-title').html("Самовывоз:");
  } else {
    soBlock.find('.shopcart-sidebar__delivery-title').html("Доставка:");
  }

  uAddress = uCity + uAddress;
  soBlock.find('.shopcart-sidebar__delivery-address').html(uAddress);

  isSideInfoInited = true;

  $.fn.toggleDeliveryPriceInfoVisibility();

  soBlock.find('.shopcart-sidebar__delivery-date span').html(uDeliveryDate);
  soBlock.find('.shopcart-sidebar__delivery-date')[uDeliveryDate === '' ? 'hide' : 'show']();
  soBlock.find('.shopcart-sidebar__delivery-time span').html(uDeliveryTime);
  soBlock.find('.shopcart-sidebar__delivery-time')[uDeliveryTime === '' ? 'hide' : 'show']();

  if (app.deliveryService.is(currentDeliveryId, "ownDelivery")) {
    soBlock.find('.shopcart-sidebar__delivery-date').closest(".shopcart-sidebar__text").show();
  } else {
    soBlock.find('.shopcart-sidebar__delivery-date').closest(".shopcart-sidebar__text").hide();
  }
};

$.fn.updateShopcartAmount = function () {
  var PRODUCT_DECLENSTION = ['товар', 'товара', 'товаров'];
  var totalProps = $('.sale_order_full[data-total-props]').data('total-props') || {};

  $('.js-shopcart-amount').html(
    totalProps.totalQuantity + ' ' + app.utils.pluralize(totalProps.totalQuantity, PRODUCT_DECLENSTION));
  $('#cart_count_prod').text(totalProps.totalQuantity);
};

$.fn.updateShopcartSidebarProducts = function () {
  var tmplHtml = $('#tmpl-shopcart-sidebar-product').html(),
    $sidebarProductList = $('.js-shopcart-sidebar-product-list');
  var currentDelivery = $(document).find('.sale_order_full_table.delivery-block input[type=\'radio\']:checked');
  var currentPaySystem = $(document).find('.sale_order_full_table.paySystem-block input[type=\'radio\']:checked');
  var currentDeliveryId = currentDelivery.length > 0 ? parseInt(currentDelivery.val()) : 0;
  var currentPaySystemId = currentPaySystem.length > 0 ? parseInt(currentPaySystem.val()) : 0;
  Mustache.parse(tmplHtml);
  $sidebarProductList.empty();

  $('.sale_order_full tr[data-props]').each(function () {
    var props = $(this).data('props');

    for (var key in props) {
      if (props.hasOwnProperty(key) && typeof props[key] === 'string') {
        props[key] = props[key].replace(/&amp;/g, '&').replace(/&quot;/g, '"').replace(/&#039;/g, '\'').replace(/&lt;/g,
          '<').replace(/&gt;/g, '>');
      }
    }

    var existDeliveryInLoc = $('.sci-delivery__tab[data-prop=\'' + currentDelivery.attr('id') + '\']').length;

    props.onlyCash = props.onlyCash && $.fn.isMoscow();
    props.onlyPickup = props.onlyPickup && existDeliveryInLoc
        && currentDeliveryId && !app.deliveryService.in(currentDeliveryId, ["ownPickup", "cdekPickup"]);
    props.onlyPrepayment = props.onlyPrepayment && currentPaySystemId
        && [4, 9].indexOf(currentPaySystemId) <= -1;

    if (props.sum.toString().indexOf("руб.") >= 0) {
      props.sum = props.sum.toString().replace('руб.', '₽');
    }

    if (props.oldSum.toString().indexOf("руб.") >= 0) {
      props.oldSum = props.oldSum.toString().replace('руб.', '₽');
    }

    $sidebarProductList.append(Mustache.render(tmplHtml, props));
  });
};

$.fn.changeRadioButtonSaleOrder = function (l_name) {
  var soBlock,
    soModule;
  soBlock = $(document).find('#so_main_block');
  soModule = $(document).find('#module_so');
  if (soModule.find('[for="' + l_name + '"]').is('label')) {
    soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
    return soModule.find('[for="' + l_name + '"]').click();
  }
};

var getPaymentIcon = function (id) {
  var tmplSelector = '';

  switch (id) {
    case 2: {
      tmplSelector = '#tmpl-payment-icon-ya-money';
      break;
    }
    case 4: {
      tmplSelector = '#tmpl-payment-icon-visa-master';
      break;
    }
    case 7: {
      tmplSelector = '#tmpl-payment-icon-cash';
      break;
    }
    case 9: {
      tmplSelector = '#tmpl-payment-icon-cashless';
      break;
    }
    case 8:
    case 10: {
      tmplSelector = '#tmpl-payment-icon-card';
      break;
    }
  }

  var $tmpl = $(tmplSelector);

  if ($tmpl.length > 0) {
    var tmplHtml = $tmpl.html();

    Mustache.parse(tmplHtml);

    return '<i class="sci-payment__icon">' + Mustache.render(tmplHtml) + '</i>';
  }

  return '';
};

$.fn.updateDateSaleOrder = function () {
  var soCity,
    soCityAlt,
    soCityAltID,
    soCityID;
  var soBlock = $(document).find('#so_main_block');
  var soModule = $(document).find('#module_so');
  var $radioButton = soBlock.find('.rb_so');
  var $soBlockSelectedDelivery = soModule.find('.sale_order_full_table.delivery-block input[type=\'radio\']:checked');

  $('.delivery-pickup-type').removeClass('current-type');

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

  var cityName = soCity.val() ? soCity.val().split(', ')[0] : '';

  $('.sci-delivery__tab').each(function () {
    setDeliveryByLocation($(this));
  });

  var selectDeliveryVal = getDeliveryByCity($soBlockSelectedDelivery.val(), soCityID.val());

  var deliveryTabType = getDeliveryTabType(selectDeliveryVal);

  if (deliveryTabType) {
    var deliveryTab = soBlock.find('#'.concat(deliveryTabType)).siblings('label.sci-delivery__tab.rb_so');
    var selectDelivery = deliveryTab.data('prop', 'ID_DELIVERY_ID_' + selectDeliveryVal).attr('data-prop',
      'ID_DELIVERY_ID_' + selectDeliveryVal);

    if (deliveryTabType === 'sci-delivery-tab2') {
      showDeliveryPickUpContent(selectDelivery);
    }

    setDeliveryByLocation(deliveryTab, true);
  }

  // Обновляем количество товаров в корзине на чекауте
  $.fn.updateShopcartAmount();
  // Обновляем товары в сайдбаре на чекауте
  $.fn.updateShopcartSidebarProducts();

  $radioButton.each(function () {
    var $this = $(this);
    var $thisParent = $this.closest('.sci-delivery-tab');
    var id = $this.data('prop');

    $this.addClass('rb_so_disbled');

    if ($this.hasClass('sci-delivery__tab')) {
      if (id) {
        if (soModule.find('#' + id + '').is('input')) {
          var $soModuleDelivery = soModule.find('label[for="' + id + '"]');

          $this.find('span').text(setDeliveryDescription($soModuleDelivery));
          $thisParent.removeClass('rb_so__hide');

          if (soModule.find('#' + id + '').prop('checked')) {
            $(document).find('#module_so').find('[name=\'isChangeLocation\']').remove();
            $this.click();
          }
        } else {
          $thisParent.addClass('rb_so__hide');
        }
      }
    } else {
      if ($this.hasClass('sci-payment__tab')) {
        $thisParent = $this.closest('.sci-payment-tab');
        if (id) {
          if (soModule.find('#' + id + '').is('input')) {
            var $soModulePayment = soModule.find('label[for="' + id + '"]');
            var paymentTitle = $soModulePayment.find('>b').eq(0).html();

            $this.html(
              paymentTitle +
              getPaymentIcon(parseInt(id.replace('ID_PAY_SYSTEM_ID_', ''))),
            );
            $thisParent.removeClass('rb_so__hide');

            if (soModule.find('#' + id + '').prop('checked')) {
              $this.click();
            }
          } else {
            $thisParent.addClass('rb_so__hide');
          }
        }
      } else {
        if (id) {
          if (soModule.find('#' + id + '').is('input')) {
            if (soModule.find('#' + id + '').prop('checked')) {
              $this.click();
            }
          }
        }
      }
    }

    $this.removeClass('rb_so_disbled');
  });

  soModule.find('.sale_order_full_table input[name="PAY_SYSTEM_ID"]').each(function () {
    var paymentID,
      htmlNewEl,
      indLav,
      titleDeliv;
    paymentID = $(this).attr('id');
    if (!paymentID) {
      return;
    }
    if (!soBlock.find('.sci-payment-tabs .rb_so[data-prop="' + paymentID + '"]').is('label')) {
      indLav = parseInt(soBlock.find('.sci-payment-tabs .sci-payment-tab').length) + 1;
      titleDeliv = soModule.find('label[for="' + paymentID + '"]>b').eq(0).html();
      htmlNewEl = $('<li class="sci-payment-tab">');
      htmlNewEl.append(
        '<input id="sci-payment-tab' + indLav + '" type="radio" name="payment-tabs" class="sci-payment__radio visually-hidden" value="' + indLav + '">');
      htmlNewEl.append('' +
        '<label class="sci-payment__tab rb_so" data-prop="' + paymentID + '" for="sci-payment-tab' + indLav + '">' +
        titleDeliv +
        getPaymentIcon(parseInt(paymentID.replace('ID_PAY_SYSTEM_ID_', ''))) +
        '</label>',
      );
      soBlock.find('.sci-payment-tabs').append(htmlNewEl);
      if ($(this).prop('checked')) {
        soBlock.find('.rb_so[data-prop="' + paymentID + '"]').click();
      }
    }
  });

  var fizPerson = soModule.find('#PERSON_TYPE_1').prop('checked');

  $(document).find('#so_main_block').find('[data-prop=ORDER_PROP_19],[data-prop=ORDER_PROP_21]').each(function () {
    var curProp = fizPerson ? $(this).attr('data-prop') : $(this).attr('data-prop-alt');
    if (soModule.find('[name=\'' + curProp + '\']').length > 0) {
      $(this).closest('.sci-contact__field').show();
    } else {
      $(this).closest('.sci-contact__field').hide();
    }
  });

  soBlock.find('input, textarea, select').each(function () {
    if ($(this).attr('data-change') !== 'Y' && $(this).attr('data-prop')) {
      if ($(this).is('#sci-delivery-date') || $(this).is('#sci-delivery-time')) {
        return true;
      }

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
          $(this).find(
            'option[value="' + soModule.find('[name="' + $(this).attr('data-prop') + '"]').val() + '"]').prop(
              'selected', true);
          return $(this).attr('data-change', 'Y');
        }
      }
    }
  });

  $('#sci-delivery-street').suggestions('setOptions', {
    constraints: {
      locations: {
        city: cityName,
      },
    },
  });

  if (soCityID.length > 0) {
    // Обновляем город в шапке
    locationDND.changeCity({
      title: cityName,
      id: parseInt(soCityID.val()),
    }, false);
  }

  $.fn.updateSideInfo();

  //Индикатор бесплатной доставки
  var ownDeliveryTabProp = "ID_DELIVERY_ID_" + app.deliveryService.get("ownDelivery");
  var ownDeliveryCount = $(document).find(".sci-delivery__tab.rb_so[data-prop='" + ownDeliveryTabProp + "']").length;
  if ($(document).find('#so_main_block #cart-price').length) {
    var basketPrice = parseInt($(document).find('#so_main_block #cart-price').html().replace(/\D+/g, ''));
  }

  $('.cond_free_delivery_style').remove();

  if (window.COND_FREE_DELIVERY > 0 && ownDeliveryCount) {
    if (window.COND_FREE_DELIVERY - basketPrice >= 0) {
      var percent = Math.round(basketPrice / window.COND_FREE_DELIVERY * 100);
      $('head').append('<style class="cond_free_delivery_style">.shopcart-sidebar__free-delivery-line::before{width:' + percent + '% !important;}</style>');
      $('.free-delivery-remains').html(window.COND_FREE_DELIVERY - basketPrice);
      $('.shopcart-sidebar__free-delivery.not_enough').show();
      $('.shopcart-sidebar__free-delivery.allright').hide();
    } else {
      $('head').append('<style class="cond_free_delivery_style">.shopcart-sidebar__free-delivery-line::before{width:100% !important;}</style>');
      $('.shopcart-sidebar__free-delivery.not_enough').hide();
      $('.shopcart-sidebar__free-delivery.allright').show();
    }
  } else {
    $('.shopcart-sidebar__free-delivery.not_enough').hide();
    $('.shopcart-sidebar__free-delivery.allright').hide();
  }

  if (
    typeof window.IPOLSDEK_pvz !== 'undefined'
    && window.IPOLSDEK_pvz.pvzId
  ) {
    soBlock.find('.pickup_address span').removeClass('is-error');
  }

  soModule.find('.errortext').each(function () {
    return $.fn.setPushUp('Ошибка', $(this).text(), false, 'message', false, 5000, undefined, 'push_up_item--error');
  });

  // Обновляем маску телефона
  soBlock.find(fizPerson ? 'input[name="sci-contact__tel"]' : 'input[name="sci-contact__ur-phone"]').unmask().mask(
    '+7 (999) 999-99-99');

    soBlock.find('.preloaderCatalog').removeClass('preloaderCatalogActive');
    app.deliveryAddress.checkPreFilled();
    return true;
};

$.fn.updateCartHeader = function () {
  var cartSum,
    prodCount;
  if ($(document).find('.shopcart-tab[for="shopcart-tab1"]').is('label')) {
    prodCount = $(document).find('#cart_count_prod').html();
    cartSum = $(document).find('#cart_sum_prod').html();
    return $(document).find('.shopcart-tab[for="shopcart-tab1"] span').html(
      prodCount + ' товара, ' + cartSum + ' руб.');
  }
};

$.fn.refreshMiniCart = function () {
  return $.ajax({
    url: '/ajax/add_to_cart.php',
    type: 'POST',
    data: {
      METHOD_CART: 'refredh_mini_cart',
      AJAX_MIN_CART: 'Y',
    },
    success: function (data) {
      return $.fn.updateMiniCart(data);
    },
  });
};

$.fn.refreshCart = function () {
  return $.ajax({
    url: '/ajax/add_to_cart.php',
    type: 'POST',
    data: {
      METHOD_CART: 'refredh_cart',
      AJAX_CART: 'Y',
    },
    success: function (data) {
      return $.fn.updateCart(data);
    },
  });
};

// TODO: vuex? это какой-то пздц
$.fn.updateMiniCompare = function (data) {
  var emptyMiniCompareClass = 'preview-heart--empty';
  var $miniCompare = $(document).find('#mini_compare_header');
  var $miniCompareCounter = $(document).find('#mini_compare_header_counter');
  var $mobileCompareLink = $(document).find('.top-nav__link.top-nav__link--compare');
  var $ft = $('<div></div>').append(data);

  if ($ft.find('.preview-prod').length > 0) {
    $miniCompare.removeClass(emptyMiniCompareClass);
    if (!$miniCompareCounter.attr('href')) {
      $miniCompareCounter.attr('href', '/catalog/compare/');
    }
    if (!$mobileCompareLink.attr('href')) {
      $miniCompareCounter.attr('href', '/catalog/compare/');
    }
  } else {
    $miniCompareCounter.removeAttr('href');
    $mobileCompareLink.removeAttr('href');
    $miniCompare.addClass(emptyMiniCompareClass);
  }

  $miniCompareCounter.html($ft.find('#mini_compare_header_counter').html());
  window.gtmActions.setProducts($(data).find('[data-gtm-products]').data('gtm-products'));
  return $miniCompare.html($ft.find('#mini_compare_header').html());
};

$.fn.updateMiniFavorite = function (data) {
  var emptyMiniFavoriteClass = 'preview-heart--empty';
  var $miniFavorite = $(document).find('#mini_favorite_header');
  var $miniFavoriteCounter = $(document).find('#mini_favorite_header_counter');
  var $ft = $('<div></div>').append(data);

  if ($ft.find('.preview-prod').length > 0) {
    $miniFavorite.removeClass(emptyMiniFavoriteClass);
    if (!$miniFavoriteCounter.attr('href')) {
      $miniFavoriteCounter.attr('href', '/user/favorite/');
    }
  } else {
    $miniFavoriteCounter.removeAttr('href');
    $miniFavorite.addClass(emptyMiniFavoriteClass);
  }

  $miniFavoriteCounter.html($ft.find('#mini_favorite_header_counter').html());
  window.gtmActions.setProducts($(data).find('[data-gtm-products]').data('gtm-products'));
  return $miniFavorite.html($ft.find('#mini_favorite_header').html());
};

$.fn.updateMiniCart = function (data) {
  var emptyMiniCartClass = 'preview-shopcart--empty';
  var $miniCart = $(document).find('#mini_cart_header');
  var $miniCartCounter = $(document).find('#mini_cart_header_counter');
  var $ft = $('<div></div>').append(data);

  if ($ft.find('.preview-prod').length > 0) {
    $miniCart.removeClass(emptyMiniCartClass);
    $miniCartCounter.addClass('top-personal__cart--full');
    $miniCartCounter.attr('href', '/cart/');
  } else {
    $miniCart.removeClass("preview-card--show").addClass(emptyMiniCartClass);
    $miniCartCounter.removeClass('top-personal__cart--full');
    $miniCartCounter.removeAttr('href');
  }

  $miniCartCounter.html($ft.find('#mini_cart_header_counter').html());
  window.gtmActions.setProducts($(data).find('[data-gtm-products]').data('gtm-products'));
  return $miniCart.html($ft.find('#mini_cart_header').html());
};

$.fn.updGlobalCityInCart = function (cityID) {
  var soBlock;
  var soModule;
  soBlock = $(document).find('#so_main_block');
  soModule = $(document).find('#module_so');
  if (soModule.is('div')) {
    if (soModule.find('#PERSON_TYPE_1').prop('checked')) {
      soModule.find('[name="ORDER_PROP_18"]').val(cityID);
    } else {
      soModule.find('[name="ORDER_PROP_25"]').val(cityID);
    }

    var orderForm = soModule.find('#ORDER_FORM');

    if (orderForm.find('[name=\'isChangeLocation\']').length <= 0) {
      orderForm.prepend('<input type=\'hidden\' name=\'isChangeLocation\' value=\'Y\'>');
    }
    soModule.find('input[name="DELIVERY_ID"]:checked').prop('checked', false);
    soBlock.find('.sci-delivery__radio:checked').prop('checked', false);
    if ($(document).find('.shopcart-nav1__radio:checked').data('num') > 3) {
      $(document).find('.shopcart-nav1 label[for="shopcart-tab3"]').click();
    }
    return $.fn.updateCart();
  }
};

$.fn.updateCart = function (data) {
  var soBlock;
  soBlock = $(document).find('#so_main_block');
  $(document).find('#shopcart-item1').html(data);
  soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive');
  return submitForm();
};

$.fn.updateProductDeliveries = function () {
  var $productDeliveryBlock = $(document).find('.product-sidebar__delivery .js-delivery_block');
  var $productPaySystemsBlock = $(document).find('.product-sidebar__payment-methods');

  if ($productDeliveryBlock.is('div')) {
    $.ajax({
      url: '',
      data: {
        ajax: 'Y',
      },
      success: function (result) {
        $(document).find('.preloaderCatalog').removeClass('preloaderCatalogActive');
        $productDeliveryBlock.html(
          $(result).find('.product-sidebar__delivery .js-delivery_block').html(),
        );
        $productPaySystemsBlock.html(
          $(result).find('.product-sidebar__payment-methods').html(),
        );
      },
    });
  }
};

$.fn.offersByPropData = {};

$.fn.checkPropParams = function () {
  var $activeCount,
    $activePropObj,
    dataJSON;
  dataJSON = $(document).find('.offers_by_prop_json').attr('data-json');
  $.fn.offersByPropData = JSON.parse(dataJSON);
  $(document).find('.offers_prop .offer_prop_item').removeClass('propDisabled');
  $activeCount = $(document).find('.offers_prop .offer_prop_item.active').length;
  $activePropObj = {};
  return $(document).find('.offers_prop .offer_prop_item.active').each(function () {
    var filtResultByProp,
      ind,
      itemID,
      newURL,
      propCode,
      results;
    propCode = $(this).attr('data-prop-code');
    itemID = $(this).attr('data-id');
    $activePropObj[propCode] = itemID;
    if (!--$activeCount) {
      $(document).find('.offers_prop .offer_prop_item').each(function () {
        var filtResult,
          itemIDAlt,
          propCodeAlt;
        propCodeAlt = $(this).attr('data-prop-code');
        itemIDAlt = $(this).attr('data-id');
        filtResult = $.fn.offersByPropData.filter((
          function (_this) {
            return function (item) {
              var ind,
                resBool;
              if (Object.keys(item.props).length <= 0) {
                return false;
              }
              resBool = true;
              var match = false;
              for (ind in $activePropObj) {
                if (ind !== propCodeAlt) {
                  if (item.props[ind]) {
                    if (item.props[ind].id !== $activePropObj[ind] && !match) {
                      resBool = false;
                    } else {
                      if ($('.offers_prop').length > 2) {
                        match = true;
                      }
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

              // if (resBool){
              //     for (ind in $activePropObj) {
              //         if (item.props[ind].id !== $activePropObj[ind]){
              //             resBool = false;
              //         }
              //     }
              // }

              return resBool;
            };
          }
        )(this));
        if (filtResult.length < 1) {
          return $(this).addClass('propDisabled');
        }
      });

      var allOffers = $.fn.offersByPropData;

      $(document).find('.offers_prop .offer_prop_item.active.propDisabled').each(function (index, elem) {
        $(elem).removeClass('active');
        $(elem).siblings('.offer_prop_item').not('.propDisabled').first().addClass('active');
      });
      $activePropObj = {};
      $(document).find('.offers_prop .offer_prop_item.active').not('.propDisabled').each(function () {
        propCode = $(this).attr('data-prop-code');
        itemID = $(this).attr('data-id');
        $activePropObj[propCode] = itemID;
      });

      $(document).find('.offers_prop .offer_prop_item').not('.active').each(
        function (activePropObj, allOffers, index, elem) {
          propCode = $(elem).attr('data-prop-code');
          itemID = $(elem).attr('data-id');
          var cloneAllOffers = Object.assign({}, allOffers);
          var cloneActivePropObj = Object.assign({}, activePropObj);

          cloneActivePropObj[propCode] = itemID;

          if (propCode !== 'HDD' && $(document).find('.offers_prop[data-code="graphics_mac"]').length > 0) {
            for (var activePropCode in cloneActivePropObj) {
              for (var allOfferKey in cloneAllOffers) {
                if (
                  cloneAllOffers.hasOwnProperty(allOfferKey)
                  && cloneAllOffers[allOfferKey].props.hasOwnProperty(activePropCode)
                  && cloneActivePropObj.hasOwnProperty(activePropCode)
                ) {
                  if (cloneAllOffers[allOfferKey].props[activePropCode].id !== cloneActivePropObj[activePropCode]) {
                    delete cloneAllOffers[allOfferKey];
                  }
                }
              }

            }
          }

          if (Object.keys(cloneAllOffers).length <= 0) {
            $(elem).removeClass('active').addClass('propDisabled');
          }

        }.bind(this, $activePropObj, allOffers),
      );

      filtResultByProp = $.fn.offersByPropData.filter((
        function (_this) {
          return function (item) {
            var ind,
              resBool;
            if (Object.keys(item.props).length <= 0) {
              return false;
            }
            resBool = true;
            var match = false;
            for (ind in $activePropObj) {
              if (item.props[ind]) {
                if (item.props[ind].id !== $activePropObj[ind] && !match) {
                  resBool = false;
                } else {
                  if ($('.offers_prop').length > 2) {
                    match = true;
                  }
                }
              } else {
                resBool = false;
              }
            }

            if (resBool) {
              for (ind in $activePropObj) {
                if (item.props[ind].id !== $activePropObj[ind]) {
                  resBool = false;
                }
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
        $(document).find('.js-one-click-order .js-product-id').val(filtResultByProp[0].id_offer);
        if ($(document).find('.goToFcnCart[data-id="' + filtResultByProp[0].id_offer + '"]').is('a')) {
          $(document).find('.addToCartBtn_mainPage').addClass('dsb-hidden');
          $(document).find('.goToFcnCart[data-id="' + filtResultByProp[0].id_offer + '"]').removeClass('dsb-hidden');
        } else {
          $(document).find('.addToCartBtn_mainPage').removeClass('dsb-hidden');
        }
        $(document).find('.BOC_btn').attr('data-id', filtResultByProp[0].id_offer);
        $(document).find('.addToCartBtn').attr('data-id', filtResultByProp[0].id_offer);
        $(document).find('.mainBlockPrice .product-sidebar__total-price-price').html(
          formatMoney(filtResultByProp[0].new_price));
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
              $(document).find('.offersPropertiesList').append(
                '<p class="product-content__value">' + filtResultByProp[0].props[ind].title + '</p>'));
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

$.fn.ajaxLoadCatalog = function () {
  var $data,
    countOnPage,
    filtParamCount,
    sort_by,
    styleBlock,
    urlForSend;
  filtParamCount = $(document).find('.cb-filter .cb-filter__param').length;
  if (filtParamCount > 0) {
    $(document).find('.cb-filter .cb-filter__clear').removeClass('dnd-hide');
  } else {
    $(document).find('.cb-filter .cb-filter__clear').addClass('dnd-hide');
  }
  $(document).find('.preloaderCatalog').addClass('preloaderCatalogActive');
  urlForSend = $(document).find('.ajaxPageNav .cb-nav-pagination__item.active').attr('data-href');
  styleBlock = 'v-block';
  countOnPage = $(document).find('select[name="countOnPage"]').val();
  sort_by = $(document).find('select[name="sort_by"]');
  $(document).find('.cb-nav-style__block input[name="style"]').each(function () {
    if ($(this).prop('checked')) {
      return styleBlock = $(this).attr('id');
    }
  });
  $data = {
    ajaxCal: 'Y',
    styleBlock: styleBlock,
    countOnPage: countOnPage,
    sort_by: sort_by.val(),
  };
  $(document).find('.cb-filter .cb-filter__param input').each(function () {
    $data['set_filter'] = 'Y';
    return $data[$(this).attr('name')] = $(this).val();
  });
  if (urlForSend) {
    return $.ajax({
      url: urlForSend,
      type: 'GET',
      data: $data,
      success: function (data) {
        if (history.pushState && sort_by.val()) {
          var urlObj = new URL(window.location.protocol + "//" + window.location.host + window.location.pathname + window.location.search);

          if (sort_by.find("option.default").val() !== sort_by.val()) {
            urlObj.searchParams.set("sort_by", sort_by.val());
            history.pushState(null, null, urlObj.href);
          } else if (urlObj.searchParams.has("sort_by")) {
            urlObj.searchParams.delete("sort_by");
            history.pushState(null, null, urlObj.href);
          }
        }
        $(document).find('.preloaderCatalog').removeClass('preloaderCatalogActive');
        if ($(document).find(".catalog-filter").length && $(data).siblings(".catalog-filter").length) {
          $(document).find(".catalog-filter").html($(data).siblings(".catalog-filter").html());
        }

        $(document).find('#PROPDS_BLOCK').html($(data).find("#PROPDS_BLOCK").html());
        try {
          window.gtmActions.setProducts($(data).find('[data-gtm-products]').data('gtm-products'));
          var gtmData = $(data).find('[data-gtm-data]').data('gtm-data');
          gtmData.event = 'update';
          gtmActions.initCommonData(gtmData);
        } catch (e) {
        }
        return $(document).find('.catTopCount .catTopCountValue').html(
          $(document).find('#PROPDS_BLOCK .catTopCountValue').html());
      },
    });
  }
};

$.fn.checkCartSlide = function (numSlide) {
  var $count,
    $formIsValid,
    sBlock;
  if (numSlide === 1) {
    return true;
  } else {
    if (numSlide === 2) {
      if ($(document).find('#sci-contact-tab1').prop('checked')) {
        sBlock = $(document).find('#sci-contact-content1');
      } else {
        sBlock = $(document).find('#sci-contact-content2');
      }
      $count = sBlock.find('input').length;
      $formIsValid = true;
      sBlock.find('input').each(function () {
        if (!$(this).val() && $(this).prop('required')) {
          $formIsValid = false;
        }
        if (!--$count) {
          return $formIsValid;
        }
      });
    } else {
      if (numSlide === 3) {
        $formIsValid = true;
        if ($(document).find('#sci-delivery-tab1').prop('checked') || $(document).find('#sci-delivery-tab3').prop(
          'checked') ||
          $(document).find('#sci-delivery-tab4').prop('checked') || $(document).find('#sci-delivery-tab5').prop(
            'checked') ||
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
    }
  }
  return false;
};

$.fn.updateURLParameter = function (url, param, paramVal) {
  var tmpAnchor;
  var TheParams;
  var TheAnchor,
    TheParams,
    additionalURL,
    baseURL,
    i,
    newAdditionalURL,
    rows_txt,
    temp,
    tempArray,
    tmpAnchor;
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

function isUserExists(email) {
  var existUser = false;

  if (email.length <= 0) {
    return existUser;
  }

  return existUser;
}

function getDeliveryTabType(deliveryId) {
  var deliveryTabType = null;
  deliveryId = parseInt(deliveryId);

  if (app.deliveryService.in(deliveryId, ["cdekDelivery", "ownDelivery", "ownDeliveryRegion"])) {
    deliveryTabType = 'sci-delivery-tab1';
  }

  if (app.deliveryService.in(deliveryId, ["ownPickup", "cdekPickup"])) {
    deliveryTabType = 'sci-delivery-tab2';
  }

  return deliveryTabType;
}

function formatMoney(number) {
  return Number(number).toLocaleString('ru-RU');
}

// Скролл к отзывам на странице товара
(function () {
  $(document).ready(function () {
    var $reviewsTab = $('[data-product-tab="reviews"]');

    $(document).on('click', '[data-scroll-to-product-tab="reviews"]', function (event) {
      if ($reviewsTab.is(':radio')) {
        $reviewsTab.trigger('click');
      }

      $('html, body').animate({ scrollTop: $reviewsTab.parent().offset().top - 100 }, 300);
      event.preventDefault();
    });
  });
})();

(function () {
  $(document).ready(function () {
    var block = $('.catTopCount');

    if ($('.cb-nav-count__total').html() > 12) {
      block.removeClass('visually-hidden');
    }
  });
})();

window.gtmActions = {
  currency: '',
  products: [],
  setCurrency: function (currency = '') {
    this.currency = currency || '';
  },
  getCurrency: function () {
    return this.currency;
  },
  setProducts: function (products) {
    if (!Array.isArray(products)) {
      products = [];
    }

    products = products.map(function (item) {
      item.id = Number(item.id);
      return item;
    });

    products = products.filter(function (item) {
      var exist = this.products.findIndex(function (existItem) {
        return Number(existItem.id) === Number(item.id);
      }) + 1;

      return exist <= 0;
    }, this,
    );

    this.products = this.products.concat(products);
  },
  getProduct: function (productId) {
    return this.products.find(function (item) {
      return Number(item.id) === productId;
    });
  },
  getProducts: function (productsId) {
    productsId = productsId.map(function (item) {
      return Number(item);
    });

    return this.products.filter(function (item) {
      return productsId.indexOf(item.id) + 1 > 0;
    });
  },
  productClickHandler: function () {
    var items = [];
    var product = gtmActions.getProduct(Number($(this).data('product-id')));
    if (product) {
      items.push(product);
    }
    var eventObj = {
      event: 'productClick',
      eventData: {
        currency: gtmActions.getCurrency(),
        source: $(this).data('product-list'),
        items: items,
      },
    };

    gtmActions.initCommonData(eventObj);
  },
  addToCartHandler: function (productId, source) {
    var items = [];
    var product = gtmActions.getProduct(Number(productId));
    if (product) {
      items.push(product);
    }

    var eventObj = {
      event: 'addToCart',
      eventData: {
        currency: gtmActions.getCurrency(),
        source: source,
        items: items,
      },
    };

    gtmActions.initCommonData(eventObj);
  },
  purchaseHandler: function (transaction) {
    var eventObj = {
      event: 'purchase',
      transaction: transaction,
    };

    gtmActions.initCommonData(eventObj);
  },
  removeFromCartHandler: function (productsId) {
    if (!Array.isArray(productsId)) {
      productsId = [productsId];
    }

    var products = gtmActions.getProducts(productsId);

    var eventObj = {
      event: 'removeFromCart',
      eventData: {
        currency: gtmActions.getCurrency(),
        source: 'cart',
        items: products,
      },
    };

    gtmActions.initCommonData(eventObj);
  },
  changeWishListHandler: function (productsId, miniFavorite, clearAll = false) {
    var eventType = this.getWishListEvent(productsId, miniFavorite, clearAll);

    if (eventType === false) {
      return;
    }

    if (!Array.isArray(productsId)) {
      productsId = [productsId];
    }

    var products = gtmActions.getProducts(productsId);

    var eventObj = {
      event: eventType,
      eventData: {
        currency: gtmActions.getCurrency(),
        source: 'favorite',
        items: products,
      },
    };

    gtmActions.initCommonData(eventObj);
  },
  getWishListEvent: function (productsId, miniFavorite, clearAll) {
    if (clearAll) {
      return 'removeFromWishList';
    } else {
      var favoriteList = $(miniFavorite).find('.preview-prod-bottom__button-favorite').map(function () {
        return Number($(this).data('cart-item'));
      }).get();

      if (Array.isArray(productsId)) {
        if (!productsId.length) {
          return false;
        }
        productsId = productsId[0];
      }

      if (favoriteList.indexOf(Number(productsId)) + 1 > 0) {
        return 'addToWishList';
      } else {
        return 'removeFromWishList';
      }
    }
  },
  initCommonData: function (data) {
    if (typeof data !== 'object' || Object(data).length <= 0) {
      return false;
    }

    window.dataLayer = window.dataLayer || [];
    dataLayer.push(data);
  },

  setCheckoutStep: function (step) {
    var steps = {
      contacts: {
        step: 1,
        option: 'Contacts',
      },
      delivery: {
        step: 2,
        option: 'Delivery',
      },
      payment: {
        step: 3,
        option: 'Payment',
      },
    };

    if (!steps.hasOwnProperty(step)) {
      return false;
    }

    var gtmData = steps[step];
    var eventObj = {};

    eventObj.event = "update";
    eventObj.pageType = "checkout";
    eventObj.checkout = gtmData;
    this.initCommonData(eventObj);
  },
};

function onloadRecaptcha() {
    $(document).ready(function () {
        var oneClickGR = document.querySelector(".js-one-click-order #one-click-recaptcha");
        var callSendGR = document.querySelector("#popap-call form #call-recaptcha");

        if (callSendGR && $(callSendGR).closest("form").length) {
            var gRecaptchaIdCall = grecaptcha.render(callSendGR, {
                'sitekey': '6Leh9rQcAAAAAA41AoMbs93HfbzkBZ8NU4Ac8Fcr',
                'callback': app.formSender.call,
                'size': "invisible",
                'expired-callback	': app.loader.hide,
                'expired-error-callback	': app.loader.hide
            });

            if (gRecaptchaIdCall) {
                $(callSendGR).closest("form").data("g-recaptcha-id", gRecaptchaIdCall);
            }
        }

        if (oneClickGR && $(oneClickGR).closest("form").length) {
            var gRecaptchaIdOneClick = grecaptcha.render(oneClickGR, {
                'sitekey': '6Leh9rQcAAAAAA41AoMbs93HfbzkBZ8NU4Ac8Fcr',
                'callback': app.formSender.oneClick,
                'size': "invisible",
                'expired-callback	': app.loader.hide,
                'expired-error-callback	': app.loader.hide,
            });

            if (gRecaptchaIdOneClick) {
                $(oneClickGR).closest("form").data("g-recaptcha-id", gRecaptchaIdOneClick);
            }
        }
    });
}

