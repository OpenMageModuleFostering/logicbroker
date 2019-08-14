<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */
 
class Logicbroker_Fulfillment_Block_Adminhtml_Logicbroker_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{


  public function __construct()
  {
      parent::__construct();
      $this->setId('logicbroker_tabs');
      $this->setDestElementId('edit_form');
     $this->setTitle(Mage::helper('logicbroker')->__('Vendor Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('logicbroker')->__('Vendor Information'),
          'title'     => Mage::helper('logicbroker')->__('Vendor Information'),
          'content'   => $this->getLayout()->createBlock('logicbroker/adminhtml_logicbroker_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}