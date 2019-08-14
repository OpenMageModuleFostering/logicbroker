<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */
class Logicbroker_Fulfillment_Block_Adminhtml_Logicbroker extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_logicbroker';
    $this->_blockGroup = 'logicbroker';
    $this->_headerText = Mage::helper('logicbroker')->__('Vendor Manager');
    $this->_addButtonLabel = Mage::helper('logicbroker')->__('Add Vendor');
    parent::__construct();
  }
}
