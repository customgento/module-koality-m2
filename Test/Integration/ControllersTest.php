<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Test\Integration;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Magento\Framework\ObjectManagerInterface;
use Koality\MagentoPlugin\Model\CollectorContainer;
use Koality\MagentoPlugin\Model\ActiveProductsCollector;
use Koality\MagentoPlugin\Model\Config;

class ControllersTest extends TestCase
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var CollectorContainer
     */
    private $activeProductsCollector;

    /**
     * @var Config
     */
    private $config;

    protected function setUp(): void
    {
        $this->objectManager           = Bootstrap::getObjectManager();
        $this->activeProductsCollector = $this->objectManager->create(ActiveProductsCollector::class);
        $this->config                  = $this->objectManager->create(Config::class);
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture koality_module_magento/rush_hour/rushHourBegin 10:00
     */
    public function testRetrieveRushHourBegin(): void
    {
        self::assertEquals('10:10', $this->config->getRushHourBegin());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture koality_module_magento/rush_hour/rushHourEnd 17:00
     */
    public function testRetrieveRushHourEnd(): void
    {
        self::assertEquals('17:00', $this->config->getRushHourBegin());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture koality_module_magento/rush_hour/ordersPerHourRushHour 50
     */
    public function testOrdersPerRushHour(): void
    {
        self::assertEquals(50, $this->config->getOrdersPerRushHour());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture koality_module_magento/rush_hour/includeWeekends 1
     */
    public function testRushHourIncludedWeekend(): void
    {
        self::assertEquals(true, $this->config->doesRushHourHappenWeekends());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture koality_module_magento/opening_hours/ordersPerHourNormal 10
     */
    public function testOrdersPerHourNormal(): void
    {
        self::assertEquals(10, $this->config->getOrdersPerHourNormal());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture koality_module_magento/open_carts/openCarts 10
     */
    public function testOpenCartsExists(): void
    {
        self::assertEquals(10, $this->config->getOpenCarts());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture koality_module_magento/active_products/activeProducts 100
     */
    public function testGetActiveProducts(): void
    {
        self::assertEquals(100, $this->config->getActiveProducts());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture koality_module_magento/newsletter_subscribers/newsletterSubscriptions 100
     */
    public function testGetNewsletterSubscribers(): void
    {
        self::assertEquals(100, $this->config->getNewsletterSubscribers());
    }
}

