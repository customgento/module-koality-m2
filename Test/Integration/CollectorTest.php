<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Test\Integration;

use Koality\MagentoPlugin\Api\ResultInterface;
use Koality\MagentoPlugin\Model\ActiveProductsCollector;
use Koality\MagentoPlugin\Model\CountOrdersCollector;
use Koality\MagentoPlugin\Model\OpenCartsCollector;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class CollectorTest extends TestCase
{
    /**
     * @var ActiveProductsCollector
     */
    private $activeProductsCollector;

    /**
     * @var CountOrdersCollector
     */
    private $countOrdersCollector;

    /**
     * @var OpenCartsCollector
     */
    private $openCartsCollector;

    protected function setUp(): void
    {
        $objectManager                 = Bootstrap::getObjectManager();
        $this->activeProductsCollector = $objectManager->get(ActiveProductsCollector::class);
        $this->countOrdersCollector    = $objectManager->get(CountOrdersCollector::class);
        $this->openCartsCollector      = $objectManager->get(OpenCartsCollector::class);
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/active_products/active_products 2000
     */
    public function testActiveProductsCollectorReturnsSuccessBasedOnActiveProducts(): void
    {
        $result = $this->activeProductsCollector->getAllProducts();
        self::assertEquals(
            ResultInterface::STATUS_PASS,
            $result->getStatus()
        );
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/active_products/active_products 3000
     */
    public function testActiveProductsCollectorReturnsErrorBasedOnActiveProducts(): void
    {
        $result = $this->activeProductsCollector->getAllProducts();
        self::assertEquals(
            ResultInterface::STATUS_FAIL,
            $result->getStatus()
        );
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoDataFixture   Magento/Sales/_files/order_with_customer_and_multiple_order_items.php
     * @magentoConfigFixture current_store koality/rush_hour/rush_hour_begin 00,00,00
     * @magentoConfigFixture current_store koality/rush_hour/rush_hour_end 23,59,00
     * @magentoConfigFixture current_store koality/rush_hour/include_weekends 1
     * @magentoConfigFixture current_store koality/rush_hour/orders_per_hour_rushHour 50
     */
    public function testCountOrdersCollectorReturnsFalseBasedOnSalesThreshold(): void
    {
        $result = $this->countOrdersCollector->getResult();
        self::assertEquals(
            ResultInterface::STATUS_FAIL,
            $result->getStatus()
        );
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoDataFixture   Magento/Sales/_files/order_with_customer_and_multiple_order_items.php
     * @magentoConfigFixture current_store koality/rush_hour/rush_hour_begin 00,00,00
     * @magentoConfigFixture current_store koality/rush_hour/rush_hour_end 23,59,00
     * @magentoConfigFixture current_store koality/rush_hour/include_weekends 1
     * @magentoConfigFixture current_store koality/rush_hour/orders_per_hour_rushHour 1
     */
    public function testCountOrdersCollectorReturnsTrueBasedOnSalesThreshold(): void
    {
        $result = $this->countOrdersCollector->getResult();
        self::assertEquals(
            ResultInterface::STATUS_PASS,
            $result->getStatus()
        );
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/open_carts/open_carts 10
     */
    public function testOpenCartCollectorReturnsTrueBasedOnMaxCartCount(): void
    {
        $result = $this->openCartsCollector->getResult();
        self::assertEquals(
            ResultInterface::STATUS_PASS,
            $result->getStatus()
        );
    }

    /**
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/open_carts/open_carts -1
     */
    public function testOpenCartCollectorReturnsFalseBasedOnMaxCartCount(): void
    {
        $result = $this->openCartsCollector->getResult();
        self::assertEquals(
            ResultInterface::STATUS_FAIL,
            $result->getStatus()
        );
    }
}
