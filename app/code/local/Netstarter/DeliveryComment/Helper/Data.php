<?php
class Netstarter_DeliveryComment_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getConfigData($element)
    {
        return Mage::getStoreConfig(sprintf('checkout/deliverycomment/%s', $element));
    }


}
	 