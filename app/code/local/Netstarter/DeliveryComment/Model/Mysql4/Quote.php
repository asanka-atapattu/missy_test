<?php
class Netstarter_DeliveryComment_Model_Mysql4_Quote extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("deliverycomment/quote", "row_id");
    }
}