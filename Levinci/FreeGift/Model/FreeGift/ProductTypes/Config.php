<?php

namespace Levinci\FreeGift\Model\FreeGift\ProductTypes;

use Magento\Framework\Config\Data;

class Config extends Data implements ConfigInterface
{
    /**
     * @inheritDoc
     */
    public function getType(string $name): array
    {
        return $this->get('types/' . $name, []);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->get('types');
    }
}
