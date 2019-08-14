<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */

    class Logicbroker_Fulfillment_Model_Resource_Supplier_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
    {

		public function _construct(){
			$this->_init("logicbroker/supplier");
		}

		

    }
	 