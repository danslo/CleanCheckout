<?php
/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Service;

use GeoIp2\Database\Reader;
use GeoIp2\Database\ReaderFactory;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Module\Dir as ModuleDirectory;

class GeoService
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @param ModuleDirectory $moduleDirectory
     * @param ReaderFactory $readerFactory
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        ModuleDirectory $moduleDirectory,
        ReaderFactory $readerFactory,
        RemoteAddress $remoteAddress
    ) {
        $this->reader = $readerFactory->create([
            'filename' => $moduleDirectory->getDir('Rubic_CleanCheckout') . '/var/GeoLite2-Country.mmdb'
        ]);
        $this->remoteAddress = $remoteAddress;
    }

    /**
     * Gets country from remote address.
     *
     * @return null|string
     */
    public function getCountry()
    {
        try {
            $address = $this->remoteAddress->getRemoteAddress();
            return $this->reader->country($address)->country->isoCode;
        } catch (\Exception $e) {
            return null;
        }
    }
}
