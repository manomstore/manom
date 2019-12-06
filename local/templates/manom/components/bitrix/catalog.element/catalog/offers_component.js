BX.namespace('BX');

(function () {
    'use strict';

    BX.OffersComponent = {
        // activatePropElementsByOfferId: function(id){
        //     // В будущем заменить на find или some
        //     var index = this.availableOffers.indexOf(id);

        //     if (index !== -1) {
        //         var currentOfferProps = this.availableOffers[index];

        //         for (var propName in currentOfferProps) {
        //             if (currentOfferProps.hasOwnProperty(propName)) {
        //                 var prop = currentOfferProps[propName];

        //                 // Может лучше сразу искать нужные элементы document.querySelectorAll?
        //                 this.offerPropElements.forEach(function(offerPropElement){
        //                     if (offerPropElement.dataset.propId === prop.prod_id) {
        //                         if (
        //                             offerPropElement.dataset.id === prop.id
        //                             && !offerPropElement.classList.contains('active')
        //                         ) {

        //                         }
        //                     }
        //                 });
        //             }
        //         }
        //     }
        // },

        activateCurrentOfferProps: function(){
            var _this = this;

            Object.keys(this.currentOfferProps).forEach(function(currentOfferProp){
                // TODO: filter элементов
                _this.offerPropElements.forEach(function(offerPropElement){
                    if (
                        offerPropElement.dataset.propCode.toUpperCase() === currentOfferProp.toUpperCase()
                        && offerPropElement.dataset.id === _this.currentOfferProps[currentOfferProp].id
                    ) {
                        offerPropElement.classList.add('active');
                    }

                    if (
                        offerPropElement.dataset.propCode.toUpperCase() === currentOfferProp.toUpperCase()
                        && offerPropElement.dataset.id !== _this.currentOfferProps[currentOfferProp].id
                    ) {
                        offerPropElement.classList.remove('active');
                    }
                });
            });
        },

        getOfferIdByCurrentOfferProps: function(){
            var _this = this;
            var lengthAvailableOffers = _this.availableOffers.length;
            var offerId = null;

            for (var i = 0; i < lengthAvailableOffers; i++) {
                var availableOffer = _this.availableOffers[i];

                offerId = availableOffer.id_offer;
                _this.offerPropKeys.forEach(function(offerPropKey){
                    if (availableOffer.props[offerPropKey].id !== _this.currentOfferProps[offerPropKey].id) {
                        offerId = null;
                    }
                });

                if (offerId) {
                    break;
                }
            }

            return offerId;
        },

        init: function(params){
            var _this = this;

            _this.initPrimaryFields();

            _this.availableOffers = params.availableOffers;
            _this.currentOfferId = Number(params.currentOfferId || availableOffers[0].id_offer);
            _this.offerProps = params.offerProps;
            _this.offerPropKeys = Object.keys(_this.offerProps);
            _this.offerMainPropKey = _this.offerPropKeys[0];

            _this.initPropElements();
            _this.setCurrentOfferPropsByOfferId(_this.currentOfferId);

            console.log('available_Offers'.toUpperCase() + ': ', _this.availableOffers);
            console.log('current_Offer_Id'.toUpperCase() + ': ', _this.currentOfferId);
            console.log('offer_Properties'.toUpperCase() + ': ', _this.offerProps);

            console.log('_this: ', _this);

            _this.initEvents();
        },

        initPrimaryFields: function(){
            this.availableOffers = [];
            this.currentOfferId = null;
            this.currentOfferProps = {};
            this.offerPropElements = [];
            this.offerProps = {};
            this.offerPropKeys = [];
            this.offerMainPropKey = '';
        },

        initPropElements: function(){
            this.offerPropElements = [].slice.call(document.querySelectorAll('.offer_prop_item'));
        },

        initEvents: function(){
            var _this = this;

            _this.offerPropElements.forEach(function(offerPropElement){
                offerPropElement.addEventListener('click', function(event){
                    var dataset = this.dataset;

                    if (dataset.propCode.toUpperCase() === _this.offerMainPropKey.toUpperCase()) {
                        var currentOffer = _this.availableOffers.filter(function(availableOffer){
                            return availableOffer.props[dataset.propCode].id === dataset.id;
                        });
                        var offerId = currentOffer[0].id_offer;

                        _this.setCurrentOfferId(offerId);
                        _this.setCurrentOfferPropsByOfferId(offerId);
                    } else {
                        _this.setCurrentOfferProp(dataset.propCode, dataset.id);

                        var newOfferId = _this.getOfferIdByCurrentOfferProps();

                        // Есть ли по выбранным параметрам доступное предложение или нет
                        // Если нет, то в качестве активного предложения выбираем первое доступное предложение
                        if (newOfferId) {
                            _this.setCurrentOfferId(newOfferId);
                        } else {
                            _this.setCurrentOfferId(_this.availableOffers[0].id_offer);
                            _this.setCurrentOfferPropsByOfferId(_this.currentOfferId);
                        }
                    }

                    _this.activateCurrentOfferProps();
                    event.preventDefault();
                });
            });
        },

        render: function(){
            if (!$.isReady) {
                return null;
            }
        },

        setCurrentOfferId: function(id){
            this.currentOfferId = parseInt(id, 10);
        },

        setCurrentOfferProp: function(propName, id) {
            var _this = this;

            Object.keys(_this.offerProps[propName].VALUE).forEach(function(value){
                if (_this.offerProps[propName].VALUE[value].id === id) {
                    _this.currentOfferProps[propName] = _this.offerProps[propName].VALUE[value];
                }
            });
        },

        setCurrentOfferPropsByOfferId: function(id){
            var _this = this;

            _this.offerPropKeys.forEach(function(offerPropKey){
                var currentOffer = _this.availableOffers.filter(function(availableOffer){
                    return Number(availableOffer.id_offer) === parseInt(id, 10);
                });

                if (currentOffer.length > 0) {
                    _this.currentOfferProps[offerPropKey] = currentOffer[0].props[offerPropKey];
                }
            });
        },


        // check: function(){


        //     this.availableOffers.forEach(function(availableOffer){
        //         availableOffer.
        //     });

        // }
    };
})();