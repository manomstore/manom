locationDND = new Vue(
  el: "#dnd-location"
  data: {
    showPanel: false
    curentCity: null
    curentCityID: null
    listOfCity: []
    listOfCityDefault: []
    listOfCityStore: {}
    specifyInformationStatus: true
    showPopupChangeCity: false
    changeCitySearchLine: ''
  }
  methods: {
    doShowPanel: ()->
      if this.showPanel == true
        return 'dnd-location-line-show'
      else
        return ''
    getUnits: ()->
      this.curentCity = LocationDataDND.cityName
      this.curentCityID = LocationDataDND.cityID
      this.listOfCityDefault = this.listOfCity = LocationDataDND.defaultCityList
      $this = this
      setTimeout ()->
        $this.specifyInformationStatus = LocationDataDND.specifyInformation
      , 2000
      this.showPanel = true
    curentCityIsActual: ()->
      this.specifyInformationStatus = true
      axios.get('/ajax/location.php', {
        params: {
          location_code: 'changeStatusSpecify'
        }
      })
    doChangeCity: ()->
      if this.specifyInformationStatus == false and this.showPopupChangeCity == true
        this.specifyInformationStatus = true
        this.showPopupChangeCity = false
        this.changeCitySearchLine = ''
        this.listOfCity = this.listOfCityDefault
      else
        this.specifyInformationStatus = false
        this.showPopupChangeCity = true
    changeCity: (cityItem)->
      this.showPopupChangeCity = false
      this.specifyInformationStatus = true
      this.changeCitySearchLine = ''
      this.listOfCity = []
      this.listOfCity = this.listOfCityDefault
      this.curentCity = cityItem.title
      this.curentCityID = cityItem.id
      $.fn.updGlobalCityInCart(cityItem.id)
      axios.get('/ajax/location.php', {
        params: {
          location_code: 'changeCity',
          cityID: cityItem.id
        }
      })


  }
  watch: {
    changeCitySearchLine: (val)->
      if val.length >= 2
        if this.listOfCityStore[val+"___str"]
          this.listOfCity = this.listOfCityStore[val+"___str"]
        else
          $this = this
          axios.get('/ajax/location.php', {
            params: {
              location_code: 'getCityList',
              location_search: val
            }
          }).then((response)->
            $this.listOfCityStore[val+"___str"] = $this.listOfCity = response.data['listOfCity']
          )
      else if val.length == 0
        this.listOfCity = this.listOfCityDefault
  }
  beforeMount: ()->
    this.getUnits()
)
