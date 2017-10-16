<?php

namespace Rubic\SimpleCheckout\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Rubic\SimpleCheckout\Plugin\AddNewsletterComponentPlugin;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    const CONFIG_PATH_HIDE_SHIPPING_METHODS  = 'simple_checkout/general/hide_shipping_methods';
    const CONFIG_PATH_FORCE_TOTALS_FULL_MODE = 'simple_checkout/general/force_totals_full_mode';

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
     * @return array
     */
    public function getConfig()
    {
        return [
            'hideShippingMethods' => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_HIDE_SHIPPING_METHODS),
            'forceTotalsFullMode' => (bool)$this->scopeConfig->getValue(self::CONFIG_PATH_FORCE_TOTALS_FULL_MODE),
            'newsletterEnabled'   => (bool)$this->scopeConfig->getValue(AddNewsletterComponentPlugin::CONFIG_PATH_NEWSLETTER_ENABLED),
            'newsletterUrl'       => $this->url->getUrl('simple_checkout/newsletter/subscribe')
        ];
    }
}