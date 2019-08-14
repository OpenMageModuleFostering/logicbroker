<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */

class Logicbroker_Fulfillment_Block_Adminhtml_Logicbroker_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('logicbroker_form', array('legend'=>Mage::helper('logicbroker')->__('Vendor Information')));
        //$data = $form->setValues(Mage::registry('logicbroker_data')->getData());
     
     $fieldset->addField('is_update', 'hidden', array(
          'name'      => 'is_update',
      ));
      $fieldset->addField('company_id', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('logicbroker Company ID'),
          'name'      => 'company_id',
          'note'=>'From the logicbroker portal - optional if you do not have an account'
           
      ));
      $fieldset->addField('address1', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('Address 1'),
          'class'     => 'required-entry',
          'required'  => true,  
          'name'      => 'address1',
      ));
      $fieldset->addField('address2', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('Address 2'),
          'name'      => 'address2',
      ));
     $fieldset->addField('city', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('City'),
          'class'     => 'required-entry',
          'required'  => true, 
          'name'      => 'city',
      )); 
     $fieldset->addField('state', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('State'),
          'class'     => 'required-entry',
          'required'  => true,  
          'name'      => 'state',
      )); 
     $fieldset->addField('zip', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('Zip'),
          'class'     => 'required-entry validate-digits',
          'required'  => true, 
          'name'      => 'zip',
      )); 
    $fieldset->addField('country', 'select', array(
          'label'     => Mage::helper('logicbroker')->__('Country'),
          'class'     => 'required-entry validate-select',
          'required'  => true, 
          'name'      => 'country',
		  'values'    => Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(), 
		  'default' => ''
      )); 
    $fieldset->addField('edi_qualifier', 'select', array(
          'label'     => Mage::helper('logicbroker')->__('EDI Qualifier'),
          'class'     => 'required-entry validate-select',
          'required'  => true, 
          'name'      => 'edi_qualifier',
          'values'    => Mage::getModel('logicbroker/system_config_source_ediqualifier')->toOptionArray(), 
		  'default' => ''
      )); 	 
	$fieldset->addField('edi_identifier', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('EDI Identifier'),
          'class'     => 'required-entry',
          'required'  => true,  
          'name'      => 'edi_identifier',
      ));
        
        $fieldset->addField('magento_vendor_attribute_id', 'hidden', array(
          'name'      => 'magento_vendor_attribute_id',
            
          
      )); 
	$fieldset->addField('magento_vendor_code', 'select', array(
          'label'     => Mage::helper('logicbroker')->__('Vendor Code'),
          'class'     => 'required-entry validate-select',
          'required'  => true,
          'onchange' => 'checkisnew(value)',
          'name'      => 'magento_vendor_code',
            'values'    => Mage::getModel('logicbroker/system_config_source_Optionvalues')->toOptionArray(), 
		  'default' => ''
      )); 
	$fieldset->addField('file_directory_inbound', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('File Directory Inbound'),
          'class'     => 'required-entry',
          'required'  => true,  
          'name'      => 'file_directory_inbound',
      )); 
	$fieldset->addField('file_directory_outbound', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('File Directory Outbound'),
          'class'     => 'required-entry',
          'required'  => true, 
          'name'      => 'file_directory_outbound',
      ));
	$fieldset->addField('ftp_address', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('FTP Address'),
          'class'     => 'required-entry validate-ftp-url-logicbroker',
          'required'  => true, 
          'name'      => 'ftp_address',
      ));
	$fieldset->addField('ftp_username', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('FTP Username'),
          'class'     => 'required-entry',
          'required'  => true,  
          'name'      => 'ftp_username',
      ));
	$fieldset->addField('ftp_password', 'password', array(
          'label'     => Mage::helper('logicbroker')->__('FTP Password'),
          'class'     => 'required-entry',
          'required'  => true, 
          'name'      => 'ftp_password',
      ));
        $fieldset->addField('ftp_protocol', 'select', array(
          'label'     => Mage::helper('logicbroker')->__('FTP Protocol'),
          'class'     => 'required-entry validate-select',
          'required'  => true, 
          'name'      => 'ftp_protocol',
          'values' =>array(
            array('value' => '', 'label'=>Mage::helper('adminhtml')->__('')),  
            array('value' => 'ftp', 'label'=>Mage::helper('adminhtml')->__('FTP')),
            array('value' => 'ftpes', 'label'=>Mage::helper('adminhtml')->__('FTPES')),
        ),
            
      ));
	$fieldset->addField('firstname', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('Contact First Name'),
          'class'     => 'required-entry',
          'required'  => true,  
          'name'      => 'firstname',
      ));
	$fieldset->addField('lastname', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('Contact Last Name'),
          'class'     => 'required-entry',
          'required'  => true, 
          'name'      => 'lastname',
      ));	
	$fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('Contact Email'),
          'class'     => 'required-entry validate-email',
          'required'  => true, 
          'name'      => 'email',
      ));	
	$fieldset->addField('phone', 'text', array(
          'label'     => Mage::helper('logicbroker')->__('Contact Phone'),
          'class'     => 'required-entry validate-phoneStrict',
          'required'  => true, 
          'name'      => 'phone',
      ));
        
        $fieldset->addField('need_help', 'link', array(
            'label' => $this->__('Need Help?'),
            'value'      => ''
             
        ));
	
      if ( Mage::getSingleton('adminhtml/session')->getLogicbrokerData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getLogicbrokerData());
          Mage::getSingleton('adminhtml/session')->setLogicbrokerData(null);
      } elseif ( Mage::registry('logicbroker_data') ) {
          $form->setValues(Mage::registry('logicbroker_data')->getData());
      }
      return parent::_prepareForm();
  }
}