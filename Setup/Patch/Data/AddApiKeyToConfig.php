<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Setup\Patch\Data;

use Koality\MagentoPlugin\Model\Config as KoalityConfig;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Koality\MagentoPlugin\Model\ApiKey;

class AddApiKeyToConfig implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var ApiKey
     */
    private $apiKey;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup, ApiKey $apiKey)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->apiKey          = $apiKey;
    }

    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->insert(
            $this->moduleDataSetup->getTable('core_config_data'),
            [
                'path'  => KoalityConfig::API_KEY,
                'value' => $this->apiKey->createRandomApiKey()
            ]
        );
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function revert(): array
    {
        return [];
    }
}
