/**
 * Copyright © 2022 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'mage/url'
    ],
    function (Component, url) {
        'use strict';

        return Component.extend({
            redirectAfterPlaceOrder: false,

            defaults: {
                template: 'Senangpay_SenangpayPaymentGateway/payment/form',
                // transactionResult: ''
            },

            // initObservable: function () {

            //     this._super()
            //         .observe([
            //             'transactionResult'
            //         ]);
            //     return this;
            // },

            getCode: function() {
                return 'senangpay_gateway';
            },

            getData: function() {
                return {
                    'method': this.item.method,
                    // 'additional_data': {
                    //     'transaction_result': this.transactionResult()
                    // }
                };
            },

            // https://magento.stackexchange.com/questions/139071/get-base-url-or-dynamic-url-in-view-js-or-html-files
            afterPlaceOrder: function () {
                window.location.replace(url.build('senangpay/checkout/index'));
            },

            // getTransactionResults: function() {
            //     return _.map(window.checkoutConfig.payment.senangpay_gateway.transactionResults, function(value, key) {
            //         return {
            //             'value': key,
            //             'transaction_result': value
            //         }
            //     });
            // }
        });
    }
);