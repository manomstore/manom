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
    listOfCity: [],
    listOfCityDefault: [],
    listOfCityStore: {},
    isInformationStatus: true,
    isShowPopupCity: false,
    changeCitySearchLine: ''
  },
  created: function() {
    bodyPage.addEventListener('click', this.closePopupCity);
  },
  destroyed: function() {
    bodyPage.removeEventListener('click', this.closePopupCity);
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
        this.isShowPopupCity = false;
      }
    },
    doChangeCity: function() {
      if (!this.isInformationStatus && this.isShowPopupCity) {
        this.isInformationStatus = true;
        this.isShowPopupCity = false;
        this.changeCitySearchLine = '';

        return this.listOfCity = this.listOfCityDefault;
      }

      this.isInformationStatus = false;
      return this.isShowPopupCity = true;
    },
    changeCity: function(cityItem) {
      this.isShowPopupCity = false;
      this.isInformationStatus = true;
      this.changeCitySearchLine = '';

      this.listOfCity = [];
      this.listOfCity = this.listOfCityDefault;
      this.currentCity = cityItem.title;
      this.currentCityID = cityItem.id;
      $.fn.updGlobalCityInCart(cityItem.id);
      return axios.get('/ajax/location.php', {
        params: {
          location_code: 'changeCity',
          cityID: cityItem.id
        }
      });
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
