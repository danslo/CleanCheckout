<?php
/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckout\Setup;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\Store;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Customer\Model\ResourceModel\Attribute as CustomerAttributeResource;

class InstallData implements InstallDataInterface
{
    /**
     * @var array
     */
    private $configSettings = [
        'general/region/display_all'    => 0,
        'customer/address/street_lines' => 1
    ];

    /**
     * @var ConfigInterface
     */
    private $configResource;

    /**
     * @var EavConfig
     */
    private $eavConfig;

    /**
     * @var CustomerAttributeResource
     */
    private $customerAttributeResource;

    /**
     * @param ConfigInterface $configResource
     * @param EavConfig $eavConfig
     * @param CustomerAttributeResource $customerAttributeResource
     */
    public function __construct(
        ConfigInterface $configResource,
        EavConfig $eavConfig,
        CustomerAttributeResource $customerAttributeResource
    ) {
        $this->configResource = $configResource;
        $this->eavConfig = $eavConfig;
        $this->customerAttributeResource = $customerAttributeResource;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0) {
            foreach ($this->configSettings as $path => $value) {
                $this->configResource->saveConfig(
                    $path,
                    $value,
                    ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                    Store::DEFAULT_STORE_ID
                );
            }

            $attribute = $this->eavConfig->getAttribute('customer_address', 'street');
            $attribute->setData('multiline_count', 1);
            $this->customerAttributeResource->save($attribute);
        }

        $setup->endSetup();
    }
}
