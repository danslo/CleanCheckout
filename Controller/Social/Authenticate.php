<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Controller\Social;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NotFoundException;
use Rubic\CleanCheckout\Service\SocialLoginService;

class Authenticate extends Action
{
    /**
     * @var SocialLoginService
     */
    private $socialLoginService;

    /**
     * @param Context $context
     * @param SocialLoginService $socialLoginService
     */
    public function __construct(Context $context, SocialLoginService $socialLoginService)
    {
        parent::__construct($context);
        $this->socialLoginService = $socialLoginService;
    }

    /**
     * Authenticates the user using social media, then returns to the checkout.
     *
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $provider = $this->_request->getParam('provider');
        $this->socialLoginService->login($provider);
        return $this->_redirect('checkout/index/index');
    }
}