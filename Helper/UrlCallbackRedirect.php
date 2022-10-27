<?php

namespace Senangpay\SenangpayPaymentGateway\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Checkout workflow helper
 *
 * Class Checkout
 * @package Senangpay\SenangpayPaymentGateway\Helper
 */
class UrlCallbackRedirect extends AbstractHelper
{
    protected $_storeManager;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->_storeManager = $storeManager;
    }

    protected function getStoreManager()
    {
        return $this->_storeManager;
    }

    public function getCallbackUrl()
    {
        return $this->getStoreManager()->getStore()->getBaseUrl() . 'senangpay/checkout/callback';
    }

    public function getRedirectUrl()
    {
        return $this->getStoreManager()->getStore()->getBaseUrl() . 'senangpay/checkout/redirect';
    }

}
