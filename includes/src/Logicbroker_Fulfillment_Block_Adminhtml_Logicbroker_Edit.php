<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */
 
class Logicbroker_Fulfillment_Block_Adminhtml_Logicbroker_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        
                 
        $this->_objectId = 'vendor_id';
        $this->_blockGroup = 'logicbroker';
        $this->_controller = 'adminhtml_logicbroker';
        parent::__construct();
        
        $this->_updateButton('save', 'label', Mage::helper('logicbroker')->__('Save Vendor'));
        $this->_updateButton('delete', 'label', Mage::helper('logicbroker')->__('Delete Vendor'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        $this->_removeButton('delete');
        $this->_removeButton('reset');
         $script = $isUpdate='';
        if( Mage::registry('logicbroker_data') && Mage::registry('logicbroker_data')->getVendorId() ) 
        {
            $isUpdate = "if($('is_update')) $('is_update').value = '1';";
        }
        
        if(Mage::getModel('logicbroker/supplier')->isVendorSubmitted())
        {
            $message = '<p><ul class="messages"><li class="error-msg"><ul><li><span>'.$this->__('You have unsubmitted changes.').'</span></li></ul></li></ul></p>';
            $script = "$$('.main-col-inner .content-header')[0].insert({after:'{$message}'});";
        }
        $vendorAttributeId = Mage::getModel('logicbroker/supplier')->getVendorAttributeId();
        $link = Mage::helper('logicbroker')->getConfigObject('apiconfig/helpurl/link');
        
        $string = 'Enter new code here';
        //$blank = '';
        $this->_formScripts[] = "
                {$isUpdate}
                {$script}
                $('need_help').href = '{$link}';
                $('need_help').target = '_blank';
                $('need_help').insert('Contact Us');
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
                
            $('magento_vendor_attribute_id').value = '{$vendorAttributeId}';
            function checkisnew(value){
if(value == 'addnew'  && $('add_new_option') == null)
{
 $('magento_vendor_code').insert({
  after: new Element('input', {type: 'text',
name:'addnewoption',class:'input-text',id:'add_new_option',value:'{$string}',
onfocus:'if(this.value==\"{$string}\"){this.value=\"\"; this.addClassName(\"required-entry\")}; '
})
});
Form.Element.focus('add_new_option');
}else
{
    if($('add_new_option'))
{
    $('add_new_option').remove();
   
}
if($('advice-required-entry-add_new_option'))
{
 $('advice-required-entry-add_new_option').remove();
}
}
}
Validation.addAllThese([
		
    ['validate-ftp-url-logicbroker', 'Please enter a valid URL. Protocol is required (ftp://, ftps:// )', function (v) {
                v = (v || '').replace(/^\s+/, '').replace(/\s+$/, '');
                return Validation.get('IsEmpty').test(v) || /^(ftp|ftps):\/\/(([A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))(\.[A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))*)(:(\d+))?(\/[A-Z0-9~](([A-Z0-9_~-]|\.)*[A-Z0-9~]|))*\/?(.*)?$/i.test(v)
            }]
			]);
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('logicbroker_data') && Mage::registry('logicbroker_data')->getVendorId() ) {
            return Mage::helper('logicbroker')->__("Edit Vendor", $this->htmlEscape(Mage::registry('logicbroker_data')->getTitle()));
        } else {
            return Mage::helper('logicbroker')->__('Add Vendor');
        }
    }

}