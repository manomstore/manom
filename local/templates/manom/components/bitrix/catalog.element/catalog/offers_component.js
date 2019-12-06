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

        },

        render: function () {
            if (!$.isReady) {
                return null;
            }
        },

    };
})();