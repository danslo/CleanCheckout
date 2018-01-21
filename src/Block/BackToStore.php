<?php
/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\App\Response\Redirect;
use Magento\Store\Model\ScopeInterface;

class BackToStore extends Template
{
    const CONFIG_PATH_BACK_TO_STORE_LABEL = 'clean_checkout/back_to_store/label';

    /**
     * @var Redirect
     */
    private $redirect;

    /**
     * @param Context $context
     * @param Redirect $redirect
     * @param array $data
     */
    public function __construct(Context $context, Redirect $redirect, array $data = [])
    {
        parent::__construct($context, $data);
        $this->redirect = $redirect;
    }

    /**
     * @return string
     */
    public function getBackToStoreLabel()
    {
        return $this->_scopeConfig->getValue(
            self::CONFIG_PATH_BACK_TO_STORE_LABEL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getBackToStoreUrl()
    {
        return $this->redirect->getRefererUrl();
    }
}
