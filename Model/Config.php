<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const KOALITY_API_KEY = 'koality/api_key/api_key';

    public const RUSHHOUR_BEGIN = 'koality/rush_hour/rush_hour_begin';

    public const RUSHHOUR_END = 'koality/rush_hour/rush_hour_end';

    public const ORDERS_PER_RUSHHOUR = 'koality/rush_hour/orders_per_hour_rushHour';

    public const RUSHHOUR_INCLUDED_WEEKEND = 'koality/rush_hour/include_weekends';

    public const ORDERS_PER_HOUR_NORMAL = 'koality/opening_hours/orders_per_hour_normal';

    public const OPEN_CARTS_EXISTS = 'koality/open_carts/open_carts';

    public const ACTIVE_PRODUCTS = 'koality/active_products/active_products';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getApiKey(): ?string
    {
        return (string)$this->scopeConfig->getValue(self::KOALITY_API_KEY, ScopeInterface::SCOPE_STORE);
    }

    public function getRushHourBegin(): ?string
    {
        return (string)$this->scopeConfig->getValue(self::RUSHHOUR_BEGIN, ScopeInterface::SCOPE_STORE);
    }

    public function getRushHourEnd(): ?string
    {
        return (string)$this->scopeConfig->getValue(self::RUSHHOUR_END, ScopeInterface::SCOPE_STORE);
    }

    public function getOrdersPerRushHour(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::ORDERS_PER_RUSHHOUR, ScopeInterface::SCOPE_STORE);
    }

    public function doesRushHourHappenWeekends(): bool
    {
        return $this->scopeConfig->getValue(self::RUSHHOUR_INCLUDED_WEEKEND, ScopeInterface::SCOPE_STORE);
    }

    public function getOrdersPerHourNormal(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::ORDERS_PER_HOUR_NORMAL, ScopeInterface::SCOPE_STORE);
    }

    public function getOpenCarts(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::OPEN_CARTS_EXISTS, ScopeInterface::SCOPE_STORE);
    }

    public function getActiveProducts(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::ACTIVE_PRODUCTS, ScopeInterface::SCOPE_STORE);
    }
}
