<?php

namespace Rubic\SimpleCheckout\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Framework\App\Config\ScopeConfigInterface;

class DisableCheckoutFieldsPlugin
{
    const CONFIG_DISABLE_LOGIN_PATH = 'simple_checkout/general/disable_login_popup';
    const CONFIG_DISABLE_FIELD_PATH = 'simple_checkout/general/disable_%s';

    /**
     * Shipping address fields that can be disabled by backend configuration.
     *
     * @var array
     */
    const DISABLE_FIELDS = [
        'telephone',
        'company'
    ];

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
     * @param LayoutProcessor $layoutProcessor
     * @param callable $proceed
     * @param array $args
     * @return array
     */
    public function aroundProcess(LayoutProcessor $layoutProcessor, callable $proceed, ...$args)
    {
        $jsLayout = $proceed(...$args);

        if ($this->scopeConfig->getValue(self::CONFIG_DISABLE_LOGIN_PATH)) {
            unset($jsLayout['components']['checkout']['children']['authentication']);
        }

        foreach (self::DISABLE_FIELDS as $field) {
            $configPath = sprintf(self::CONFIG_DISABLE_FIELD_PATH, $field);
            if ($this->scopeConfig->getValue($configPath)) {
                unset(
                    $jsLayout['components']['checkout']
                        ['children']
                            ['steps']
                                ['children']
                                    ['shipping-step']
                                        ['children']
                                            ['shippingAddress']
                                                ['children']
                                                    ['shipping-address-fieldset']
                                                        ['children']
                                                            [$field]
                );
            }
        }
        return $jsLayout;
    }
}