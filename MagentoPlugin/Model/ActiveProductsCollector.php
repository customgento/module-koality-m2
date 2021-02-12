<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Model\Formatter\Result;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class ActiveProductsCollector
{
    private array $pluginConfig = [];

    /**
     * @var ProductCollectionFactory
     */
    private ProductCollectionFactory $productCollectionFactory;

    public function __construct(ProductCollectionFactory $productCollectionFactory)
    {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function getAllProducts(): Result
    {
        //TODO check this variable against original

        $activeProductCount = $this->getActiveProductsCount();
        if (array_key_exists('activeProducts', $this->pluginConfig)) {
            $minOpenProjects = $this->pluginConfig['activeProducts'];
        } else {
            $minOpenProjects = 0;
        }

        if ($activeProductCount < $minOpenProjects) {
            $cartResult = new Result(Result::STATUS_FAIL, Result::KEY_PRODUCTS_ACTIVE,
                'There are too few active products in your shop.');
        } else {
            $cartResult = new Result(Result::STATUS_PASS, Result::KEY_PRODUCTS_ACTIVE,
                'There are enough active products in your shop.');
        }

        $cartResult->setLimit($minOpenProjects);
        $cartResult->setObservedValue($activeProductCount);
        $cartResult->setObservedValueUnit('products');
        $cartResult->setObservedValuePrecision(0);
        $cartResult->setLimitType(Result::LIMIT_TYPE_MIN);
        $cartResult->setType(Result::TYPE_TIME_SERIES_NUMERIC);

        return $cartResult;
    }

    /**
     * Return the number of active products.
     *
     * @return int
     */
    private function getActiveProductsCount(): int
    {
        $collection = $this->productCollectionFactory->create()->addAttributeToSelect('*')
            ->addAttributeToFilter('status', Status::STATUS_ENABLED);

        return $collection->getSize();

    }
}
