<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Controller\Adminhtml\System\Config;

use Koality\MagentoPlugin\Model\ApiKey;
use Koality\MagentoPlugin\Model\Config;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\ResponseInterface;

class RefreshApiKey extends Action
{
    public const ADMIN_RESOURCE = 'Koality_MagentoPlugin::ApiKey';

    /**
     * @var ApiKey
     */
    private $apiKey;

    /**
     * @var WriterInterface
     */
    private $configWriter;

    public function __construct(
        Context $context,
        ApiKey $apiKey,
        WriterInterface $configWriter
    ) {
        parent::__construct($context);
        $this->apiKey       = $apiKey;
        $this->configWriter = $configWriter;
    }

    public function execute(): ResponseInterface
    {
        $newApiKey = $this->apiKey->createRandomApiKey();
        $this->configWriter->save(Config::API_KEY, $newApiKey);

        return $this->getResponse()->setBody($newApiKey);
    }
}
