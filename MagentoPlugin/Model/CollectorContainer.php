<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Model\Formatter\KoalityFormatter;

class CollectorContainer
{
    //TODO Discuss this
    /**
     * @var KoalityFormatter
     */
    private $formatter;

    public function __construct(KoalityFormatter $formatter)
    {
        $this->formatter = $formatter;
    }
}
