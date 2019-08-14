<?php

/**
 * Logicbroker
 *
 * @category    Community
 * @package     Logicbroker_Fulfillment
 */

$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
DROP TABLE IF EXISTS {$this->getTable('logicbroker_vendor')};
CREATE TABLE {$this->getTable('logicbroker_vendor')}(vendor_id int not null auto_increment,
    company_id varchar(50) not null,
    companyname varchar(100) not null,
    address1 varchar(255) not null,
    address2 varchar(255) not null,
    city varchar(100) not null,
    state varchar(100) not null,
    zip int not null ,
    country varchar(50) not null,
    edi_qualifier text NOT NULL,
    edi_identifier varchar(100) not null,
    magento_vendor_code varchar(100) not null,
    file_directory_inbound varchar(100) not null,
    file_directory_outbound varchar(100) not null,
    ftp_address varchar(100) not null,
    ftp_username varchar(100) not null,
    ftp_password varchar(100) not null,
    firstname varchar(100) not null,
    lastname varchar(100) not null,
    email varchar(100) not null,
    phone varchar(50) not null,
    primary key(vendor_id),
    is_update int(1) NOT NULL DEFAULT '0',
    verified int(1) NOT NULL DEFAULT '0',
    ftp_protocol ENUM('ftp','ftpes' ) NOT NULL,
    magento_vendor_attribute_id varchar(255) not null,
    status varchar(11) NOT NULL, 
    KEY `company_id` (`company_id`),
    KEY `status` (`status`),
    KEY `magento_vendor_code` (`magento_vendor_code`),
    KEY `email` (`email`))
    ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
SQLTEXT;

$installer->run($sql);
//Mage::getModel('compiler/process')->registerIncludePath(false);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 