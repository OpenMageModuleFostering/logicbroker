<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */

class Logicbroker_Fulfillment_Model_Excelxml{
    
    
   protected function prepareExportCollection()
    {
        $fieldSelect = Mage::helper('logicbroker')->getConfigObject('apiconfig/fieldstoexport/data');
        $selectFields =  array();
        foreach(explode(',',$fieldSelect) as $values)
        {
            $selectFields[] = $values;
           
        }
        $supplierCollection = Mage::getModel('logicbroker/supplier')->getCollection()->addFieldToFilter('status','')->addFieldToSelect($selectFields);
        $supplierData = $supplierCollection->getData();
        
        if(count($supplierData) > 0)
        {
          
        return $supplierData;
        }
        return 0;
    }
    
     public function getConfigObject($nodeName = null)
    {
        return Mage::getConfig()->getNode($nodeName);
    }
    
    protected function _getExportHeadersConfiguration($fieldsetData)
    {
       $headers = array();
       $mode = '';
       
       $baseUrl = Mage::getBaseUrl('web');
       
       if($baseUrl == $fieldsetData['your_production_store_url'])
       {
       $mode = Mage::getModel('logicbroker/logicbroker')->checkProductionMode($fieldsetData);
       }elseif($baseUrl == $fieldsetData['your_staging_store_url'])
       {
          $mode = 'staging'; 
       }else
       {
           $mode = 'staging'; 
       }
       
       
       $headers = array('companyid','key',$mode);
       
        return $headers;
}
    
    protected function _getExportRowsConfiguration($adapter,$parser,$fieldsetData)
 {
        $mode = '';
       
       $baseUrl = Mage::getBaseUrl('web');
       
       if($baseUrl == $fieldsetData['your_production_store_url'])
       {
        Mage::getModel('logicbroker/logicbroker')->checkProductionMode($fieldsetData);
       $mode = 'your_production_store_url';
       }elseif($baseUrl == $fieldsetData['your_staging_store_url'])
       {
          $mode = 'your_staging_store_url'; 
       }else
       {
        $mode = 'your_staging_store_url';   
       }
       $apiPassword = Mage::registry('api_password');
        $columns = array('MagentoAPIVersion' => Mage::getVersion(), 'MagentoStoreList' => 'stores', 'MagentoURL' => $mode, 'MagentoUserName' => 'api_user_name', 'MagentoUserPwd' => $apiPassword, 'MageStatus1' =>'processing_status', 'MageStatus2' => 'error_status', 'MageStatus3' => 'sentto_warehouse_status', 'MageVendorCode' => 'supplier_attribute');
        $temproray = array();


        foreach ($columns as $key => $val) {
            if($key == 'MagentoAPIVersion' || $key == 'MagentoUserPwd')
            {
               $temproray[] = array($fieldsetData['logicbroker_company_id'], $key, $val);  
            }elseif($key == 'MagentoURL'){
                $url = $fieldsetData[$val];
                //$append = (preg_match("/\/\z/i",$fieldsetData[$val]))?'index.php/v2_soap/index':'/index.php/v2_soap/index';
                $slash = (preg_match("/\/\z/i",$fieldsetData[$val]))?'':'/';
                $append = (preg_match("/v2_soap/i",$fieldsetData[$val]))?'':$slash.'index.php/v2_soap/index';
            $temproray[] = array($fieldsetData['logicbroker_company_id'], $key,$url.$append);
            }elseif($key == 'MagentoStoreList'){
                $temproray[] = array($fieldsetData['logicbroker_company_id'], $key, implode(',',$fieldsetData[$val]));
            }
            else{
                $temproray[] = array($fieldsetData['logicbroker_company_id'], $key, $fieldsetData[$val]);
            }
        }
//print_r($temproray);
//die();
        foreach ($temproray as $values) {
            $row = array();
            foreach ($values as $val) {
                $row[] = $val;
            }
            $data = $parser->getRowXml($row);
            $adapter->streamWrite($data);
            array_merge($row, $data);
        }
    }
    
    
     protected function _getExportHeadersSupplier()
    {
       $headers = array();
       
       
       foreach ($this->prepareExportCollection() as $keys => $values) {

            foreach($values as $key=>$val)
                if(!in_array($key,$headers))
                $headers[] = $key;
            
        }
       
        return $headers;
}
    
    protected function _getExportRowsSupplier($adapter,$parser)
    {
        foreach ($this->prepareExportCollection() as $keys => $values) {
            $row = array();
            foreach ($values as $val) {
               $row[] = $val;
            }
           $data = $parser->getRowXml($row);
           $adapter->streamWrite($data);
           array_merge($row,$data);
        } 
         
    }
    
    
    
    
    /**
     * Retrieve a file container array by grid data as MS Excel 2003 XML Document
     *
     * Return array with keys type and value
     *
     * @return string
     */
    public function getExcelFile($fieldsetData)
    {
        $sheetName =array('configurationproperties','systemvendor');
        if(!is_array($fieldsetData))
        {
            return false;
        }
       if($this->prepareExportCollection() > 0)
       {
       
        $parser = new Varien_Convert_Parser_Xml_Excel();
        $io     = new Varien_Io_File();

        $path = Mage::getBaseDir('var') . DS . 'export' . DS;
        $name = 'logicbroker_integration_'.date('YmdHis');
        $file = $path . DS . $name . '.xml';

        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $path));
        $io->streamOpen($file, 'w+');
        $io->streamLock(true);
        $io->streamWrite($parser->getHeaderXml($sheetName[0]));
        $io->streamWrite($parser->getRowXml($this->_getExportHeadersConfiguration($fieldsetData)));
        $this->_getExportRowsConfiguration($io,$parser,$fieldsetData);
        $io->streamWrite('</Table></Worksheet>');
        $io->streamWrite('<Worksheet ss:Name="'.$sheetName[1].'"><Table>');
        $io->streamWrite($parser->getRowXml($this->_getExportHeadersSupplier()));
        $this->_getExportRowsSupplier($io,$parser);
        $io->streamWrite($parser->getFooterXml());
        $io->streamUnlock();
        $io->streamClose();

        return array(
            'type'  => 'filename',
            'value' => $file,
            'rm'    => true // can delete file after use
        );
    }else{
        return false;
    }
    
    
    }

    
    
}

