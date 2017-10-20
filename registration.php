<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Rubic_CleanCheckout',
    __DIR__
);

ComponentRegistrar::register(
    ComponentRegistrar::LIBRARY,
    'GeoIp2',
    __DIR__ . '/../../../vendor/geoip2/geoip2/src'
);
