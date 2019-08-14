<?php

/**
 * model to get the popup notification for soap user on module insatllation
 *
 * @category    Logicbroker
 * @package     Logicbroker_Edi
 */
class Logicbroker_Edi_Block_Adminhtml_Notification extends Mage_Adminhtml_Block_Notification_Window
{
	protected function _construct(){
        parent::_construct();
        $this->setHeaderText($this->escapeHtml($this->__('Logicbroker Edi Message')));
        $this->setCloseText($this->escapeHtml($this->__('close')));
        $this->setReadDetailsText($this->escapeHtml($this->__('Read details')));
        $this->setNoticeText($this->escapeHtml($this->__('NOTICE')));
        $this->setMinorText($this->escapeHtml($this->__('MINOR')));
        $this->setMajorText($this->escapeHtml($this->__('MAJOR')));
        $this->setCriticalText($this->escapeHtml($this->__('CRITICAL')));

	}
	
	/*
     * Create Api Role & Save the Soap details in system config too.
     * 
     * @return array $result
     */
	public function getNoticeMessageText(){	
		$userDetails = array('api_user_name'=>'logicbroker_edi',  'firstname' =>'Logicbroker',  'lastname' => 'Edi', 'email'=>'customer@logicbroker.com');
		$apiResult = Mage::getModel('edi/soapuser')->createApiRoleAndUser($userDetails);
		$result = array();
		$result['username'] = 'logicbroker_edi';
		$result['api_password'] = $apiResult['password'];
		$result['user_id']      = $apiResult['user_id'];
		$coreConfigData = array(					
				array(
						'scope'         => 'default',
						'scope_id'    => '0',
						'path'       => 'logicbroker_edi_section/logicbroker_edi_group1/soapuser',
						'value'     => $result['username'],
							
				),
			);		
		foreach ($coreConfigData as $data) {
			$this->setConfigValue($data);	
		}
		return $result;					
	}
	
	/*
     * Soap details in system config.
     * 
     */
	protected function setConfigValue($data){	
		Mage::getModel('core/config_data')->load($data['path'],'path')->setData($data)->save();
	}
	
	/*
     * Check if module notification window has been shown earlier, else set it.
     * 
     * @return booolean
     */
	public function canShow(){
		if (Mage::getStoreConfig('logicbroker_edi_section/logicbroker_edi_group1/notificationstatus') == 0) {
			$this->setConfigValue(array(
				'scope'         => 'default',
				'scope_id'    => '0',
				'path'       => 'logicbroker_edi_section/logicbroker_edi_group1/notificationstatus',
				'value'     => '1',
		
				));
			return true;
        }else{
        	return false;
        }
	}
}
