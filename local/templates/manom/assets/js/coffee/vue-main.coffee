locationDND = new Vue(
  el: "#dnd-location"
  data: {
    showPanel: false
    currentCity: null
    currentCityID: null
    listOfCity: []
    listOfCityDefault: []
    listOfCityStore: {}
    isInformationStatus: true
    isShowPopupCity: false
    changeCitySearchLine: ''
  }
  methods: {
    doShowPanel: ()->
      if this.showPanel == true
        return 'dnd-location-line-show'
      else
        return ''
    getUnits: ()->
      console.log LocationDataDND
      this.currentCity = LocationDataDND.cityName
      this.currentCityID = LocationDataDND.cityID
      this.listOfCityDefault = this.listOfCity = LocationDataDND.defaultCityList
      $this = this
      setTimeout ()->
        $this.isInformationStatus = LocationDataDND.specifyInformation
      , 2000
      this.showPanel = true
    currentCityIsActual: ()->
      this.isInformationStatus = true
      axios.get('/ajax/location.php', {
        params: {
          location_code: 'changeStatusSpecify'
        }
      })
    doChangeCity: ()->
      if this.isInformationStatus == false and this.isShowPopupCity == true
        this.isInformationStatus = true
        this.isShowPopupCity = false
        this.changeCitySearchLine = ''
        this.listOfCity = this.listOfCityDefault
      else
        this.isInformationStatus = false
        this.showPopupChangeCity = true
    changeCity: (cityItem)->
      this.showPopupChangeCity = false
      this.isInformationStatus = true
      this.changeCitySearchLine = ''
      this.listOfCity = []
      this.listOfCity = this.listOfCityDefault
      this.currentCity = cityItem.title
      this.currentCityID = cityItem.id
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
