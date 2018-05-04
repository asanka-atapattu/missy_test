<?php

class Netstarter_DeliveryComment_Block_Shipping_Method_Additional
    extends Mage_Checkout_Block_Onepage_Shipping_Method_Additional
{

    protected function _afterToHtml($html)
    {
        $helper = Mage::helper('deliverycomment');

        if ($helper->getConfigData('show_comment')) {
            if ($block = $this->getLayout()->createBlock('deliverycomment/form')) {
                $html = $block->setTemplate('deliverycomment/comment.phtml')->toHtml().$html;
            }
        }

        if ($helper->getConfigData('show_deliverydate')) {
            if ($block = $this->getLayout()->createBlock('deliverycomment/form')) {
                $html = $block->setTemplate('deliverycomment/date.phtml')->toHtml().$html;
            }
        }
        return $html;
    }
}
