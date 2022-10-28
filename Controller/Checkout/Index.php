<?php

namespace Senangpay\SenangpayPaymentGateway\Controller\Checkout;

use Senangpay\SenangpayPaymentGateway\Model\SenangpayApi;
use Magento\Sales\Model\Order;

/**
 * @package Senangpay\SenangpayPaymentGateway\Controller\Checkout
 */
class Index extends AbstractAction
{

    private function constructPaymentUrl($order)
    {
        if ($order == null) {
            $this->getLogger()->debug('Unable to get order from last lodged order id. Possibly related to a failed database call');
            $this->_redirect('checkout/onepage/error', array('_secure' => false));
        }

        $orderId = $order->getRealOrderId();
        $gatewayConf = $this->getGatewayConfig();
        $billingAddress = $order->getBillingAddress();

        $parameter = array(
            'order_id' => $orderId,
            'email' => $order->getData('customer_email'),
            'phone' => $billingAddress->getData('telephone'),
            'name' => $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname(),
            'amount' => $order->getTotalDue(),
            'detail' => "Shopping Cart Order $orderId",
        );
        // $optional = array(
        //     'redirect_url' => $this->getUrlHelper()->getRedirectUrl(),
        // );
        
        if (empty($parameter['mobile']) && empty($parameter['email'])) {
            $parameter['email'] = '';
        }

        if (empty($parameter['name'])) {
            $parameter['name'] =  '';
        }

        $is_staging = $gatewayConf->getIsSandbox() == 1 ? true : false;
        $senangpay = new SenangpayApi(
            trim($gatewayConf->getSecretKey()),
            trim($gatewayConf->getMerchantId()),
            $is_staging,
            $parameter
        );
        $paymentUrl = $senangpay->getPaymentUrl();

        $order->setState(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
        $order->addCommentToStatusHistory("Order ID: $orderId; Status: Pending Payment;", true, true);
        $order->setData('senangpay_order_id', $orderId);
        $order->save();

        return $paymentUrl;
    }

    private function redirectToPaymentForm($paymentUrl)
    {
        $this->renderRedirect($paymentUrl);
    }

    private function renderRedirect($paymentUrl)
    {
        echo
            "<html>
            <body>
            <a href=\"$paymentUrl\">Redirecting to senangPay...</a>
            </body>
            <script>
                window.location.replace(\"$paymentUrl\");
            </script>
            </html>";
    }

    /**
     *
     *
     * @return void
     */
    public function execute()
    {
        try {
            $order = $this->getOrder();
            if ($order->getState() === Order::STATE_PENDING_PAYMENT) {
                $paymentUrl = $this->constructPaymentUrl($order);
                $this->redirectToPaymentForm($paymentUrl);
            } else if ($order->getState() === Order::STATE_CANCELED) {
                $this->getCheckoutHelper()->restoreQuote(); //restore cart
                $this->_redirect('checkout/cart');
            } else {
                $this->getLogger()->debug('Order in unrecognized state: ' . $order->getState());
                $this->_redirect('checkout/cart');
            }
        } catch (Exception $ex) {
            $this->getLogger()->debug('An exception was encountered in senangpay/checkout/index: ' . $ex->getMessage());
            $this->getLogger()->debug($ex->getTraceAsString());
            $this->getMessageManager()->addErrorMessage(__('Unable to start senangPay checkout.'));
        }
    }

}
