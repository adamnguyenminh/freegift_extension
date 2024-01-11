<?php

namespace Levinci\FreeGift\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class FreeGiftMapping extends AbstractFieldArray
{
    /**
     * @inheritDoc
     */
    protected function _prepareToRender()
    {
        $this->addColumn('condition_price', ['label' => __('Condition Price'), 'class' => 'required-entry']);
        $this->addColumn('free_gift_sku', ['label' => __('Free-Gift Product SKU'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * @inheritDoc
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $options = [];
        $row->setData('option_extra_attrs', $options);
    }
}
