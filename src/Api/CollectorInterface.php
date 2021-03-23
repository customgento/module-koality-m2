<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Api;

use Koality\MagentoPlugin\Model\Formatter\Result;

/**
 * Collection interface.
 *
 * @api
 */
interface CollectorInterface
{
    /**
     * Return a health check result for a single criteria.
     *
     * @return Result
     */
    public function getResult(): Result;
}
