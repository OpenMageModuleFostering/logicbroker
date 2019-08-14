<?php
/**
 * rewrite to allow tax for api orders based on edi module setttings
 *
 * @category    Logicbroker
 * @package     Logicbroker_Edi
 */

/**
 * Tax totals calculation model
 */
class Logicbroker_Edi_Model_Sales_Total_Quote_Tax extends Mage_Tax_Model_Sales_Total_Quote_Tax
{
    /**
     * Collect tax totals for quote address
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Mage_Tax_Model_Sales_Total_Quote
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
		$sessionName = Mage::getSingleton('core/session')->getSessionName();
		if($sessionName=='adminhtml' || $sessionName=='frontend'){		
			parent::collect($address);			
		}else{
			if(Mage::helper('edi')->canApplyTax()){
				parent::collect($address);
			}
		}
    }
}
