<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Controller\Adminhtml\System\Config;

use Koality\MagentoPlugin\Model\ApiKey;
use Koality\MagentoPlugin\Model\Config;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\Storage\WriterInterface;

class RefreshApiKey extends Action
{
    //TODO add admin resource
    /**
     * @var ApiKey
     */
    private $apiKey;

    /**
     * @var WriterInterface
     */
    private $configWriter;

    protected $resultFactory;

    public function __construct(
        Context $context,
        ApiKey $apiKey,
        WriterInterface $configWriter
    ) {
        parent::__construct($context);
        $this->apiKey       = $apiKey;
        $this->configWriter = $configWriter;
    }

    public function execute(): void
    {
        $newApiKey = $this->apiKey->createRandomApiKey();
        $this->configWriter->save(Config::KOALITY_API_KEY, $newApiKey);
        $this->getResponse()->setBody($newApiKey);
    }
}
