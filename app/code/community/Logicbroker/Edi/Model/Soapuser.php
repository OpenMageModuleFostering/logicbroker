<?php
/**
 * Logicbroker
 *
 * @category    Logicbroker
 * @package     Logicbroker_Edi
 */

class Logicbroker_Edi_Model_Soapuser {

	/*
	 * Create API role 
	 *
	 * @return array
	 */

    public function createApiRoleAndUser($fieldsetData) {

        $role = Mage::getModel('api/roles');
        $roleName = 'logicbroker_edi';
        $parentId = '';
        $roleType = 'G';
        $ruleNodes = array();

        if (!is_array($fieldsetData)) {
            return false;
        }
        $role->load($roleName, 'role_name');
        $ruleNodes = array('all');
        try {
            $role = $role->setName($roleName)
                    ->setPid($parentId)
                    ->setRoleType($roleType)
                    ->save();

            Mage::getModel("api/rules")->setRoleId($role->getId())
                    ->setResources($ruleNodes)
                    ->saveRel();
        } catch (Exception $e) {
            return false;
        }
        $password = '';
        $userExist = $this->_userExists($fieldsetData['api_user_name'],$fieldsetData['email']);
        $userId = '';
        if (is_array($userExist)) {
			  $modelData =  Mage::getModel('api/user')->load($userExist[1]);
			  $userId = $modelData->getUserId();
        }else {
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
            return false;
        }
        return array('password'=>$modelData->getApiKey(),'user_id'=>$modelData->getUserId());
    }
    
	/*
	 * Check if the provided user details already exist 
	 *
	 * @return bool
	 */

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
  
}
