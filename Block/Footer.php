<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;

class Footer extends Template
{
    const CONFIG_PATH_FOOTER_CONTENT = 'clean_checkout/general/footer_content';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(Context $context, ScopeConfigInterface $scopeConfig, array $data = [])
    {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Gets footer content from store config.
     *
     * @return string
     */
    public function getFooterContent()
    {
        return $this->scopeConfig->getValue(self::CONFIG_PATH_FOOTER_CONTENT, ScopeInterface::SCOPE_STORE);
    }
}
