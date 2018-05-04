<?php
class Netstarter_DeliveryComment_Block_Adminhtml_Sales_Order_View_Info extends Mage_Adminhtml_Block_Sales_Order_View_Info
{
    protected function _afterToHtml($html)
    {
        $helper =  Mage::helper('deliverycomment');
        if ($helper->getConfigData('show_comment') || $helper->getConfigData('show_deliverydate')) {
            $block = $this->getChild('order_info.deliverycomment');
        }

        if (isset($block) && $block instanceof Mage_Core_Block_Abstract) {
            $html .= $block->toHtml();
        }

        return parent::_afterToHtml($html);
    }



}
			