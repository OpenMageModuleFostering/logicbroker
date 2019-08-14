<?php

/**
 * Logicbroker Edi create SOAP user on module installation
 *
 * @category    Logicbroker
 * @package     Logicbroker_Edi
 */
 
class Logicbroker_Edi_Adminhtml_SoapuserController extends Mage_Adminhtml_Controller_Action
{
	/*
	 *	To save SOAP API Password during notification popup
	 */	
	public function saveSoapApiAction() {
		$paramsArray = $this->getRequest()->getParams();
		$result['success'] = 1;
		if(!empty($paramsArray['user_id']) && !empty($paramsArray['api_key'])){			
			Mage::getModel('api/user')->load($paramsArray['user_id'])->setApiKey($paramsArray['api_key'])->save();
			$result['message'] = 'Password is saved successfully';
		}else{
			$result['message'] = 'Cannot save Password';
		}	
		$result = Mage::helper('core')->jsonEncode($result);
		Mage::app()->getResponse()->setBody($result);
	}
}	