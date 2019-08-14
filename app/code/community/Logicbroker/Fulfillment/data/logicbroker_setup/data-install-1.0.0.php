<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Cms
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$coreConfigData = array(
    array(
        'scope'         => 'default',
        'scope_id'    => '0',
        'path'       => 'logicbroker_integration/integration/notificationstatus',
        'value'     => '0',
        
    ),
    array(
        'scope'         => 'default',
        'scope_id'    => '0',
        'path'       => 'logicbroker_integration/integration/dateofinstalltion',
        'value'     => date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())),
        
    )
    );

/**
 * Insert default blocks
 */
foreach ($coreConfigData as $data) {
    
    Mage::getModel('core/config_data')->setData($data)->save();
}

