<?php

namespace Senangpay\SenangpayPaymentGateway\Controller\Checkout;

use Senangpay\SenangpayPaymentGateway\Model\BillplzConnect;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Sales\Model\Order;

/**
 * @package Senangpay\SenangpayPaymentGateway\Controller\Checkout
 */
class Callback extends AbstractAction implements CsrfAwareActionInterface
{
    public function execute()
    {
        try {
            $params = SenangpayConnect::getXSignature($this->getGatewayConfig()->getXSignature());
            $this->getLogger()->debug('Secret key validation passed.');
        } catch (\Exception $e) {
            $this->getLogger()->debug('Failed secret Validation. Possibly due to invalid secret key');
            exit;
        }

        $order = $this->getOrderSenangpayOrderId('senangpay_order_id', $params['order_id']);

        if (!$order) {
            $this->getLogger()->debug("senangPay order id could not be retrieved: {$params['order_id']}");
        }

        if ($params['paid']) {
            if ($order->getState() === Order::STATE_PENDING_PAYMENT) {
                $this->_createInvoice($order, $params['order_id']);
            }
        }
    }

    private function _createInvoice(Order $order, $bill_id)
    {
        if (!$order->canInvoice()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Cannot create an invoice.')
            );
        }

        $invoice = $this->getObjectManager()
            ->create('Magento\Sales\Model\Service\InvoiceService')
            ->prepareInvoice($order);

        if (!$invoice->getTotalQty()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('You can\'t create an invoice without products.')
            );
        }

        /*
         * Look Magento/Sales/Model/Order/Invoice.register() for CAPTURE_OFFLINE explanation.
         * Basically, if !config/can_capture and config/is_gateway and CAPTURE_OFFLINE and
         * Payment.IsTransactionPending => pay (Invoice.STATE = STATE_PAID...)
         */
        $invoice->setTransactionId($bill_id);
        $invoice->setRequestedCaptureCase(Order\Invoice::CAPTURE_OFFLINE);
        $invoice->register();

        $transaction = $this->getObjectManager()->create('Magento\Framework\DB\Transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder());
        $transaction->save();

        $order->setState(Order::STATE_PROCESSING);
        $order->addStatusToHistory($order->getConfig()->getStateDefaultStatus(Order::STATE_PROCESSING), "senangPay payment success. Order ID $bill_id", true);

        $order->save();
    }

    public function createCsrfValidationException(RequestInterface $request):  ? InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request) :  ? bool
    {
        return true;
    }
}
