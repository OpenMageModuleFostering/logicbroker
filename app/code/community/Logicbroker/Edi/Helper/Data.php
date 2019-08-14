<?php
/**
 * This helper is to provide utility functions
 *
 * @category   Logicbroke
 * @package    Logicbroker_Edi
 * 
 */

class Logicbroker_Edi_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_LOGICBORKER_EDI_ENABLE = 'logicbroker_edi_section/logicbroker_edi_group2/enable_module';
	const XML_LOGICBORKER_EDI_APPLY_TAX = 'logicbroker_edi_section/logicbroker_edi_group2/apply_tax';
	
	/*
	 * Check if allowed to apply tax for orders generated through api
	 * 
	 * @return boolean
	 */
	public function canApplyTax(){
		if(Mage::getStoreConfig(self::XML_LOGICBORKER_EDI_APPLY_TAX)==0){
			return false;
		}
		return true;
	}
}
	 