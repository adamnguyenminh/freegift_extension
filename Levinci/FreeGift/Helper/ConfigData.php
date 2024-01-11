<?php

namespace Levinci\FreeGift\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class ConfigData extends AbstractHelper
{
    const LEVINCI_FREEGIFT_GENERAL_PATH = 'levinci_freegift/general/';

    const LEVINCI_FREEGIFT_GENERAL_MODULE_ENABLE = self::LEVINCI_FREEGIFT_GENERAL_PATH . 'enabled';

    const LEVINCI_FREEGIFT_GENERAL_MAPPING = self::LEVINCI_FREEGIFT_GENERAL_PATH . 'free_gift_mapping';

    private StoreManagerInterface $storeManager;

    private Json $serialize;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Json $serialize
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Json $serialize
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->serialize = $serialize;
    }

    /**
     * @param $path
     * @param $storeId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getConfigValue($path, $storeId = null): mixed
    {
        $storeId = is_null($storeId) ? $this->storeManager->getStore()->getId() : $storeId;
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param $storeId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getModuleEnable($storeId = null): mixed
    {
        return $this->getConfigValue(self::LEVINCI_FREEGIFT_GENERAL_MODULE_ENABLE, $storeId);
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getFreeGiftMapping(float $totalPrice, $storeId = null): string
    {
        if (!$this->getModuleEnable($storeId)) {
            return '';
        }

        $field = self::LEVINCI_FREEGIFT_GENERAL_MAPPING;
        $freeGiftMapping = $this->getConfigValue($field, $storeId) ?? [];
        $unSerializeData = $this->serialize->unserialize($freeGiftMapping);
        $results = '';
        foreach ($unSerializeData as $key => $row) {
            if (isset($row["condition_price"])) {
                continue;
            }
            if ($totalPrice >= $row["condition_price"]) {
                $results = $row["free_gift_sku"];
            }
        }

        return $results;
    }
}
