<?php

namespace Rubic\SimpleCheckout\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    const CONFIG_PATH_HIDE_SHIPPING_METHODS  = 'simple_checkout/general/hide_shipping_methods';
    const CONFIG_PATH_FORCE_TOTALS_FULL_MODE = 'simple_checkout/general/force_totals_full_mode';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'hideShippingMethods' => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_HIDE_SHIPPING_METHODS),
            'forceTotalsFullMode' => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_FORCE_TOTALS_FULL_MODE)
        ];
    }
}