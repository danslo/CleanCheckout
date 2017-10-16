<?php

namespace Rubic\SimpleCheckout\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Framework\App\Config\ScopeConfigInterface;

class AddNewsletterComponentPlugin
{
    const CONFIG_PATH_NEWSLETTER_ENABLED = 'simple_checkout/newsletter/enabled';

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

        if ($this->scopeConfig->getValue(self::CONFIG_PATH_NEWSLETTER_ENABLED)) {
            $jsLayout['components']['checkout']
                ['children']
                    ['steps']
                        ['children']
                            ['billing-step']
                                ['children']
                                    ['payment']
                                        ['children']
                                            ['afterMethods']
                                                ['children']
                                                    ['newsletter'] = [
                                                        'component' => 'Rubic_SimpleCheckout/js/view/newsletter'
                                                    ]
            ;
        }
        return $jsLayout;
    }
}