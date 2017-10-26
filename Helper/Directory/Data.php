<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Helper\Directory;

use Magento\Directory\Model\CurrencyFactory;
use Magento\Directory\Model\ResourceModel\Country\Collection;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory;
use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Store\Model\StoreManagerInterface;
use Rubic\CleanCheckout\Service\GeoService;

class Data extends \Magento\Directory\Helper\Data
{
    /**
     * @var GeoService
     */
    private $geoService;

    /**
     * @var string|null|false
     */
    private $country;

    /**
     * @param Context $context
     * @param Config $configCacheType
     * @param Collection $countryCollection
     * @param CollectionFactory $regCollectionFactory
     * @param JsonHelper $jsonHelper
     * @param StoreManagerInterface $storeManager
     * @param CurrencyFactory $currencyFactory
     * @param GeoService $geoService
     */
    public function __construct(
        Context $context,
        Config $configCacheType,
        Collection $countryCollection,
        CollectionFactory $regCollectionFactory,
        JsonHelper $jsonHelper,
        StoreManagerInterface $storeManager,
        CurrencyFactory $currencyFactory,
        GeoService $geoService
    ) {
        parent::__construct(
            $context,
            $configCacheType,
            $countryCollection,
            $regCollectionFactory,
            $jsonHelper,
            $storeManager,
            $currencyFactory
        );
        $this->geoService = $geoService;
    }

    /**
     * We prefer not to use a preference for this, but we're not allowed to create a plugin
     * for Magento\Directory\Helper\Data because it's a virtual type.
     *
     * We also can't use a plugin on Magento\Checkout\Block\Checkout\AttributeMerger::getDefaultValue because
     * it's protected, also it would miss a couple of other getDefaultCountry() calls.
     *
     * @param int|null $store
     * @return string
     */
    public function getDefaultCountry($store = null)
    {
        /**
         * null  = not cached
         * false = couldn't get country from IP
         */
        if ($store === null && $this->country === null) {
            $country = $this->geoService->getCountry();
            $this->country = $country !== null ? $country : false;
        }
        return $this->country ? $this->country : parent::getDefaultCountry($store);
    }
}
