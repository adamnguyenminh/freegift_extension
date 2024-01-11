<?php

namespace Levinci\FreeGift\Model\FreeGift\ProductTypes;

class SimpleProduct extends \Levinci\FreeGift\Model\FreeGift\ProductTypes\FreeGiftProduct
{
    public function _prepareFreeGiftItemRequest(&$output)
    {
        $product = $this->getCurrentProduct();
        $output = [
            'product' => $product,
            'qty' => 1
        ];
        return $output;
    }
}
