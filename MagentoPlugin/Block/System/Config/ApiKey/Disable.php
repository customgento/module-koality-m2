<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Block\System\Config\ApiKey;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field;

class Disable extends Field
{
    protected function _getElementHtml(AbstractElement $element): string
    {
        $element->setDisabled('disabled');

        return $element->getElementHtml();
    }
}
