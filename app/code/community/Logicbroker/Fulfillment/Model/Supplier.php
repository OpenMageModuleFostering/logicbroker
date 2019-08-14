<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */

class Logicbroker_Fulfillment_Model_Supplier extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("logicbroker/supplier");
    }
	
	public function getCompanyids() {
		$collection = Mage::getModel('logicbroker/supplier')->getCollection();       
		$collection = Mage::getModel('logicbroker/supplier')
					->getCollection()
					->addFieldToSelect('company_id');
		$data = $collection->getData();
		$options = array();
		foreach($data as $newdata) {
			if(!empty($newdata['company_id'])) {
				$options[] = $newdata['company_id'];
			}
		}
		return $options;
	}
	
	public function getCountry() {
		$countries = Mage::getModel('adminhtml/system_config_source_country')->toOptionArray();
		$options = array();
		foreach ($countries as $country) {
			if(!empty($country['value'])) {
				$options[$country['value']] = $country['label'];
			}
		}
		return $options;
	}
	
	public function validateCompany($companyid,$supplierId) {
		$flag = 0;
                if(empty($companyid) || empty($supplierId))
                {
                    return $flag;
                }
		$collection = Mage::getModel('logicbroker/supplier')
				->getCollection()
				->addFieldToFilter('company_id', $companyid)
                                ->addFieldToFilter('status',array('neq'=>'deleted'))
				->addFieldToSelect('company_id')
				->addFieldToSelect('vendor_id');
		$data = $collection->getData();
		if(array_key_exists(0,$data) and $data[0]['company_id'] != null) {
			if($data[0]['vendor_id'] != $supplierId) {				
				return $flag = 1;
			}else
                        {
                            return $flag;
                        }
		} 

    }
    
    public function getVendorAttributeId()
    {
        $attributeCode = Mage::getStoreConfig('logicbroker_integration/integration/supplier_attribute');
        //$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product',$attributeCode);
        if($attributeCode != null)
        {
            return $attributeCode;
        }else
        {
            return '';
        }
      
        
    }
    
    public function deleteOption($attributeCode,$optionvalue) {
        //$attributeCode = Mage::getStoreConfig('logicbroker_integration/integration/supplier_attribute');
        if ($attributeCode) {
            
            $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeCode);
            $collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                    ->setAttributeFilter($attribute->getId())
                    ->setStoreFilter($attribute->getStoreId())
                    ->load();
            //echo '<pre>';
            //die(print_r($collection->getData()));
            foreach ($collection as $option) {
                if($option['option_id'] == $optionvalue)
                $option->delete();
            }
        } else {
           return ; 
        }
    }
    //check if information is submitted to logicbroker
    public function isVendorSubmitted()
    {
        $objId = Mage::app()->getRequest()->getParam('vendor_id');
                if(!empty($objId)){
         $logicbrokerCollection = $this->getCollection()
                ->addFieldToFilter(array('is_update', 'verified'), array('1', '0'))
                ->addFieldToFilter('status','')
                // ->addFieldToFilter('vendor_id',$objId)
                ->addFieldToSelect(array('company_id', 'magento_vendor_code'));

        //return $logicbrokerCollection->getSelect()->__toString();
        $comapnyIdArray = $logicbrokerCollection->getData();
        if (count($comapnyIdArray) > 0) {
            return true;
        } else {
            return false;
        }
                }else
                {
                    return false;
                }
                
    }
}
	 