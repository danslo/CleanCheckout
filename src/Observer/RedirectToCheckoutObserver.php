<?php
/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

class RedirectToCheckoutObserver implements ObserverInterface
{
    const CONFIG_PATH_REDIRECT_TO_CHECKOUT = 'clean_checkout/general/redirect_to_checkout';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $url
     */
    public function __construct(ScopeConfigInterface $scopeConfig, UrlInterface $url)
    {
        $this->scopeConfig = $scopeConfig;
        $this->url = $url;
    }

    /**
     * Redirects directly to checkout on adding product, if we're configured to do so.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->scopeConfig->getValue(self::CONFIG_PATH_REDIRECT_TO_CHECKOUT, ScopeInterface::SCOPE_STORE)) {
            return;
        }

        /** @var Http $request */
        $request = $observer->getData('request');
        $request->setParam('return_url', $this->url->getUrl('checkout'));
    }
}
