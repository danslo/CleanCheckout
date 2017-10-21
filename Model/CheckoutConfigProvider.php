<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Rubic\CleanCheckout\Service\SocialLoginService;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    const CONFIG_PATH_HIDE_SHIPPING_METHODS   = 'clean_checkout/general/hide_shipping_methods';
    const CONFIG_PATH_HIDE_SHIPPING_TITLE     = 'clean_checkout/general/hide_shipping_title';
    const CONFIG_PATH_FORCE_TOTALS_FULL_MODE  = 'clean_checkout/general/force_totals_full_mode';
    const CONFIG_PATH_NEWSLETTER_ENABLED      = 'clean_checkout/newsletter/enabled';
    const CONFIG_PATH_NEWSLETTER_CHECKED      = 'clean_checkout/newsletter/checked';
    const CONFIG_PATH_NEWSLETTER_LABEL        = 'clean_checkout/newsletter/label';

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
     * Checks if social login provider is enabled in config.
     *
     * @param $provider
     * @return bool
     */
    private function isProviderEnabled($provider)
    {
        return (bool)$this->scopeConfig->getValue(
            sprintf(SocialLoginService::CONFIG_PATH_SOCIAL_LOGIN_PROVIDER_ENABLED, $provider)
        );
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'hideShippingMethods' => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_HIDE_SHIPPING_METHODS),
            'hideShippingTitle'   => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_HIDE_SHIPPING_TITLE),
            'forceTotalsFullMode' => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_FORCE_TOTALS_FULL_MODE),
            'newsletterEnabled'   => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_NEWSLETTER_ENABLED),
            'newsletterUrl'       => $this->url->getUrl('clean_checkout/newsletter/subscribe'),
            'newsletterChecked'   => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_NEWSLETTER_CHECKED),
            'newsletterLabel'     => $this->scopeConfig->getValue(self::CONFIG_PATH_NEWSLETTER_LABEL),
            'socialLogin'         => [
                'enabled'  => (bool)$this->scopeConfig->getValue(SocialLoginService::CONFIG_PATH_SOCIAL_LOGIN_ENABLED),
                'url'      => $this->url->getUrl('clean_checkout/social/authenticate'),
                'twitter'  => $this->isProviderEnabled('twitter'),
                'facebook' => $this->isProviderEnabled('facebook'),
                'google'   => $this->isProviderEnabled('google')
            ]
        ];
    }
}