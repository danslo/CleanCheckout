<?php
/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Block;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Footer extends Template
{
    const CONFIG_PATH_FOOTER_CONTENT = 'clean_checkout/general/footer_content';

    /**
     * Gets footer content from store config.
     *
     * @return string
     */
    public function getFooterContent()
    {
        return $this->_scopeConfig->getValue(
            self::CONFIG_PATH_FOOTER_CONTENT,
            ScopeInterface::SCOPE_STORE
        );
    }
}
