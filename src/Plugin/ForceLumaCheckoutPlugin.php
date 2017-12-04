<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Theme\Model\View\Design;

class ForceLumaCheckoutPlugin
{
    const CONFIG_PATH_FORCE_LUMA_CHECKOUT = 'clean_checkout/general/force_luma_checkout';

    /**
     * @var ThemeProviderInterface
     */
    private $themeProvider;

    /**
     * @var HttpRequest\Proxy
     */
    private $httpRequest;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ThemeProviderInterface $themeProvider
     * @param HttpRequest\Proxy $httpRequest
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ThemeProviderInterface $themeProvider,
        HttpRequest\Proxy $httpRequest
    ) {
        $this->themeProvider = $themeProvider;
        $this->httpRequest = $httpRequest;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return int
     */
    private function getLumaThemeId()
    {
        $theme = $this->themeProvider->getThemeByFullPath('frontend/Magento/luma');
        return $theme->getId();
    }

    /**
     * Forces the Luma theme when httpRequest is being handled by checkout.
     *
     * @param Design $subject
     * @param callable $proceed
     * @param array ...$args
     * @return int
     */
    public function aroundGetConfigurationDesignTheme(Design $subject, callable $proceed, ...$args)
    {
        $forceLuma = $this->scopeConfig->getValue(self::CONFIG_PATH_FORCE_LUMA_CHECKOUT, ScopeInterface::SCOPE_STORE);
        if ($forceLuma &&
            $this->httpRequest->getModuleName() === 'checkout' &&
            $this->httpRequest->getControllerName() === 'index') {
            return $this->getLumaThemeId();
        }
        return $proceed(...$args);
    }
}
