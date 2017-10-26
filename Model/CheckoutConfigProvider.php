<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Rubic\CleanCheckout\Service\SocialLoginService;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    const CONFIG_PATH_HIDE_SHIPPING_METHODS   = 'clean_checkout/shipping/hide_shipping_methods';
    const CONFIG_PATH_HIDE_SHIPPING_TITLE     = 'clean_checkout/shipping/hide_shipping_title';
    const CONFIG_PATH_FORCE_TOTALS_FULL_MODE  = 'clean_checkout/general/force_totals_full_mode';
    const CONFIG_PATH_NEWSLETTER_ENABLED      = 'clean_checkout/newsletter/enabled';
    const CONFIG_PATH_NEWSLETTER_CHECKED      = 'clean_checkout/newsletter/checked';
    const CONFIG_PATH_NEWSLETTER_LABEL        = 'clean_checkout/newsletter/label';
    const CONFIG_PATH_STEP_TRANSITION_SPEED   = 'clean_checkout/general/step_transition_speed';

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
            sprintf(SocialLoginService::CONFIG_PATH_SOCIAL_LOGIN_PROVIDER_ENABLED, $provider),
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'hideShippingMethods' => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_HIDE_SHIPPING_METHODS, ScopeInterface::SCOPE_STORE),
            'hideShippingTitle'   => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_HIDE_SHIPPING_TITLE, ScopeInterface::SCOPE_STORE),
            'forceTotalsFullMode' => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_FORCE_TOTALS_FULL_MODE, ScopeInterface::SCOPE_STORE),
            'newsletterEnabled'   => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_NEWSLETTER_ENABLED, ScopeInterface::SCOPE_STORE),
            'newsletterUrl'       => $this->url->getUrl('clean_checkout/newsletter/subscribe'),
            'newsletterChecked'   => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_NEWSLETTER_CHECKED, ScopeInterface::SCOPE_STORE),
            'newsletterLabel'     => $this->scopeConfig->getValue(self::CONFIG_PATH_NEWSLETTER_LABEL, ScopeInterface::SCOPE_STORE),
            'stepTransitionSpeed' => (int)$this->scopeConfig->getValue(self::CONFIG_PATH_STEP_TRANSITION_SPEED, ScopeInterface::SCOPE_STORE),
            'socialLogin'         => [
                'enabled'  => (bool)$this->scopeConfig->getValue(SocialLoginService::CONFIG_PATH_SOCIAL_LOGIN_ENABLED, ScopeInterface::SCOPE_STORE),
                'url'      => $this->url->getUrl('clean_checkout/social/authenticate'),
                'twitter'  => $this->isProviderEnabled('twitter'),
                'facebook' => $this->isProviderEnabled('facebook'),
                'google'   => $this->isProviderEnabled('google')
            ]
        ];
    }
}
