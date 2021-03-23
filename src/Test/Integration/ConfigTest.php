<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Test\Integration;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Koality\MagentoPlugin\Model\Config;

class ConfigTest extends TestCase
{
    /**
     * @var Config
     */
    private $config;

    protected function setUp(): void
    {
        $objectManager           = Bootstrap::getObjectManager();
        $this->config            = $objectManager->create(Config::class);
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/rush_hour/begin 10:00
     */
    public function testRetrieveRushHourBegin(): void
    {
        self::assertEquals('10:00', $this->config->getRushHourBegin());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/rush_hour/end 10:00
     */
    public function testRetrieveRushHourEnd(): void
    {
        self::assertEquals('10:00', $this->config->getRushHourEnd());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/rush_hour/orders_per_rush_hour 50
     */
    public function testOrdersPerRushHour(): void
    {
        self::assertEquals(50, $this->config->getOrdersPerRushHour());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/rush_hour/include_weekends 1
     */
    public function testRushHourIncludedWeekend(): void
    {
        self::assertEquals(true, $this->config->doesRushHourHappenWeekends());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/opening_hours/orders_per_hour_normal 10
     */
    public function testOrdersPerHourNormal(): void
    {
        self::assertEquals(10, $this->config->getOrdersPerHourNormal());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/open_carts/max_number 10
     */
    public function testOpenCartsExists(): void
    {
        self::assertEquals(10, $this->config->getOpenCarts());
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/active_products/min_number 100
     */
    public function testGetActiveProducts(): void
    {
        self::assertEquals(100, $this->config->getActiveProducts());
    }
}
