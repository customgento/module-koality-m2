<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model\Formatter;

use Koality\MagentoPlugin\Api\ResultInterface;

class Result implements ResultInterface
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $key;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var float|int
     */
    private $observedValue;

    /**
     * @var int
     */
    private $observedValuePrecision;

    /**
     * @var string
     */
    private $observedValueUnit;

    /**
     * @var string
     */
    private $limitType;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * Result constructor.
     *
     * @param string $status
     * @param string $key
     * @param string $message
     */
    public function __construct(string $status, string $key, string $message)
    {
        $this->status  = $status;
        $this->message = $message;
        $this->key     = $key;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function getObservedValue()
    {
        return $this->observedValue;
    }

    public function setObservedValue($observedValue): void
    {
        $this->observedValue = $observedValue;
    }

    public function getObservedValueUnit(): string
    {
        return $this->observedValueUnit;
    }

    public function setObservedValueUnit(string $observedValueUnit): void
    {
        $this->observedValueUnit = $observedValueUnit;
    }

    public function addAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getLimitType(): string
    {
        return $this->limitType;
    }

    public function setLimitType(string $limitType): void
    {
        $this->limitType = $limitType;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getObservedValuePrecision(): int
    {
        return $this->observedValuePrecision;
    }

    public function setObservedValuePrecision(int $observedValuePrecision): void
    {
        $this->observedValuePrecision = $observedValuePrecision;
    }
}
