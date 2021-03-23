<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Test\Integration\Controller;

use Koality\MagentoPlugin\Model\Config;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Form\FormKey;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 * @magentoAppArea adminhtml
 */
class ControllerApiTest extends AbstractBackendController
{
    /**
     * @var string
     */
    protected $uri = 'backend/koality/system_config/refreshApiKey';

    /**
     * @var string
     */
    protected $resource = 'Koality_MagentoPlugin::ApiKey';

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var FormKey
     */
    protected $formKey;

    protected function setUp(): void
    {
        parent::setUp();
        $objectManager = Bootstrap::getObjectManager();
        $this->config  = $objectManager->get(Config::class);
        $this->formKey = $objectManager->get(FormKey::class);
    }

    /**
     * @covers               \Koality\MagentoPlugin\Controller\Adminhtml\System\Config\RefreshApiKey::execute
     * @magentoDbIsolation   enabled
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store koality/api/key 19A35506-CA01-4BED-B5EC-4FDA8DF2AE58
     */
    public function testApiKeyRefreshesWhenControllerIsCalled(): void
    {
        $post = [
            'form_key' => $this->formKey->getFormKey()
        ];
        $this->getRequest()->setMethod(Http::METHOD_POST);
        $this->getRequest()->setPostValue($post);
        $apiKeyValueBeforeChange = $this->config->getApiKey();
        $this->dispatch('backend/koality/system_config/refreshApiKey?isAjax=true');
        self::assertNotEquals($apiKeyValueBeforeChange, $this->getResponse()->getContent());
    }

    public function testAclHasAccess(): void
    {
        $post = [
            'form_key' => $this->formKey->getFormKey()
        ];
        $this->getRequest()->setMethod(Http::METHOD_POST);
        $this->getRequest()->setPostValue($post);
        $this->uri .= '?isAjax=true';
        parent::testAclHasAccess();
    }

    /**
     * @inheritdoc
     */
    public function testAclNoAccess(): void
    {
        $post = [
            'form_key' => $this->formKey->getFormKey()
        ];
        $this->getRequest()->setMethod(Http::METHOD_POST);
        $this->getRequest()->setPostValue($post);
        $this->uri .= '?isAjax=true';
        parent::testAclNoAccess();
    }
}
