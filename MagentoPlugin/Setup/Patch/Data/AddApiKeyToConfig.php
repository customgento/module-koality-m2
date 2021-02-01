<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Koality\MagentoPlugin\Model\Config as KoalityConfig;

class AddApiKeyToConfig implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var KoalityConfig
     */
    private $config;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        KoalityConfig $config
    ) {
        $this->moduleDataSetup     = $moduleDataSetup;
        $this->config              = $config;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->insert(
            $this->moduleDataSetup->getTable('core_config_data'), [
                'path'  => KoalityConfig::KOALITY_API_KEY,
                'value' => $this->createGuid()
            ]
        );
    }

    private function createGuid(): string
    {
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', random_int(0, 65535), random_int(0, 65535),
            random_int(0, 65535), random_int(16384, 20479), random_int(32768, 49151), random_int(0, 65535),
            random_int(0, 65535), random_int(0, 65535));
    }

    public static function getDependencies(): array
    {
        return [];

    }

    public function getAliases(): array
    {
        return [];

    }

    public function revert()
    {
        return [];

    }
}
