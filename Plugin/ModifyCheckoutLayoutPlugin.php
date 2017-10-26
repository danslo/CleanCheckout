<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ModifyCheckoutLayoutPlugin
{
    const CONFIG_DISABLE_LOGIN_PATH = 'clean_checkout/cleanup/disable_login_popup';
    const CONFIG_DISABLE_FIELD_PATH = 'clean_checkout/cleanup/disable_%s';
    const CONFIG_MOVE_CART_ITEMS    = 'clean_checkout/general/move_cart_items';

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
     * Disables authentication modal.
     *
     * @param $jsLayout
     * @return array
     */
    private function disableAuthentication($jsLayout)
    {
        if ($this->scopeConfig->getValue(self::CONFIG_DISABLE_LOGIN_PATH, ScopeInterface::SCOPE_STORE)) {
            unset($jsLayout['components']['checkout']['children']['authentication']);
        }
        return $jsLayout;
    }

    /**
     * Changes cart items to be above totals in the cart summary.
     *
     * @param $jsLayout
     * @return array
     */
    private function changeCartItemsSortOrder($jsLayout)
    {
        if ($this->scopeConfig->getValue(self::CONFIG_MOVE_CART_ITEMS, ScopeInterface::SCOPE_STORE)) {
            $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']['cart_items']
                ['sortOrder'] = 0;
        }
        return $jsLayout;
    }

    /**
     * Disables specific input fields in shipping address fieldset.
     *
     * @param $jsLayout
     * @return array
     */
    private function disableShippingFields($jsLayout)
    {
        foreach (self::DISABLE_FIELDS as $field) {
            $configPath = sprintf(self::CONFIG_DISABLE_FIELD_PATH, $field);
            if ($this->scopeConfig->getValue($configPath, ScopeInterface::SCOPE_STORE)) {
                unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                    ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][$field]);
            }
        }
        return $jsLayout;
    }

    /**
     * Disables specific input fields in billing address fieldset.
     *
     * @param $jsLayout
     * @return array
     */
    private function disableBillingFields($jsLayout)
    {
        foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                 ['payment']['children']['payments-list']['children'] as $code => &$payment) {
            if (isset($payment['children']['form-fields'])) {
                foreach (self::DISABLE_FIELDS as $field) {
                    $configPath = sprintf(self::CONFIG_DISABLE_FIELD_PATH, $field);
                    if ($this->scopeConfig->getValue($configPath, ScopeInterface::SCOPE_STORE)) {
                        unset($payment['children']['form-fields']['children'][$field]);
                    }
                }
            }
        }
        return $jsLayout;
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

        $jsLayout = $this->disableAuthentication($jsLayout);
        $jsLayout = $this->disableShippingFields($jsLayout);
        $jsLayout = $this->disableBillingFields($jsLayout);
        $jsLayout = $this->changeCartItemsSortOrder($jsLayout);

        return $jsLayout;
    }
}
