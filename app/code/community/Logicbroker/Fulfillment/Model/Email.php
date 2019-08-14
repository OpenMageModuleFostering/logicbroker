<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */

class Logicbroker_Fulfillment_Model_Email extends Logicbroker_Fulfillment_Model_Abstract{
    
     public function prepareData($fieldsetData)
    {
        $excelxmlModel = Mage::getModel('logicbroker/excelxml');
        $response = $excelxmlModel->getExcelFile($fieldsetData);
       if(!is_array($response) && !$response)
       {
           Mage::throwException('Not able to genrate excel XML');
       }
       
       return $response['value'];
       
    }
    
    
    
    public function send($attachment = null,$fieldsetData) {
        try {
            
            $fieldsetData['isnewreg'] = ($attachment)?0:1;
            
            $postObject = new Varien_Object();
            $postObject->setData($fieldsetData);
            $mailTemplate = Mage::getModel('core/email_template');
            if($attachment)
            {
            $content = file_get_contents($attachment);
            $content = str_replace('></',">\n</",$content);
            // this is for to set the file format
            $at = new Zend_Mime_Part($content);

            $at->type = 'application/xml'; // if u have PDF then it would like -> 'application/pdf'
            $at->disposition = Zend_Mime::DISPOSITION_INLINE;
            $at->encoding = Zend_Mime::ENCODING_8BIT;
            $at->filename = 'logicbroker'.date('ymdHis').'.xml';
            $mailTemplate->getMail()->addAttachment($at);
            //$emailTemplate->_mail->addAttachment($at);
            }
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                        'logicbroker_email_email_template',
                        'general',
                       Mage::helper('logicbroker')->getConfigObject('apiconfig/email/toaddress'),
                       Mage::helper('logicbroker')->getConfigObject('apiconfig/email/toname'),
                        array('templatevar' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    return false;
                    //throw new Exception();
                }
                
            return true;//echo 'Mail send successfully with attachment file name -: ' . $attachment . "\n";
        } catch (Exception $e) {
            return false;//$e->getMassage();
        }
    }
    
}
?>
