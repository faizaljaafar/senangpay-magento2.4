<?php

namespace Senangpay\SenangpayPaymentGateway\Gateway\Config\Source;

class IsSandbox implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * To option array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Yes')],
            ['value' => 0, 'label' => __('No')]
        ];
    }
}