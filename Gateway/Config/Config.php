<?php

namespace Senangpay\SenangpayPaymentGateway\Gateway\Config;

/**
 * Class Config.
 * Values returned from Magento\Payment\Gateway\Config\Config.getValue()
 * are taken by default from ScopeInterface::SCOPE_STORE
 */
class Config extends \Magento\Payment\Gateway\Config\Config
{
    const CODE = 'senangpay_gateway';
    const KEY_DEBUG = 'debug';

    public function getSecretKey()
    {
        return $this->getValue('secret_key');
    }

    public function getMerchantId()
    {
        return $this->getValue('merchant_id');
    }

    public function getIsSandbox()
    {
        return $this->getValue('is_sandbox');
    }

    public function isEmailCustomer()
    {
        return (bool) $this->getValue('email_customer');
    }

    /**
     * Check if customer is to be notified
     * @return boolean
     */
    public function isAutomaticInvoice()
    {
        return (bool) $this->getValue('notify_customer');
    }

    /**
     * Get Payment configuration status
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->getValue('active');
    }

    /**
     * Get specific country
     *
     * @return string
     */
    public function getSpecificCountry()
    {
        return $this->getValue('specificcountry');
    }

}
