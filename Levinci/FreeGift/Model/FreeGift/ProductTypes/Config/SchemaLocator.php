<?php

namespace Levinci\FreeGift\Model\FreeGift\ProductTypes\Config;

use Magento\Framework\Config\SchemaLocatorInterface;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader;

class SchemaLocator implements SchemaLocatorInterface
{
    /**
     * XML schema for config file.
     */
    const CONFIG_FILE_SCHEMA = 'freegift_product_types.xsd';

    protected ?string $schema = null;

    protected ?string $perFileSchema = null;

    /**
     * @param Reader $moduleReader
     */
    public function __construct(Reader $moduleReader)
    {
        $configDir = $moduleReader->getModuleDir(Dir::MODULE_ETC_DIR, 'Levinci_FreeGift');
        $this->schema = $configDir . DIRECTORY_SEPARATOR . self::CONFIG_FILE_SCHEMA;
        $this->perFileSchema = $configDir . DIRECTORY_SEPARATOR . self::CONFIG_FILE_SCHEMA;
    }

    /**
     * @inheritDoc
     */
    public function getSchema(): ?string
    {
        return $this->schema;
    }

    /**
     * @inheritDoc
     */
    public function getPerFileSchema(): ?string
    {
        return $this->perFileSchema;
    }
}
