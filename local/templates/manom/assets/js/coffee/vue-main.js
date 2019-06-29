var locationDND;

(function(ELEMENT) {
    ELEMENT.matches = ELEMENT.matches || ELEMENT.mozMatchesSelector || ELEMENT.msMatchesSelector || ELEMENT.oMatchesSelector || ELEMENT.webkitMatchesSelector;
    ELEMENT.closest = function closest(selector) {
        if (!this) return null;
        if (this.matches(selector)) return this;
        if (!this.parentElement) {return null}
        else return this.parentElement.closest(selector)
    };
}(Element.prototype));

locationDND = new Vue({
  el: "#dnd-location",
  data: {
    showPanel: false,
    curentCity: null,
    curentCityID: null,
    listOfCity: [],
    listOfCityDefault: [],
    listOfCityStore: {},
    specifyInformationStatus: true,
    showPopupChangeCity: false,
    changeCitySearchLine: ''
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
      console.log(LocationDataDND);
      this.curentCity = LocationDataDND.cityName;
      this.curentCityID = LocationDataDND.cityID;
      this.listOfCityDefault = this.listOfCity = LocationDataDND.defaultCityList;
      $this = this;
      setTimeout(function() {
        return $this.specifyInformationStatus = LocationDataDND.specifyInformation;
      }, 2000);
      return this.showPanel = true;
    },
    curentCityIsActual: function() {
      this.specifyInformationStatus = true;
      return axios.get('/ajax/location.php', {
        params: {
          location_code: 'changeStatusSpecify'
        }
      });
    },
    closePopupCity: function (evt) {
      console.log(evt.target !== this.$el);

      if (!evt.target.closest("#" + this.$el.id)) {
          this.specifyInformationStatus = true;
          this.showPopupChangeCity = false;
	        document.querySelector('body').removeEventListener('click', this.closePopupCity);
      }
    },
    doChangeCity: function() {
      if (this.specifyInformationStatus === false && this.showPopupChangeCity === true) {
        this.specifyInformationStatus = true;
        this.showPopupChangeCity = false;
        this.changeCitySearchLine = '';
	      document.querySelector('body').removeEventListener('click', this.closePopupCity);
        return this.listOfCity = this.listOfCityDefault;
      } else {
        document.querySelector('body').addEventListener('click', this.closePopupCity);
        this.specifyInformationStatus = false;
        return this.showPopupChangeCity = true;
      }
    },
    changeCity: function(cityItem) {
      this.showPopupChangeCity = false;
      this.specifyInformationStatus = true;
      this.changeCitySearchLine = '';
	    document.querySelector('body').removeEventListener('click', this.closePopupCity);
      this.listOfCity = [];
      this.listOfCity = this.listOfCityDefault;
      this.curentCity = cityItem.title;
      this.curentCityID = cityItem.id;
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
