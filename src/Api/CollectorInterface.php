<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Api;

use Koality\MagentoPlugin\Api\ResultInterface;

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
     * @return ResultInterface
     */
    public function getResult(): ResultInterface;
}
