<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Exception;

class ApiKey
{
    /**
     * @throws Exception
     */
    public function createRandomApiKey(): string
    {
        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(16384, 20479),
            random_int(32768, 49151),
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(0, 65535)
        );
    }
}
