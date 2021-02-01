<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ActiveProductsCollector
{
    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    public function __construct(CollectionFactory $productCollectionFactory)
    {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function getActiveProductsCount()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('type_id', Type::TYPE_SIMPLE);

        return $collection;

    }

}
