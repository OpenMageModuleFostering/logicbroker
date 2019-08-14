<?php

/**
 * Logicbroker
 *
 * 
 *
 */
 
class Logicbroker_Fulfillment_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getConfigObject($nodeName = null)
    {
        return trim(Mage::getConfig()->getNode($nodeName)->__toString());
    }
}
	 