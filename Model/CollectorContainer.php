<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Model\Formatter\KoalityFormatter;
use Koality\MagentoPlugin\Model\Config;
use Koality\MagentoPlugin\Model\CountOrdersCollector;
use Koality\MagentoPlugin\Model\ActiveProductsCollector;
use Koality\MagentoPlugin\Model\OpenCartsCollector;
use Koality\MagentoPlugin\Model\NewsletterSubscriptionCollection;
use Magento\Framework\Controller\Result\JsonFactory;

class CollectorContainer
{
    /**
     * @var KoalityFormatter
     */
    private $formatter;

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

    /**
     * @var NewsletterSubscriptionCollection
     */
    private $newsletterSubscriptionCollection;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    public function __construct(
        KoalityFormatter $formatter,
        CountOrdersCollector $countOrderCollector,
        ActiveProductsCollector $activeProductsCollector,
        OpenCartsCollector $openCartsCollector,
        NewsletterSubscriptionCollection $newsletterSubscriptionCollection,
        JsonFactory $resultJsonFactory
    ) {
        $this->formatter                        = $formatter;
        $this->countOrderCollector              = $countOrderCollector;
        $this->activeProductsCollector          = $activeProductsCollector;
        $this->openCartsCollector               = $openCartsCollector;
        $this->newsletterSubscriptionCollection = $newsletterSubscriptionCollection;
        $this->resultJsonFactory                = $resultJsonFactory;
    }

    public function run(): KoalityFormatter
    {
        $formatter  = new KoalityFormatter($this->resultJsonFactory);
        $collectors = [
            $this->countOrderCollector->getResult(),
            $this->activeProductsCollector->getAllProducts(),
            $this->openCartsCollector->getResult(),
            $this->newsletterSubscriptionCollection->getResult()
        ];

        foreach ($collectors as $collector) {
            $formatter->addResult($collector);
        }

        return $formatter;
    }
}
