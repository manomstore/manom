BX.namespace('BX');

(function () {
    'use strict';

    BX.OffersComponent = {
        initializePrimaryFields: function () {
            this.availableOffers = []
            this.offerProperties = {}
            this.offerPropertyElements = []
            this.currentOfferId = null
        },

        init: function (parameters) {
            this.initializePrimaryFields()

            this.availableOffers = parameters.availableOffers
            this.currentOfferId = Number(parameters.currentOfferId)
            this.offerProperties = parameters.offerProperties

            console.log('available_Offers'.toUpperCase() + ': ', this.availableOffers);
            console.log('current_Offer_Id'.toUpperCase() + ': ', this.currentOfferId);
            console.log('offer_Properties'.toUpperCase() + ': ', this.offerProperties);

        },

        render: function () {
            if (!$.isReady) {
                return null;
            }
        },

    };
})();