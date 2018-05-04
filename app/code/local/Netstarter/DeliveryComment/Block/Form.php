<?php

class Netstarter_DeliveryComment_Block_Form extends Mage_Checkout_Block_Onepage_Abstract
{
    /** @var string */
    protected $_startDate;
    /** @var string */
    protected $_deliveryDate;
    /** @var string */
    protected $_customerComment;
    /** @var EcommerceTeam_Ddc_Helper_Data */
    protected $_helper;

    protected $_startDateTime;

    /**
     * Retrieve helper model
     *
     * @return EcommerceTeam_Ddc_Helper_Data
     */
    public function getLocalHelper()
    {
        if (is_null($this->_helper)) {
            $this->_helper = Mage::helper('deliverycomment');
        }

        return $this->_helper;
    }

    /**
     * Get first available date
     *
     * @return string
     */
    public function getStartDate()
    {
        if (is_null($this->_startDate)) {
            $helper = $this->getLocalHelper();
            $date   = Mage::app()->getLocale()->date();
            $day    = intval($date->toString('d', 'php'));

            $date->setDay($day);
            if ($days = trim($helper->getConfigData('disable_week_days'))) {
                $days = explode(',', $days);
                if (!empty($days)) {
                    while(in_array(($date->toString('w', 'php')+1), $days)){
                        $date->setDay(intval($date->toString('d', 'php'))+1);
                    }
                }
            }
            $this->_startDate = $date->toString('dd MMMM YYYY');
            $this->_startDateTime = $date->getTimestamp() - $date->getGmtOffset();
        }
        return $this->_startDate;
    }

    /**
     * Get saved or default date
     *
     * @return string
     */
    public function getDeliveryDate()
    {
        if (is_null($this->_deliveryDate)) {
            $helper = $this->getLocalHelper();
            /** @var $quote Mage_Sales_Model_Quote */
            $quote  = $this->getCheckout()->getQuote();
            $date   = Mage::app()->getLocale()->date();
            $date->setTimestamp($date->getTimestamp()-$date->getGmtOffset());
            $this->getStartDate();
            if (($savedDate = trim($quote->getDeliveryDate())) && (strtotime(trim($quote->getDeliveryDate())) >= $this->_startDateTime)) {
                $date->setTimestamp(strtotime($savedDate)-$date->getGmtOffset());
            } else {
                $day = intval($date->toString('d', 'php'))+intval($helper->getConfigData('min_day'));
                $date->setDay($day);
            }

            if ($days = trim($helper->getConfigData('disable_week_days'))) {
                $days = explode(',', $days);
                if (!empty($days)) {
                    while (in_array(($date->toString('w', 'php')+1), $days)) {
                        $date->setDay(intval($date->toString('d', 'php'))+1);
                    }
                }
            }
            $this->_deliveryDate = $date->toString('dd MMMM YYYY');
        }
        return $this->_deliveryDate;
    }

    /**
     * Get saved customer comment
     *
     * @return string
     */
    public function getCustomerComment()
    {
        if (is_null($this->_customerComment)) {
            $this->_customerComment = $this->getCheckout()->getQuote()->getCustomerComment();
        }
        return $this->_customerComment;
    }

    /**
     * Get config data
     *
     * @param string $node
     * @return string|bool|int
     */
    public function getConfigData($node)
    {
        return $this->getLocalHelper()->getConfigData($node);
    }

    /**
     * Get first day of week from current store locale
     *
     * @return int
     */
    public function getFirstDay()
    {
        return intval(Mage::getStoreConfig('general/locale/firstday'));
    }

    /**
     * Retrieve comment label
     *
     * @return string
     */
    public function getCommentLabel()
    {
        return $this->getConfigData('comment_label');
    }

    /**
     * Retrieve date label
     *
     * @return string
     */
    public function getDateLabel()
    {
        return $this->getConfigData('deliverydate_label');
    }

}
