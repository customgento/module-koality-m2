<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Api;

interface ResultInterface
{
    public const KEY_NEWSLETTER_TOO_FEW = 'newsletter.too_few';
    public const KEY_ORDERS_TOO_FEW = 'orders.too_few';
    public const KEY_CARTS_OPEN_TOO_MANY = 'carts.open.too_many';
    public const KEY_PRODUCTS_ACTIVE = 'products.active';
    public const KEY_PLUGINS_UPDATABLE = 'plugins.updatable';
    public const STATUS_PASS = 'pass';
    public const STATUS_FAIL = 'fail';
    public const LIMIT_TYPE_MIN = 'min';
    public const LIMIT_TYPE_MAX = 'max';
    public const TYPE_TIME_SERIES_NUMERIC = 'time_series_numeric';
    public const TYPE_TIME_SERIES_PERCENT = 'time_series_percent';

    /**
     * Return the results status. Can be fail or pass.
     *
     * Use the class constants for checking the status.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Return the results message.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Return the results unique key.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Get the limit of the metric that was checked.
     *
     * This field is optional.
     *
     * @return int|null
     */
    public function getLimit(): ?int;

    /**
     * Set the limit of the metric that was checked.
     *
     * @param int $limit
     *
     * @return void
     */
    public function setLimit(int $limit): void;

    /**
     * Get the current value of the checked metric.
     *
     * This field is optional.
     *
     * @return mixed
     */
    public function getObservedValue();

    /**
     * Set the current value if the metric that is checked.
     *
     * @param mixed $observedValue
     *
     * @return void
     */
    public function setObservedValue($observedValue): void;

    /**
     * Return the unit of the observed value.
     *
     * @return string
     */
    public function getObservedValueUnit(): string;

    /**
     * Set the unit of the observed value.
     *
     * @param string $observedValueUnit
     *
     * @return void
     */
    public function setObservedValueUnit(string $observedValueUnit): void;

    /**
     * Add a new attribute to the result.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function addAttribute(string $key, $value): void;

    /**
     * Return a list of attribute
     *
     * @return array
     */
    public function getAttributes(): array;

    /**
     * @return string
     */
    public function getLimitType(): string;

    /**
     * @param string $limitType
     *
     * @return void
     */
    public function setLimitType(string $limitType): void;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     */
    public function setType(string $type): void;

    /**
     * @return int
     */
    public function getObservedValuePrecision(): int;

    /**
     * @param int $observedValuePrecision
     */
    public function setObservedValuePrecision(int $observedValuePrecision): void;
}
