<?php
/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\ScopeInterface;
use Rubic\CleanCheckout\ConfigProvider\CheckoutConfigProvider;

class SetDefaultShippingObserver implements ObserverInterface
{
    const CONFIG_PATH_DEFAULT_SHIPPING_METHOD = 'clean_checkout/general/default_shipping_method';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var DirectoryHelper
     */
    private $directoryHelper;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param DirectoryHelper $directoryHelper
     */
    public function __construct(ScopeConfigInterface $scopeConfig, DirectoryHelper $directoryHelper)
    {
        $this->scopeConfig = $scopeConfig;
        $this->directoryHelper = $directoryHelper;
    }

    /**
     *
     * @return bool
     */
    private function isTotalsFullModeEnabled()
    {
        return (bool)$this->scopeConfig->getValue(
            CheckoutConfigProvider::CONFIG_PATH_FORCE_TOTALS_FULL_MODE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    private function getDefaultShippingMethod()
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_DEFAULT_SHIPPING_METHOD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->isTotalsFullModeEnabled()) {
            return;
        }

        /** @var Quote $quote */
        $quote = $observer->getData('quote');
        $shippingAddress = $quote->getShippingAddress();

        if (!$shippingAddress->getShippingMethod()) {
            if (!$shippingAddress->getCountryId()) {
                $shippingAddress->setCountryId($this->directoryHelper->getDefaultCountry());
            }
            $shippingAddress->setCollectShippingRates(true)
                ->setShippingMethod($this->getDefaultShippingMethod());
        }
    }
}
