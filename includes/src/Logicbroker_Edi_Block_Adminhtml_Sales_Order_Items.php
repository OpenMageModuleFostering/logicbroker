<?php
/**
 * Abstract items renderer class overwrite for not displaying Tax percent if tax amt is 0.
 *
 * @category    Logicbroker
 * @package     Logicbroker_Edi
 * 
 */
class Logicbroker_Edi_Block_Adminhtml_Sales_Order_Items extends Mage_Adminhtml_Block_Sales_Order_View_Items_Renderer_Default
{
	  /**
     * Retrieve tax with persent html content
     *
     * @param Varien_Object $item
     * @return string
     */
    public function displayTaxPercent(Varien_Object $item)
    {
        if ($item->getTaxPercent() && $item->getTaxAmount() > 0) {
            return sprintf('%s%%', $item->getTaxPercent() + 0);
        } else {
            return '0%';
        }
    }
}