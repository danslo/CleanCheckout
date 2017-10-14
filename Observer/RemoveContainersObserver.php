<?php

namespace Rubic\SimpleCheckout\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Layout;

class RemoveContainersObserver implements ObserverInterface
{
    const CONFIG_DISABLE_PATH = 'simple_checkout/general/disable_%s';

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
     * @var Layout
     */
    private $layout;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Layout $layout
     */
    public function __construct(ScopeConfigInterface $scopeConfig, Layout $layout)
    {
        $this->layout = $layout;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        foreach (self::DISABLE_ELEMENTS as $type => $element) {
            $configPath = sprintf(self::CONFIG_DISABLE_PATH, $type);
            if ($this->scopeConfig->getValue($configPath)) {
                $this->layout->unsetElement($element);
            }
        }
    }
}