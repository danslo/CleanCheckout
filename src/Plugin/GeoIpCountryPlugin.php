<?php
/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Plugin;

use Magento\Tax\Model\TaxConfigProvider;
use Rubic\CleanCheckout\Service\GeoService;

class GeoIpCountryPlugin
{
    /**
     * @var GeoService
     */
    private $geoService;

    /**
     * @param GeoService $geoService
     */
    public function __construct(GeoService $geoService)
    {
        $this->geoService = $geoService;
    }

    /**
     * @param TaxConfigProvider $subject
     * @param callable $proceed
     * @return array
     */
    public function aroundGetConfig(TaxConfigProvider $subject, callable $proceed)
    {
        $config = $proceed();
        $country = $this->geoService->getCountry();
        if ($country !== null) {
            $config['defaultCountryId'] = $country;
        }
        return $config;
    }
}
