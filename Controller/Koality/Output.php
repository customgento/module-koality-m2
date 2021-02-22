<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Controller\Koality;

use Koality\MagentoPlugin\Model\Config;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Symfony\Component\HttpFoundation\Response;
use Koality\MagentoPlugin\Model\CollectorContainer;

class Output extends Action
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var CollectorContainer
     */
    private $collectorContainer;

    public function __construct(
        Context $context,
        Config $config,
        JsonFactory $resultJsonFactory,
        CollectorContainer $collectorContainer

    ) {
        parent::__construct($context);
        $this->config             = $config;
        $this->resultJsonFactory  = $resultJsonFactory;
        $this->collectorContainer = $collectorContainer;
    }

    public function execute(): Json
    {
        /** @var Http $currentApiKey */
        $currentApiKey = $this->getRequest()->getParam('apikey');
        /** @var Redirect $resultRedirect */
        $resultPage = $this->resultJsonFactory->create();
        if ($currentApiKey === '') {
            $resultPage->setHttpResponseCode(Response::HTTP_INTERNAL_SERVER_ERROR);

            return $resultPage->setData(['error' => 'API key is missing. Please provide an API key and try again.']);
        }

        if ($currentApiKey !== $this->config->getApiKey()) {
            $resultPage->setHttpResponseCode(Response::HTTP_INTERNAL_SERVER_ERROR);

            return $resultPage->setData(['error' => 'API key does not match. Please check API key and try again.']);
        }

        $resultPage->setHttpResponseCode(Response::HTTP_OK);

        return $resultPage->setData([$this->collectorContainer->run()]);

    }

}
