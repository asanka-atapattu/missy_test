<?php
$installer = $this;
$installer->startSetup();

$this->run(
"CREATE TABLE IF NOT EXISTS `{$this->getTable('netstarter_sales_order')}` (
  `row_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `customer_comment` text,
  PRIMARY KEY (`row_id`,`order_id`),
  KEY `FK_netstarter_sales_order` (`order_id`),
  CONSTRAINT `FK_netstarter_sales_order` FOREIGN KEY (`order_id`) REFERENCES `{$this->getTable('sales_flat_order')}` (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");

$this->run(
"CREATE TABLE IF NOT EXISTS `{$this->getTable('netstarter_sales_quote')}` (
  `row_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quote_id` int(10) unsigned NOT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `customer_comment` text,
  PRIMARY KEY (`row_id`,`quote_id`),
  KEY `FK_netstarter_sales_quote` (`quote_id`),
  CONSTRAINT `FK_netstarter_sales_quote` FOREIGN KEY (`quote_id`) REFERENCES `{$this->getTable('sales_flat_quote')}` (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");
$installer->endSetup();
	 