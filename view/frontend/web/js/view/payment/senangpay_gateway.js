/**
 * Copyright © 2022 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'senangpay_gateway',
                component: 'Senangpay_SenangpayPaymentGateway/js/view/payment/method-renderer/senangpay_gateway'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
