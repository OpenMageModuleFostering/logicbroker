<?php


/**
 * Description of Createexcelxml
 *
 * @author shubhs
 */
 
class Logicbroker_Fulfillment_Model_Synchronize {
    
    protected $_modelResource = 'logicbroker';
    protected $_configNode = 'apiconfig/connectiontype/type';
    
    
    public function process($fieldsetData)
    {
        $model = $this->_getModelObject();
        try
        {
          $fileAttachment = $model->prepareData($fieldsetData);  
          if(!$model->send($fileAttachment,$fieldsetData))
          {
             Mage::throwException('Problem in sending attachment mail'); 
          }
            
        }catch(Exception $e)
        {
            return false;
        }
    }
    
    protected function _getModelObject()
    {
        $modelClass = $this->_modelResource;
        $type = Mage::helper('logicbroker')->getConfigObject($this->_configNode);
        
        //die($modelClass.'/'.$type);
        return Mage::getModel($modelClass.'/'.$type);        
    }
    
    
  }

?>
