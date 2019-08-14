<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */

class Logicbroker_Fulfillment_Model_Resource_Supplier extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("logicbroker/supplier", "vendor_id");
    }
}