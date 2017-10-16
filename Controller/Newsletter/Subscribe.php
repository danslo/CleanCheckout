<?php

namespace Rubic\SimpleCheckout\Controller\Newsletter;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;

class Subscribe extends Action
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param SubscriberFactory $subscriberFactory
     */
    public function __construct(Context $context, JsonFactory $jsonFactory, SubscriberFactory $subscriberFactory)
    {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->subscriberFactory = $subscriberFactory;
    }

    /**
     * @return Json
     */
    public function execute()
    {
        $result = $this->jsonFactory->create();
        $email = $this->getRequest()->getParam('email');
        if (empty($email)) {
            $result->setData(['success' => false, 'error' => __('E-mail cannot be empty.')]);
            return $result;
        }

        try {
            /** @var Subscriber $subscriber */
            $subscriber = $this->subscriberFactory->create();
            $subscriber->subscribe($email);
            $result->setData(['success' => true]);
        } catch (\Exception $e) {
            $result->setData(['success' => false, 'error' => $e->getMessage()]);
        }
        return $result;
    }
}