<?php
/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ModifyCheckoutLayoutPlugin
{
    const CONFIG_DISABLE_LOGIN_PATH    = 'clean_checkout/cleanup/disable_login_popup';
    const CONFIG_DISABLE_FIELD_PATH    = 'clean_checkout/cleanup/disable_%s';
    const CONFIG_DISABLE_DISCOUNT_PATH = 'clean_checkout/cleanup/disable_discount';
    const CONFIG_MOVE_CART_ITEMS_PATH  = 'clean_checkout/general/move_cart_items';
    const CONFIG_PATH_FIELD_ORDER_PATH = 'clean_checkout/field_order';

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
     * Gets field order from config.
     *
     * @return array
     */
    private function getFieldOrder()
    {
        return $this->scopeConfig->getValue(self::CONFIG_PATH_FIELD_ORDER_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Disables authentication modal.
     *
     * @param array $jsLayout
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
     * @param array $jsLayout
     * @return array
     */
    private function changeCartItemsSortOrder($jsLayout)
    {
        if ($this->scopeConfig->getValue(self::CONFIG_MOVE_CART_ITEMS_PATH, ScopeInterface::SCOPE_STORE)) {
            $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']['cart_items']
                ['sortOrder'] = 0;
        }
        return $jsLayout;
    }

    /**
     * Disables / reorders specific input fields in shipping address fieldset.
     *
     * @param array $jsLayout
     * @return array
     */
    private function modifyShippingFields($jsLayout)
    {
        $shippingFields = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

        $fieldOrder = $this->getFieldOrder();
        foreach ($shippingFields as $fieldName => $shippingField) {
            if (isset($fieldOrder[$fieldName])) {
                $shippingFields[$fieldName]['sortOrder'] = $fieldOrder[$fieldName];
            }
        }

        foreach (self::DISABLE_FIELDS as $field) {
            $configPath = sprintf(self::CONFIG_DISABLE_FIELD_PATH, $field);
            if ($this->scopeConfig->getValue($configPath, ScopeInterface::SCOPE_STORE)) {
                unset($shippingFields[$field]);
            }
        }
        return $jsLayout;
    }

    /**
     * Disables / reorders specific input fields in billing address fieldset.
     *
     * @param array $jsLayout
     * @return array
     */
    private function modifyBillingFields($jsLayout)
    {
        $fieldOrder = $this->getFieldOrder();
        foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                 ['payment']['children']['payments-list']['children'] as $code => &$payment) {
            if (isset($payment['children']['form-fields'])) {
                $billingFields = &$payment['children']['form-fields']['children'];

                foreach ($billingFields as $fieldName => $billingField) {
                    if (isset($fieldOrder[$fieldName])) {
                        $billingFields[$fieldName]['sortOrder'] = $fieldOrder[$fieldName];
                    }
                }

                foreach (self::DISABLE_FIELDS as $field) {
                    $configPath = sprintf(self::CONFIG_DISABLE_FIELD_PATH, $field);
                    if ($this->scopeConfig->getValue($configPath, ScopeInterface::SCOPE_STORE)) {
                        unset($billingFields[$field]);
                    }
                }
            }
        }
        return $jsLayout;
    }

    /**
     * Disables the discount component after payment step.
     *
     * @param array $jsLayout
     * @return array
     */
    private function disableDiscountComponent($jsLayout)
    {
        if ($this->scopeConfig->getValue(self::CONFIG_DISABLE_DISCOUNT_PATH, ScopeInterface::SCOPE_STORE)) {
            unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['afterMethods']['children']['discount']);
        }
        return $jsLayout;
    }

    /**
     * @param LayoutProcessor $layoutProcessor
     * @param callable $proceed
     * @param array<int, mixed> $args
     * @return array
     */
    public function aroundProcess(LayoutProcessor $layoutProcessor, callable $proceed, ...$args)
    {
        $jsLayout = $proceed(...$args);

        $jsLayout = $this->disableAuthentication($jsLayout);
        $jsLayout = $this->modifyShippingFields($jsLayout);
        $jsLayout = $this->modifyBillingFields($jsLayout);
        $jsLayout = $this->changeCartItemsSortOrder($jsLayout);
        $jsLayout = $this->disableDiscountComponent($jsLayout);

        return $jsLayout;
    }
}
