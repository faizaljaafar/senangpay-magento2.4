<?php
/**
 * Copyright © 2022 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Senangpay\SenangpayPaymentGateway\Model\Ui;

use Senangpay\SenangpayPaymentGateway\Gateway\Config\Config;
use Magento\Backend\Model\Session\Quote;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\View\Asset\Repository;

/**
 * Class ConfigProvider
 */
final class ConfigProvider implements ConfigProviderInterface
{
    protected $_gatewayConfig;
    protected $_scopeConfigInterface;
    protected $customerSession;
    protected $_urlBuilder;
    protected $request;
    protected $_assetRepo;

    public function __construct(
        Config $gatewayConfig,
        Session $customerSession,
        Quote $sessionQuote,
        Context $context,
        Repository $assetRepo
    ) {
        $this->_gatewayConfig = $gatewayConfig;
        $this->_scopeConfigInterface = $context->getScopeConfig();
        $this->customerSession = $customerSession;
        $this->sessionQuote = $sessionQuote;
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->_assetRepo = $assetRepo;
    }

    public function getConfig()
    {
        return [];
    }
}
