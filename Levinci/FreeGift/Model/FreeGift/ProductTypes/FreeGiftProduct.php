<?php

namespace Levinci\FreeGift\Model\FreeGift\ProductTypes;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;

abstract class FreeGiftProduct
{
    private ProductRepositoryInterface $productRepository;

    private Product $product;

    /**
     * @param Product $product
     * @return $this
     */
    public function setCurrentProduct(Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return Product
     */
    public function getCurrentProduct(): Product
    {
        return $this->product;
    }


    /**
     * @param ProductRepositoryInterface $productRepository
     * @return $this
     */
    public function setExchangeProductRepository(ProductRepositoryInterface $productRepository): static
    {
        $this->productRepository = $productRepository;
        return $this;
    }

    /**
     * @return ProductRepositoryInterface
     */
    public function getExchangeProductRepository(): ProductRepositoryInterface
    {
        return $this->productRepository;
    }

    abstract public function _prepareFreeGiftItemRequest(&$output);
}
