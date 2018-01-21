<?php
/**
 * Copyright © 2018 Rubic. All rights reserved.
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
     * @var ModuleDirectory
     */
    private $moduleDirectory;

    /**
     * @var ReaderFactory
     */
    private $readerFactory;

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
        $this->remoteAddress = $remoteAddress;
        $this->moduleDirectory = $moduleDirectory;
        $this->readerFactory = $readerFactory;
    }

    /**
     * @return Reader
     */
    private function getReader()
    {
        if ($this->reader === null) {
            $this->reader = $this->readerFactory->create([
                'filename' => $this->moduleDirectory->getDir('Rubic_CleanCheckout') .
                    '/var/GeoLite2-Country.mmdb'
            ]);
        }
        return $this->reader;
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
            return $this->getReader()->country($address)->country->isoCode;
        } catch (\Exception $e) {
            return null;
        }
    }
}
