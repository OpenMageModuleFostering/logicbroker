<?php

/**
 * Adminhtml grid item renderer
 *
 * @category   Orange
 * @package    Orange_Prepaidcards
 * @author     Anil Pawar <anilpa@cybage.com>
 */

class Logicbroker_Fulfillment_Block_Adminhtml_Widget_Grid_Column_Renderer_Textaction extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
  
	/**
     * Renders column
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $actions = $this->getColumn()->getActions();
        if ( empty($actions) || !is_array($actions) ) {
            return '&nbsp;';
        }
		$out="";
        if(!$this->getColumn()->getNoLink()) {
            foreach ($actions as $action) {
                if ( is_array($action) ) {
                  $out .= "&nbsp;&nbsp;&nbsp;".$this->_toLinkHtml($action, $row);
                }
            }
        }
		return $out;
        
    }
}
