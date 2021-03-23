<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Controller\Health;

use Koality\MagentoPlugin\Model\CollectorContainer;
use Koality\MagentoPlugin\Model\Config;
use Magento\Framework\App\Action\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Symfony\Component\HttpFoundation\Response;

class Status extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Koaliuty_MagentoPlugin::Koality_status';

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
        Context $context,
        Config $config,
        JsonFactory $resultJsonFactory,
        CollectorContainer $collectorContainer,
        RequestInterface $request
    ) {
        parent::__construct($context);
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

        $formatter = $this->collectorContainer->run();

        return $this->resultJsonFactory->create()
            ->setData($formatter->getFormattedResults());
    }
}
