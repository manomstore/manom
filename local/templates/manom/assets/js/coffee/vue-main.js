var locationDND;

locationDND = new Vue({
  el: "#dnd-location",
  data: {
    showPanel: false,
    curentCity: null,
    cityNotDefined:false,
    curentCityID: null,
    listOfCity: [],
    listOfCityDefault: [],
    listOfCityStore: {},
    specifyInformationStatus: true,
    showPopupChangeCity: false,
    changeCitySearchLine: ''
  },
  computed: {
      showConfirmCity: function () {
          return !this.showPopupChangeCity && !this.cityNotDefined;
      },
      showNotDefinedCity: function () {
          return !this.showPopupChangeCity && this.cityNotDefined;
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
      this.curentCity = LocationDataDND.cityName;
      this.curentCityID = LocationDataDND.cityID;
      this.cityNotDefined = this.curentCity.length <= 0;
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
    doChangeCity: function() {
      if (this.specifyInformationStatus === false && this.showPopupChangeCity === true) {
        this.specifyInformationStatus = true;
        this.showPopupChangeCity = false;
        this.changeCitySearchLine = '';
        return this.listOfCity = this.listOfCityDefault;
      } else {
        this.specifyInformationStatus = false;
        return this.showPopupChangeCity = true;
      }
    },
    changeCity: function(cityItem) {
      this.showPopupChangeCity = false;
      this.specifyInformationStatus = true;
      this.changeCitySearchLine = '';
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
