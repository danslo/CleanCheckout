<?php

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Rubic_SimpleCheckout',
    __DIR__
);

ComponentRegistrar::register(
    ComponentRegistrar::LIBRARY,
    'GeoIp2',
    __DIR__ . '/../../../vendor/geoip2/geoip2/src'
);
