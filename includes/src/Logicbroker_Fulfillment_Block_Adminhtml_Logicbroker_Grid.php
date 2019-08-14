<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */

class Logicbroker_Fulfillment_Block_Adminhtml_Logicbroker_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('logicbrokergrid');
      $this->setDefaultSort('vendor_id');
      $this->setDefaultDir('ASC');
	  $this->setUseAjax(true);
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('logicbroker/supplier')->getCollection()->addFieldToFilter('status',array('neq'=>'deleted'));
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('vendor_id', array(
          'header'    => Mage::helper('logicbroker')->__('Magento Id'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'vendor_id',
      ));
	  $this->addColumn('company_id', array(
          'header'    => Mage::helper('logicbroker')->__('logicbroker Id'),
          'align'     =>'right',
          'width'     => '50px',
          //'type'  => 'text',
          'index'     => 'company_id',
          //'options' => Mage::getModel('logicbroker/supplier')->getCompanyids(),
      ));
	  $this->addColumn('address1', array(
          'header'    => Mage::helper('logicbroker')->__('Address'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'address1',
      ));
	  
	 $this->addColumn('magento_vendor_code', array(
          'header'    => Mage::helper('logicbroker')->__('Vendor Attribute Value'),
          'align'     =>'left',
          'width'     => '80px',
          'index'     => 'magento_vendor_code',
      ));
	  
	  $this->addColumn('firstname', array(
          'header'    => Mage::helper('logicbroker')->__('Firstname'),
          'align'     =>'left',
          'width'     => '80px',
          'index'     => 'firstname',
      ));
	  
      $this->addColumn('lastname', array(
          'header'    => Mage::helper('logicbroker')->__('Lastname'),
          'align'     =>'left',
          'width'     => '80px',
          'index'     => 'lastname',
      ));
	  
      $this->addColumn('email', array(
          'header'    => Mage::helper('logicbroker')->__('Email'),
          'align'     =>'left',
          'width'     => '80px',
          'index'     => 'email',
      ));
	  $this->addColumn('phone', array(
          'header'    => Mage::helper('logicbroker')->__('Phone'),
          'align'     =>'left',
          'width'     => '80px',
          'index'     => 'phone',
      ));
	  
	  $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('logicbroker')->__('Action'),
                'width'     => '100',
                'type'      => 'textaction',
                'getter'    => 'getVendorId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('logicbroker')->__('edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'vendor_id'
                    ),
                    array(
                        'caption'   => Mage::helper('logicbroker')->__('delete'),
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'vendor_id',
                        'confirm' =>'Are you sure to delete vendor?'
                        
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
                'renderer' => 'Logicbroker_Fulfillment_Block_Adminhtml_Widget_Grid_Column_Renderer_Textaction'
        ));
	

        $this->addExportType('*/*/exportCsv', Mage::helper('logicbroker')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('logicbroker')->__('XML'));
	  
      return parent::_prepareColumns();
  }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('vendor_id' => $row->getVendorId()));
  }
 public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}