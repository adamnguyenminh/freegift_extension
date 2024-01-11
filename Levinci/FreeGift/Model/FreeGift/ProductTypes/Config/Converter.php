<?php

namespace Levinci\FreeGift\Model\FreeGift\ProductTypes\Config;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * @inheritDoc
     */
    public function convert($source): array
    {
        $output = [];
        $xpath = new \DOMXPath($source);
        $types = $xpath->evaluate('/config/type');
        /** @var $typeNode \DOMNode */
        foreach ($types as $typeNode) {
            $typeName = $this->_getAttributeValue($typeNode, 'name');
            $isComposite = $this->_getAttributeValue($typeNode, 'composite', 'false');
            $isDecimal = $this->_getAttributeValue($typeNode, 'canUseQtyDecimals', 'true');
            $isQty = $this->_getAttributeValue($typeNode, 'isQty', 'false');
            $data = [];
            $data['name'] = $typeName;
            $data['label'] = $this->_getAttributeValue($typeNode, 'label', '');
            $data['model'] = $this->_getAttributeValue($typeNode, 'modelInstance');
            $data['composite'] = !empty($isComposite) && 'false' !== $isComposite;
            $data['can_use_qty_decimals'] = !empty($isDecimal) && 'false' !== $isDecimal;
            $data['is_qty'] = !empty($isQty) && 'false' !== $isQty;
            $data['sort_order'] = (int)$this->_getAttributeValue($typeNode, 'sortOrder', 0);

            /** @var $childNode \DOMNode */
            foreach ($typeNode->childNodes as $childNode) {
                if ($childNode->nodeType != XML_ELEMENT_NODE) {
                    continue;
                }

                switch ($childNode->nodeName) {
                    case 'allowedSelectionTypes':
                        /** @var $selectionsTypes \DOMNode */
                        foreach ($childNode->childNodes as $selectionsTypes) {
                            if ($selectionsTypes->nodeType != XML_ELEMENT_NODE) {
                                continue;
                            }
                            $name = $this->_getAttributeValue($selectionsTypes, 'name');
                            $data['allowed_selection_types'][$name] = $name;
                        }
                        break;
                }
            }
            $output['types'][$typeName] = $data;
        }

        return $output;
    }

    /**
     * Get attribute value
     *
     * @param \DOMNode $input
     * @param string $attributeName
     * @param string|null $default
     * @return null|string
     */
    protected function _getAttributeValue(\DOMNode $input, string $attributeName, ?string $default = null): ?string
    {
        $node = $input->attributes->getNamedItem($attributeName);
        return $node ? $node->nodeValue : $default;
    }
}
