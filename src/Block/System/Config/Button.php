<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\UrlInterface;

class Button extends Field
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    public function __construct(Context $context, UrlInterface $urlBuilder, array $data = [])
    {
        parent::__construct($context, $data);
        $this->urlBuilder = $urlBuilder;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('Koality_MagentoPlugin::system/config/api_refresh_button.phtml');

        return $this;
    }
    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->addData([
            'id'       => 'api_refresh',
            'label'    => __('Refresh API Key'),
            'ajax_url' => $this->urlBuilder->getUrl('koality/system_config/refreshApiKey'),
        ]);

        return $this->toHtml();
    }
}
