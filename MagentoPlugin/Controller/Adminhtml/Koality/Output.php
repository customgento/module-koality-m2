<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Controller\Adminhtml\Koality;

use Koality\MagentoPlugin\Model\Config;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

class Output extends Action implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var MessageManagerInterface
     */
    protected $messageManager;

    public function __construct(
        Action\Context $context,
        Config $config,
        RequestInterface $request,
        RedirectFactory $resultRedirectFactory,
        MessageManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->config                = $config;
        $this->request               = $request;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager        = $messageManager;
    }

    public function execute()
    {
        /** @var Json $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!empty($this->config->getApiKey())) {
            return $result->setData(['success' => true]);
        }

        $this->messageManager->addErrorMessage('Access Denied. Please provide an API Key');

        return $resultRedirect->setPath('*/*/');

    }
}
