<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Controller\Health;

use Koality\MagentoPlugin\Model\CollectorContainer;
use Koality\MagentoPlugin\Model\Config;
use Koality\MagentoPlugin\Model\Formatter\KoalityFormatter;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Symfony\Component\HttpFoundation\Response;

class Status implements HttpGetActionInterface
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

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        Config $config,
        JsonFactory $resultJsonFactory,
        CollectorContainer $collectorContainer,
        RequestInterface $request

    ) {
        $this->config             = $config;
        $this->resultJsonFactory  = $resultJsonFactory;
        $this->collectorContainer = $collectorContainer;
        $this->request            = $request;
    }

    public function execute(): Json
    {
        /** @var Http $currentApiKey */
        $currentApiKey = $this->request->getParam('apikey');
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

        $formatter = $this->collectResults();

        return $formatter->getFormattedResults();

    }

    private function collectResults(): KoalityFormatter
    {
        return $this->collectorContainer->run();
    }

}
