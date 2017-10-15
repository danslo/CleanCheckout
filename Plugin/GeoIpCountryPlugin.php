<?php

namespace Rubic\SimpleCheckout\Plugin;

use GeoIp2\Database\Reader;
use GeoIp2\Database\ReaderFactory;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Module\Dir as ModuleDirectory;
use Magento\Tax\Model\TaxConfigProvider;

class GeoIpCountryPlugin
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
    )
    {
        $this->reader = $readerFactory->create([
            'filename' => $moduleDirectory->getDir('Rubic_SimpleCheckout') . '/var/GeoLite2-Country.mmdb'
        ]);
        $this->remoteAddress = $remoteAddress;
    }

    /**
     * @param TaxConfigProvider $subject
     * @param callable $proceed
     * @return array
     */
    public function aroundGetConfig(TaxConfigProvider $subject, callable $proceed)
    {
        $config = $proceed();
        try {
            $address = $this->remoteAddress->getRemoteAddress();
            $country = $this->reader->country($address)->country->isoCode;
            $config['defaultCountryId'] = $country;
        } catch (\Exception $e) {}
        return $config;
    }
}
