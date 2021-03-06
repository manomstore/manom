(
  function(ELEMENT) {
    ELEMENT.matches = ELEMENT.matches || ELEMENT.mozMatchesSelector || ELEMENT.msMatchesSelector || ELEMENT.oMatchesSelector || ELEMENT.webkitMatchesSelector;
    ELEMENT.closest = function closest(selector) {
      if (!this) {
        return null;
      }
      if (this.matches(selector)) {
        return this;
      }
      if (!this.parentElement) {return null}
      else {
        return this.parentElement.closest(selector)
      }
    };
  }(Element.prototype)
);

var locationDND;
var bodyPage = document.querySelector('body');

locationDND = new Vue({
  el: "#dnd-location",
  data: {
    showPanel: false,
    currentCity: null,
    currentCityID: null,
    cityNotDefined:false,
    listOfCity: [],
    listOfCityDefault: [],
    listOfCityStore: {},
    isInformationStatus: true,
    isPopupChangeCityVisible: false,
    changeCitySearchLine: ''
  },
  created: function() {
    bodyPage.addEventListener('click', this.closePopupCity);
  },
  destroyed: function() {
    bodyPage.removeEventListener('click', this.closePopupCity);
  },
  computed: {
    isConfirmCityVisible: function () {
      return !this.isPopupChangeCityVisible && !this.cityNotDefined;
    },
    isNotDefinedCityVisible: function () {
      return !this.isPopupChangeCityVisible && this.cityNotDefined;
    }
  },
  methods: {
    doShowPanel: function() {
      if (this.showPanel === true) {
        return 'dnd-location-line-show';
      } else {
        return '';
      }
    },
    getUnits: function() {
      var $this;
      this.currentCity = LocationDataDND.cityName;
      this.currentCityID = LocationDataDND.cityID;
      this.cityNotDefined = this.currentCity.length <= 0;
      this.listOfCityDefault = this.listOfCity = LocationDataDND.defaultCityList;
      $this = this;
      setTimeout(function() {
        return $this.isInformationStatus = LocationDataDND.specifyInformation;
      }, 2000);
      return this.showPanel = true;
    },
    currentCityIsActual: function() {
      this.isInformationStatus = true;
      return axios.get('/ajax/location.php', {
        params: {
          location_code: 'changeStatusSpecify'
        }
      });
    },
    closePopupCity: function(evt) {
      var elID = '#dnd-location';

      if (!evt.target.closest(elID)) {
        this.isInformationStatus = true;
        this.isPopupChangeCityVisible = false;
      }
    },
    doChangeCity: function() {
      // TODO: разобраться что за строка присваивается isInformationStatus и для чего
      if (this.isInformationStatus === false && this.isPopupChangeCityVisible) {
        this.isInformationStatus = true;
        this.isPopupChangeCityVisible = false;
        this.changeCitySearchLine = '';

        this.listOfCity = this.listOfCityDefault;
      } else {
        this.isInformationStatus = false;
        this.isPopupChangeCityVisible = true;
      }
    },
    changeCity: function (cityItem, needSubmitForm = true) {
      var _this = this;
      this.isPopupChangeCityVisible = false;
      this.isInformationStatus = true;
      this.changeCitySearchLine = '';

      this.listOfCity = [];
      this.listOfCity = this.listOfCityDefault;
      var cityChanged = cityItem.id !== this.currentCityID;
      this.currentCity = cityItem.title;
      this.currentCityID = cityItem.id;
      if (needSubmitForm) {
          $.fn.updGlobalCityInCart(cityItem.id);
      }

      // Меняем текущий город во всех элементах [data-current-city]
      [].slice.call(document.querySelectorAll('[data-current-city]')).forEach(function(currentCityElement){
        currentCityElement.textContent = _this.currentCity;
      });

        if ($(document).find(".product-sidebar__delivery .js-delivery_block").is("div")) {
            $(document).find('.preloaderCatalog').addClass('preloaderCatalogActive');
        }

        var result = axios.get('/ajax/location.php', {
            params: {
                location_code: 'changeCity',
                cityID: cityItem.id
            }
        }).then(response => ($.fn.updateProductDeliveries()));

        if (
            typeof window.IPOLSDEK_pvz !== 'undefined'
            && window.IPOLSDEK_pvz.pvzId
            && cityChanged
        ) {
            window.IPOLSDEK_pvz.pvzId = false;
            document.getElementById("pvz_address").innerText = "Не выбран"
        }

        var disallowLocBuy = document.querySelector(".js-disallow_loc_buy");
        var allowLocBuy = document.querySelector(".js-allow_loc_buy");
        if (disallowLocBuy) {
            if (parseInt(this.currentCityID) === 84) {
                disallowLocBuy.classList.add("dnd-hide");
                allowLocBuy.classList.remove("dnd-hide");
            } else {
                allowLocBuy.classList.add("dnd-hide");
                disallowLocBuy.classList.remove("dnd-hide");
            }
        }

        return result;
    }
  },
  watch: {
    changeCitySearchLine: function(val) {
      var $this;
      if (val.length >= 2) {
        if (this.listOfCityStore[val + "___str"]) {
          return this.listOfCity = this.listOfCityStore[val + "___str"];
        } else {
          $this = this;
          return axios.get('/ajax/location.php', {
            params: {
              location_code: 'getCityList',
              location_search: val
            }
          }).then(function(response) {
            return $this.listOfCityStore[val + "___str"] = $this.listOfCity = response.data['listOfCity'];
          });
        }
      } else if (val.length === 0) {
        return this.listOfCity = this.listOfCityDefault;
      }
    }
  },
  beforeMount: function() {
    return this.getUnits();
  }
});
