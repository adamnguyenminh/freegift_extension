<?php

namespace Levinci\FreeGift\Model\FreeGift\ProductTypes;

interface ConfigInterface
{
    /**
     * Get configuration of product type by name
     *
     * @param string $name
     * @return array
     */
    public function getType(string $name): array;

    /**
     * Get configuration of all registered product types
     *
     * @return array
     */
    public function getAll(): array;
}
