<?php

declare(strict_types=1);

use Magento\Framework\App\ObjectManager as AppObjectManager;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\ModuleList;
use Magento\TestFramework\ObjectManager as TestFrameworkObjectManager;
use PHPUnit\Framework\TestCase;

class ModuleConfigTest extends TestCase
{
    /**
     * @var string
     */
    private $subjectModuleName;

    /**
     * @var AppObjectManager
     */
    private $objectManager;

    protected function setUp(): void
    {
        $this->subjectModuleName = 'Koality_MagentoPlugin';
        $this->objectManager     = TestFrameworkObjectManager::getInstance();
    }

    public function testTheModuleIsRegistered(): void
    {
        $registrar = new ComponentRegistrar();
        self::assertArrayHasKey($this->subjectModuleName, $registrar->getPaths(ComponentRegistrar::MODULE));
    }

    public function testModuleIsListed(): void
    {
        /** @var ModuleList $moduleList */
        $moduleList = $this->objectManager->create(ModuleList::class);
        self::assertTrue($moduleList->has($this->subjectModuleName));
    }

    public function testTheModuleIsConfiguredInTheTestEnvironment(): void
    {
        /** @var ModuleList $moduleList */
        $moduleList = $this->objectManager->create(ModuleList::class);
        self::assertTrue($moduleList->has($this->subjectModuleName));
    }
}
