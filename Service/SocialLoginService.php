<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Service;

use Hybridauth\HybridauthFactory;
use Hybridauth\User\Profile;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class SocialLoginService
{
    const CONFIG_PATH_SOCIAL_LOGIN_ENABLED          = 'clean_checkout/social_login/enabled';
    const CONFIG_PATH_SOCIAL_LOGIN_PROVIDER_ENABLED = 'clean_checkout/social_login/enable_%s';
    const CONFIG_PATH_SOCIAL_LOGIN_PROVIDER_KEY     = 'clean_checkout/social_login/%s_key';
    const CONFIG_PATH_SOCIAL_LOGIN_PROVIDER_SECRET  = 'clean_checkout/social_login/%s_secret';

    /**
     * The providers we currently support.
     */
    const PROVIDERS = [
        'twitter',
        'facebook',
        'google'
    ];

    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var CustomerResource
     */
    private $customerResource;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var HybridauthFactory
     */
    private $hybridauthFactory;

    /**
     * @param HybridauthFactory $hybridauthFactory
     * @param UrlInterface $url
     * @param CustomerFactory $customerFactory
     * @param CustomerResource $customerResource
     * @param CustomerSession $customerSession
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        HybridauthFactory $hybridauthFactory,
        UrlInterface $url,
        CustomerFactory $customerFactory,
        CustomerResource $customerResource,
        CustomerSession $customerSession,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->hybridauthFactory = $hybridauthFactory;
        $this->customerFactory = $customerFactory;
        $this->customerSession = $customerSession;
        $this->customerResource = $customerResource;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->url = $url;
    }

    /**
     * Gets providers from Magento configuration.
     *
     * @return array
     */
    private function getProvidersConfig()
    {
        $config = [];
        foreach (self::PROVIDERS as $provider) {
            $config[ucfirst($provider)] = [
                'enabled' => (bool)$this->scopeConfig->getValue(sprintf(self::CONFIG_PATH_SOCIAL_LOGIN_PROVIDER_ENABLED, $provider)),
                'keys' => [
                    'key' => $this->scopeConfig->getValue(sprintf(self::CONFIG_PATH_SOCIAL_LOGIN_PROVIDER_KEY, $provider)),
                    'secret' => $this->scopeConfig->getValue(sprintf(self::CONFIG_PATH_SOCIAL_LOGIN_PROVIDER_SECRET, $provider)),
                ]
            ];
        }
        return $config;
    }

    /**
     * Gets the callback URL including current provider.
     *
     * @param string $provider
     * @return string
     */
    private function getCallbackUrl($provider)
    {
        return $this->url->getUrl('clean_checkout/social/authenticate', ['_query' => ['provider' => $provider]]);
    }

    /**
     * Gets the current website ID.
     *
     * @return int
     */
    private function getCurrentWebsiteId()
    {
        return $this->storeManager->getWebsite()->getId();
    }

    /**
     * Gets customer data for a hybrid auth profile.
     *
     * @param Profile $profile
     * @return array
     */
    private function getCustomerData(Profile $profile)
    {
        $customerData = [];
        foreach (['firstName', 'lastName', 'email'] as $field) {
            $data = $profile->{$field};
            $customerData[strtolower($field)] = $data !== null ? $data : '-';
        }
        return $customerData;
    }

    /**
     * Loads or creates a customer for a hybridauth profile.
     *
     * @param Profile $profile
     * @return Customer
     */
    private function getCustomerForProfile(Profile $profile)
    {
        /** @var Customer $customer */
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($this->getCurrentWebsiteId());
        $customer->loadByEmail($profile->emailVerified);
        if (!$customer->getId()) {
            $customer->setData('email', $profile->emailVerified);
            $customer->addData($this->getCustomerData($profile));
            $this->customerResource->save($customer);
        }
        return $customer;
    }

    /**
     * Authenticates the user using social media, then returns to the checkout.
     *
     * @param string $provider
     */
    public function login($provider)
    {
        if (!$this->scopeConfig->getValue(self::CONFIG_PATH_SOCIAL_LOGIN_ENABLED)) {
            return;
        }

        $provider = ucfirst($provider);
        $hybridAuth = $this->hybridauthFactory->create([
            'config' => [
                'callback'  => $this->getCallbackUrl($provider),
                'providers' => $this->getProvidersConfig()
            ]
        ]);

        $adapter = $hybridAuth->authenticate($provider);
        if (!$this->customerSession->isLoggedIn() && $adapter->isConnected()) {
            $profile = $adapter->getUserProfile();
            if ($profile->emailVerified) {
                $customer = $this->getCustomerForProfile($profile);
                $this->customerSession->setCustomerAsLoggedIn($customer);
            }
        }
    }
}