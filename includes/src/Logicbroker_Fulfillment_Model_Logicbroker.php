<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */

class Logicbroker_Fulfillment_Model_Logicbroker {
    /* Validate the user inputs */

    public function validation($request) {
        $result = array();
        if (!is_array($request)) {
            return $result[] = array('error' => 'Something wrong with request');
        }

        if (!$this->_checkRequestParams($request)) {
            return $result[] = array('error' => 'Please fill all field mark with astrik sign');
        }

        if (!$this->_checkAttribute($request)) {
            $value = $request['supplier_attribute'];
            return $result[] = array('error' => 'Vendor attribute code "' . $value['value'] . '" is not defined');
        }
        if (!$this->checkAtLeastOneSupplier()) {
            return $result[] = array('error' => 'At least one vendor information available to share with logicbroker');
        }
         if(Mage::app()->getRequest()->getParam('savelater'))
        {
       //no further processing only fields are saving in magento database
         return $result[] = array('success' => 'Data has been validate successfully.... ');
        }
        return $result[] = array('success' => 'Please wait while transmitting your configuration to logicbroker');
    
        
    }

    public function checkAtLeastOneSupplier() {
        $logicbrokerCollection = Mage::getModel('logicbroker/supplier')->getCollection();
                //->addFieldToFilter(array('is_update', 'verified'), array('1', '0'))
                //->addFieldToSelect(array('company_id', 'magento_vendor_code'));

        //return $logicbrokerCollection->getSelect()->__toString();
        $comapnyIdArray = $logicbrokerCollection->getData();
        if (count($comapnyIdArray) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*  check that all listed field will not be empty in integration form 
     *  username
     *  password
     *  production Url >> regx check
     *  staging Url >> regx check
     *  Api username >> regx check only alpha
     *  supplier Attribute
     * 
     */

    protected function _checkRequestParams($request) {
        $values = array('logic_broker_username', 'logic_broker_password', 'your_production_store_url', 'your_staging_store_url', 'api_user_name', 'supplier_attribute');

        foreach ($request as $key => $val) {
            if (in_array($key, $values) && $val['value'] == '') {

                return false;
            }
        }
        return true;
    }

    protected function _checkAttribute($request) {
        $supplierAttributeCode = $request['supplier_attribute'];
        $attributeDetails = Mage::getSingleton("eav/config")->getAttribute("catalog_product", $supplierAttributeCode['value']);

        if ($attributeDetails->getId() && $attributeDetails->getFrontendInput() == 'select') {
            return true;
        } else {
            return false;
        }
    }

    public function checkProductionMode($request) {
        if (!is_array($request)) {
            return false;
        }
        $salseOderItemTable = Mage::getSingleton('core/resource')->getTableName('sales/order_item');
        $orders = Mage::getResourceModel('sales/order_collection')->addAttributeTofilter('status', 'complete');
        $orders->getSelect()->join(array('item' => $salseOderItemTable), 'item.order_id = main_table.entity_id and item.product_type = "simple"', array('item.sku'));
        $productCollection = Mage::getResourceModel('catalog/product_collection');
        $skus = array();
        foreach ($orders as $order) {
            $skus[] = $order->getSku();
        }


        $productCollection->addAttributeToFilter('sku', array('in' => $skus))->addAttributeToFilter(trim($request['supplier_attribute']), array('notnull' => true));
        if (count($productCollection) > 0) {
            return 'production';
        } else {
            return 'qa';
        }
    }

    public function installConfig($fieldsetData) {
        if (!$this->createApiRoleAndUser($fieldsetData)) {
            Mage::throwException('Problem in creating API role and user');
        }

        if (!$this->attributeOptionValidation($fieldsetData)) {
            Mage::throwException('Problem in validating attribute options');
        }

        if (!$this->checkAndCreateOrderStatus($fieldsetData)) {
            Mage::throwException('Problem in validating order status');
        }
        return true;
    }

    /* Creae API role */

    public function createApiRoleAndUser($fieldsetData) {

        $role = Mage::getModel('api/roles');
        $roleName = 'logicbroker';
        $parentId = '';
        $roleType = 'G';
        $ruleAsString = Mage::helper('logicbroker')->getConfigObject('apiconfig/rolepermission/data');
        $ruleNodes = array();

        if (!is_array($fieldsetData)) {
            return false;
        }
        foreach (explode(',', $ruleAsString) as $rules) {
            $ruleNodes[] = trim($rules);
        }
        $role->load($roleName, 'role_name');

        try {
            $role = $role->setName($roleName)
                    ->setPid($parentId)
                    ->setRoleType($roleType)
                    ->save();

            Mage::getModel("api/rules")->setRoleId($role->getId())
                    ->setResources($ruleNodes)
                    ->saveRel();
        } catch (Exception $e) {
            return false; //$e->getMessage();
        }
        //$password = Mage::helper('core')->decrypt(Mage::getStoreConfig('logicbroker_integration/integration/api_password'));
        $password = $fieldsetData['api_password'];
        $userExist = $this->_userExists($fieldsetData['api_user_name'],$fieldsetData['email']);
        $userId = '';
        if (is_array($userExist)) {
          $modelData =  Mage::getModel('api/user')->load($userExist[1]);
          $userId = $modelData->getUserId();
        }else
        {
          $modelData =  Mage::getModel('api/user');  
        }
        
        $modelData->setData(array(
            'user_id'=> $userId,
            'username' => $fieldsetData['api_user_name'],
            'firstname' => $fieldsetData['firstname'],
            'lastname' => $fieldsetData['lastname'],
            'email' => $fieldsetData['email'],
            'api_key' => $password,
            'api_key_confirmation' => $password,
            'is_active' => 1,
            'user_roles' => '',
            'assigned_user_role' => '',
            'role_name' => '',
            'roles' => array($role->getId()) // your created custom role
                ));

        try {
            
                Mage::register('api_password', $modelData->getApiKey());
                $modelData->save();
                $modelData->setRoleIds(array($role->getId()))  // your created custom role
                    ->setRoleUserId($modelData->getUserId())
                    ->saveRelations();
            }
            
         catch (Exception $e) {
            return false;//$e->getMessage();
        }
        return true;
    }
    
    protected function _userExists($username,$email)
    {
        $resource = Mage::getSingleton('core/resource');
        $usersTable = $resource->getTableName('api/user');
        $adapter    = $resource->getConnection('core_read');
        $condition  = array(
            $adapter->quoteInto("{$usersTable}.username = ?", $username),
            $adapter->quoteInto("{$usersTable}.email = ?", $email),
        );
        $select = $adapter->select()
            ->from($usersTable)
            ->where(implode(' OR ', $condition))
            ->where($usersTable.'.user_id != ?', '');
        $result =  $adapter->fetchRow($select);
        if(is_array($result) && count($result) > 0 )
        {
            return array(true,(int)$result['user_id']); 
        }else
        {
            return false;
        }
        
    }

    protected function _generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    /* Creae Atrribute option if not available */

    public function attributeOptionValidation($fieldsetData) {
        if (!is_array($fieldsetData)) {
            return false;
        }

        $logicbrokerCollection = Mage::getModel('logicbroker/supplier')->getCollection()
                ->addFieldToFilter(array('is_update', 'verified'), array('1', '0',''))->addFieldToFilter('status','')
                ->addFieldToSelect(array('company_id', 'magento_vendor_code'));

        //return $logicbrokerCollection->getSelect()->__toString();
        $comapnyIds = $logicbrokerCollection->getData();
        if (count($comapnyIds) > 0) {
            foreach ($comapnyIds as $key => $value) {
                if (!$this->_checkAndCreateOptions($value['magento_vendor_code'],$fieldsetData['supplier_attribute'])) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function _checkAndCreateOptions($value, $fieldsetData) {

        $attributeLables = array();
        $optionToBeSearch = strtolower($value);
        $attributeCode = strtolower($fieldsetData);

        $attributeDetails = Mage::getSingleton("eav/config")->getAttribute("catalog_product", $attributeCode);
        $options = $attributeDetails->getSource()->getAllOptions(false);
        Foreach ($options as $option) {
            
            $attributeLables[] = strtolower($option["label"]);
        }

        if (!in_array($optionToBeSearch, $attributeLables)) {
            $installer = new Mage_Eav_Model_Entity_Setup('core_setup');
            $installer->startSetup();
            try {
                $searchAttributeOptions = array($optionToBeSearch);
                $productEntityTypeId = Mage::getModel('catalog/product')->getResource()->getTypeId();
                $createOptions = array();
                $createOptions['attribute_id'] = $installer->getAttributeId($productEntityTypeId, $attributeCode);

                for ($iCount = 0; $iCount < sizeof($searchAttributeOptions); $iCount++) {
                    $createOptions['value']['option' . $iCount][0] = $searchAttributeOptions[$iCount];
                }
                $installer->addAttributeOption($createOptions);
            } catch (Exception $e) {
                return false; //$e->getMessage();
            }

            $installer->endSetup();
        }
        return true;
    }

    public function checkAndCreateOrderStatus($fieldsetData) {
        if (!is_array($fieldsetData) && empty($fieldsetData)) {
            return false;
        }
        $statuses = $this->_getStatusCode($fieldsetData);
        $state = 'processing';
        $isDefault = '0';
        foreach ($statuses as $status => $label) {
            $statusModel = Mage::getModel('sales/order_status')->load($status);
            if (!$statusModel->getStatus()) {
                $statusModel->setData(array('status' => $status, 'label' => $label))->setStatus($status);
                try {
                    $statusModel->save();
                    $statusModel->assignState($state, $isDefault);
                    
                }catch (Mage_Core_Exception $e) {
                    return false; //$e->getMessage();
                }
            }
        }
        return true;
    }

    protected function _getStatusCode($fieldsetData)
    {
        $status = array($fieldsetData['processing_status'],$fieldsetData['error_status'],$fieldsetData['sentto_warehouse_status']);
        $labels = array();
        foreach($status as $label)
        {
            $labels[$label] = ucwords(str_replace('_',' ', $label));
        }
        return $labels;
    }
    
    
    public function updateSupplier()
    {
       
        $logicbrokerCollection = Mage::getModel('logicbroker/supplier')->getCollection()
                ->addFieldToFilter(array('is_update', 'verified'), array('1', '0'))
                ->addFieldToSelect(array('company_id', 'vendor_id'));
    
    foreach($logicbrokerCollection->getData() as $key=>$value)
    {
        $modelSupplier =  Mage::getModel('logicbroker/supplier')->load($value['vendor_id']);
        $modelSupplier->setIsUpdate('0');
        $modelSupplier->setVerified('1');
        try
        {
        $modelSupplier->save();
        }catch(Exception $e)
        {
            return false;
        }
    }
    
    
    }
    
    
    public function createOptionValueOnSave($value)
    {
        
        $integration = Mage::getStoreConfig('logicbroker_integration/integration/supplier_attribute');
         if($integration != null && $integration && $value)
        {
        
             $this->_checkAndCreateOptions($value,$integration);
        
        }
        return;
    }
    
    
}

?>
