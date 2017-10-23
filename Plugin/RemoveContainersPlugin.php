<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Result\Layout;

class RemoveContainersPlugin
{
    const CONFIG_DISABLE_PATH = 'clean_checkout/cleanup/disable_%s';

    /**
     * Elements that can be disabled on the checkout page.
     *
     * @var array
     */
    const DISABLE_ELEMENTS = [
        'header'    => 'header.container',
        'footer'    => 'footer-container',
        'copyright' => 'copyright'
    ];

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Http
     */
    private $httpRequest;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Http $httpRequest
     */
    public function __construct(ScopeConfigInterface $scopeConfig, Http $httpRequest)
    {
        $this->scopeConfig = $scopeConfig;
        $this->httpRequest = $httpRequest;
    }

    /**
     * Preferably we would use a 'layout_render_before_checkout_index_index' event for this, but in 2.1, the
     * layout is rendered *before* this event is fired.
     *
     * See https://github.com/magento/magento2/pull/3907
     *
     * @param Layout $subject
     * @param array $args
     * @return array
     */
    public function beforeRenderResult(Layout $subject, ...$args)
    {
        if ($this->httpRequest->getFullActionName() === 'checkout_index_index') {
            $layout = $subject->getLayout();
            foreach (self::DISABLE_ELEMENTS as $type => $element) {
                $configPath = sprintf(self::CONFIG_DISABLE_PATH, $type);
                if ($this->scopeConfig->getValue($configPath)) {
                    $layout->unsetElement($element);
                }
            }
        }
        return $args;
    }
}