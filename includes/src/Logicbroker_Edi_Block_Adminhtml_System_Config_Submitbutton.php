<?php
/**
 * To make SOAP details field in module configuration section as readonly
 *
 * @category    Logicbroker
 * @package     Logicbroker_Edi
 */
class Logicbroker_Edi_Block_Adminhtml_System_Config_Submitbutton extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
    	$element->setReadonly('readonly');
    	return parent::_getElementHtml($element);
    }

}
