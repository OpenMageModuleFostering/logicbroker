<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */

class Logicbroker_Fulfillment_Model_Observer
{
public function connectToLogicbroker($object)
{
    
    $modelSupplier = Mage::getModel('logicbroker/supplier');
    $modelLogicbroker = Mage::getModel('logicbroker/logicbroker');
    $modelSync = Mage::getModel('logicbroker/synchronize');
    $modelEmail = Mage::getModel('logicbroker/email');
    $groupData = $object->getobject()->getGroups();
    
    
   
  
    
    foreach($groupData as $group=>$groupDataArray )
    $fieldsetData = array();
     foreach ($groupDataArray['fields'] as $field => $fieldData) {
                $fieldsetData[$field] = (is_array($fieldData) && isset($fieldData['value'])) ? $fieldData['value'] : null;
            }
    $modelLogicbroker->installConfig($fieldsetData); 
     if(Mage::app()->getRequest()->getParam('savelater'))
    {
       //no further processing only fields are saving in magento database
         return;
    }
    $modelSync->process($fieldsetData);
    $modelLogicbroker->updateSupplier();
    if(!Mage::getStoreConfig('logicbroker_integration/integration/notificationstatus'))
    $modelEmail->send(null,$fieldsetData);
    
    
}


}

?>
