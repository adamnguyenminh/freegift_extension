<?php

namespace Levinci\FreeGift\Model\Quote\Address\Total;

use Levinci\FreeGift\Helper\ConfigData;
use Levinci\FreeGift\Model\FreeGift\Product\Type;
use Levinci\FreeGift\Model\FreeGift\ProductTypes\FreeGiftProduct;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Cart;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;

class FreeGift extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    protected $quoteValidator = null;

    private ConfigData $configData;

    private ProductRepositoryInterface $productRepository;

    private Type $freeGiftProductType;

    private Cart $cartManagement;

    /**
     * @param ConfigData $configData
     * @param ProductRepositoryInterface $productRepository
     * @param Type $freeGiftProductType
     * @param Cart $cartManagement
     */
    public function __construct(
        ConfigData $configData,
        ProductRepositoryInterface $productRepository,
        Type $freeGiftProductType,
        Cart $cartManagement
    ) {
        $this->configData = $configData;
        $this->productRepository = $productRepository;
        $this->freeGiftProductType = $freeGiftProductType;
        $this->cartManagement = $cartManagement;
    }

    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ): FreeGift {
        parent::collect($quote, $shippingAssignment, $total);

        try {
            $isFreeGiftEnabled = (bool) $this->configData->getModuleEnable();
            if (!$isFreeGiftEnabled) {
                return $this;
            }

            $address = $shippingAssignment->getShipping()->getAddress();
            if ($address->getAddressType() == 'billing'
                || $quote->getData('free_gift')
            ) {
                return $this;
            }

            $totals = array_sum($total->getAllTotalAmounts());
            $baseTotals = array_sum($total->getAllBaseTotalAmounts());
            $freeGiftProducts = $this->configData->getFreeGiftMapping($baseTotals);
            if (!$freeGiftProducts) {
                return $this;
            }
            $freeGiftProducts = explode(',', $freeGiftProducts);
            $param = [];
            $price = 0;
            foreach ($freeGiftProducts as $freeGiftProduct) {
                /** @var Product $product */
                $product = $this->productRepository->get($freeGiftProduct);
                $price += $product->getFinalPrice();
                $freeGiftProductType = $this->freeGiftProductType->factory($product);
                $freeGiftProductType->setCurrentProduct($product);
                $freeGiftProductType->setExchangeProductRepository($this->productRepository);
                $param = $freeGiftProductType->_prepareFreeGiftItemRequest($param);
                $this->cartManagement->addProduct(
                    $product,
                    $param
                );
            }
            $this->cartManagement->save();

            $balance = $price;

            $quote->setData('free_gift', $balance);
            $total->setTotalAmount('free_gift', -$balance);
            $total->setBaseTotalAmount('free_gift', -$balance);
            return $this;
        } catch (\Exception $exception) {
            return $this;
        }
    }

    public function fetch(Quote $quote, Total $total)
    {
        return [
            'code' => 'free_gift',
            'title' => 'Free Gift',
            'value' => 100
        ];
    }

    public function getLabel(): string
    {
        return __('Free Gift');
    }
}
