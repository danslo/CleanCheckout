<?php

namespace Rubic\SimpleCheckout\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    const CONFIG_PATH_HIDE_SHIPPING_METHODS = 'simple_checkout/general/hide_shipping_methods';

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
            'hideShippingMethods' => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_HIDE_SHIPPING_METHODS)
        ];
    }
}