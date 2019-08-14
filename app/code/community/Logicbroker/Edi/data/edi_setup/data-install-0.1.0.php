<?php
$coreConfigData = array(
    array(
        'scope'         => 'default',
        'scope_id'    => '0',
        'path'       => 'logicbroker_edi_section/logicbroker_edi_group1/notificationstatus',
        'value'     => '0',
        
    ),
    array(
        'scope'         => 'default',
        'scope_id'    => '0',
        'path'       => 'logicbroker_edi_section/logicbroker_edi_group1/soap_webservice_url',
        'value'     => Mage::getBaseUrl().'api/v2_soap/',
        
    ),
);

/**
 * Insert default blocks
 */
foreach ($coreConfigData as $data) {
    
    Mage::getModel('core/config_data')->setData($data)->save();
}

