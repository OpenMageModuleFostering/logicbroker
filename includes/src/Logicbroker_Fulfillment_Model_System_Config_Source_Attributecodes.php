<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Attributecode
 *
 * @author shubhs
 */

class Logicbroker_Fulfillment_Model_System_Config_Source_Attributecodes {

    public function toOptionArray() {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
                ->getItems();
        $attributeCode = array();
        foreach ($attributes as $attribute) {

            if ($attribute->getFrontendInput() == 'select' && $attribute->getIsUserDefined()) {
                $attributeCode[] = array('value' => $attribute->getAttributecode(), 'label' => Mage::helper('logicbroker')->__($attribute->getFrontendLabel()));
            }
        }
        array_unshift($attributeCode,array('value' => '', 'label' => Mage::helper('logicbroker')->__('--Please Select--')));
        return $attributeCode;
    }
}


