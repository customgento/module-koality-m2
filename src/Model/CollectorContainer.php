<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Model\Formatter\KoalityFormatter;
use Koality\MagentoPlugin\Model\Config;
use Koality\MagentoPlugin\Model\CountOrdersCollector;
use Koality\MagentoPlugin\Model\ActiveProductsCollector;
use Koality\MagentoPlugin\Model\OpenCartsCollector;

class CollectorContainer
{
    /**
     * @var CountOrdersCollector
     */
    private $countOrderCollector;

    /**
     * @var ActiveProductsCollector
     */
    private $activeProductsCollector;

    /**
     * @var OpenCartsCollector
     */
    private $openCartsCollector;

    public function __construct(
        CountOrdersCollector $countOrderCollector,
        ActiveProductsCollector $activeProductsCollector,
        OpenCartsCollector $openCartsCollector
    ) {
        $this->countOrderCollector              = $countOrderCollector;
        $this->activeProductsCollector          = $activeProductsCollector;
        $this->openCartsCollector               = $openCartsCollector;
    }

    public function run(): KoalityFormatter
    {
        $formatter  = new KoalityFormatter();
        $collectors = [
            $this->countOrderCollector->getResult(),
            $this->activeProductsCollector->getResult(),
            $this->openCartsCollector->getResult()
        ];

        foreach ($collectors as $collector) {
            $formatter->addResult($collector);
        }

        return $formatter;
    }
}
