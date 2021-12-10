<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Model\Formatter\KoalityFormatter;

class CollectorContainer
{
    /**
     * @var array
     */
    private $collectors;

    public function __construct(array $collectors = [])
    {
        $this->collectors = $collectors;
    }

    public function run(): KoalityFormatter
    {
        $formatter = new KoalityFormatter();
        foreach ($this->collectors as $collector) {
            $formatter->addResult($collector->getResult());
        }

        return $formatter;
    }
}
