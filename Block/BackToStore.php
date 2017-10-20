<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\App\Response\Redirect;

class BackToStore extends Template
{
    const CONFIG_PATH_BACK_TO_STORE_LABEL = 'clean_checkout/back_to_store/label';

    /**
     * @var Redirect
     */
    private $redirect;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Redirect $redirect
     * @param array $data
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Redirect $redirect,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
        $this->redirect = $redirect;
    }

    /**
     * @return string
     */
    public function getBackToStoreLabel()
    {
        return $this->scopeConfig->getValue(self::CONFIG_PATH_BACK_TO_STORE_LABEL);
    }

    /**
     * @return string
     */
    public function getBackToStoreUrl()
    {
        return $this->redirect->getRefererUrl();
    }
}