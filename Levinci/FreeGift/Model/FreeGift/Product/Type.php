<?php

namespace Levinci\FreeGift\Model\FreeGift\Product;

use Levinci\FreeGift\Model\FreeGift\ProductTypes\ConfigInterface;
use Levinci\FreeGift\Model\FreeGift\ProductTypes\FreeGiftProduct;
use Levinci\FreeGift\Model\FreeGift\ProductTypes\SimpleProduct;
use Magento\Framework\ObjectManagerInterface;

class Type
{
    const DEFAULT_TYPE = 'simple';

    const DEFAULT_TYPE_MODEL = SimpleProduct::class;

    /**
     * Product types
     *
     * @var array|null
     */
    protected ?array $_types = null;

    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $_config;

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    /**
     * @param ConfigInterface $config
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ConfigInterface $config,
        ObjectManagerInterface $objectManager
    ) {
        $this->_config = $config;
        $this->objectManager = $objectManager;
    }

    /**
     * @param $product
     * @return FreeGiftProduct|null
     */
    public function factory($product): ?FreeGiftProduct
    {
        try {
            $types = $this->getTypes();
            $typeId = $product->getTypeId();

            if (!empty($types[$typeId]['model'])) {
                $typeModelName = $types[$typeId]['model'];
            } else {
                $typeModelName = self::DEFAULT_TYPE_MODEL;
            }

            return $this->objectManager->get($typeModelName);
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Get product types
     *
     * @return array|string
     */
    public function getTypes(): array|string
    {
        if (is_null($this->_types)) {
            $productTypes = $this->_config->getAll();
            foreach ($productTypes as $productTypeKey => $productTypeConfig) {
                $productTypes[$productTypeKey]['label'] = __($productTypeConfig['label']);
            }
            $this->_types = $productTypes;
        }
        return $this->_types;
    }
}
