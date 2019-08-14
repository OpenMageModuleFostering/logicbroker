<?php

/**
 * To get logicbroker portal link in module configuration section
 * 
 * @category    Logicbroker
 * @package     Logicbroker_Edi
 */
class Logicbroker_Edi_Block_Adminhtml_System_Config_Singuplink extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$url = 'https://portal.logicbroker.com/';
		$html = parent::_getElementHtml($element);
		$html .= "<a href='{$url}' target='_blank' title='Logicbroker_Edi'>Sign up here</a>";
		return $html;
	}
}
