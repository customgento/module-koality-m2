<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Controller\Koality;

//use Koality\MagentoPlugin\Model\ActiveProductsCollector;
use Koality\MagentoPlugin\Model\Config;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Symfony\Component\HttpFoundation\Response;
use Koality\MagentoPlugin\Model\OpenCartsCollector;
class Output extends Action
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var OpenCartsCollector
     */
    protected $collection;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    public function __construct(
        Context $context,
        Config $config,
        JsonFactory $resultJsonFactory,
        OpenCartsCollector $collection

    ) {
        parent::__construct($context);
        $this->config            = $config;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->collection        = $collection;
    }

    public function execute(): Json
    {
        /** @var Http $currentApiKey */
        $currentApiKey = $this->getRequest()->getParam('apikey');
        /** @var Redirect $resultRedirect */
        $resultPage = $this->resultJsonFactory->create();
        //TODO remove test call
        // $this->collection->getOpenCartCount();

        if ($currentApiKey === '') {
            $resultPage->setHttpResponseCode(Response::HTTP_INTERNAL_SERVER_ERROR);

            return $resultPage->setData(['error' => 'API key is missing. Please provide an API key and try again.']);
        }

        if ($currentApiKey !== $this->config->getApiKey()) {
            $resultPage->setHttpResponseCode(Response::HTTP_INTERNAL_SERVER_ERROR);

            return $resultPage->setData(['error' => 'API key does not match. Please check API key and try again.']);
        }

        $resultPage->setHttpResponseCode(Response::HTTP_OK);

        return $resultPage->setData(['success' => true]);

    }
}
