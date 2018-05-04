<?php

class Netstarter_DeliveryComment_Block_Adminhtml_Sales_Order_View_Info_Block
    extends Mage_Core_Block_Template
{
    protected $_order;

    public function getOrder()
    {
        if (is_null($this->_order)) {
            if (Mage::registry('current_order')) {
                $order = Mage::registry('current_order');
            } elseif (Mage::registry('order')) {
                $order = Mage::registry('order');
            } else {
                $order = new Varien_Object();
            }
            $this->_order = $order;
        }
        return $this->_order;
    }
}
