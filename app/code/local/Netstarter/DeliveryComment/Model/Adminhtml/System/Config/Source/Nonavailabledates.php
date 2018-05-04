<?php

class Netstarter_DeliveryComment_Model_Adminhtml_System_Config_Source_Nonavailabledates
{
    /**
     * Options getter
     * Prepare week days
     * @return array
     */
    public function toOptionArray()
    {
        /** @var $helper EcommerceTeam_Ddc_Helper_Data */
        $helper = Mage::helper('deliverycomment');
        return array(
            array('value' => 0, 'label'=>$helper->__('Sunday')),
            array('value' => 1, 'label'=>$helper->__('Monday')),
            array('value' => 2, 'label'=>$helper->__('Tuesday')),
            array('value' => 3, 'label'=>$helper->__('Wednesday')),
            array('value' => 4, 'label'=>$helper->__('Thursday')),
            array('value' => 5, 'label'=>$helper->__('Friday')),
            array('value' => 6, 'label'=>$helper->__('Saturday')),
        );
    }
}