<?php
class Netstarter_DeliveryComment_Model_Observer
{

			public function loadCsQuoteData(Varien_Event_Observer $observer)
			{
				$quote = $observer->getQuote();
				$data = Mage::getModel('deliverycomment/quote')->load($quote->getEntityId(), 'quote_id')->getData();
				if (isset($data['quote_id'])) {
					unset($data['entity_id'], $data['quote_id']);
					$quote->addData($data);
				}
			}
		
			public function loadCsOrderData(Varien_Event_Observer $observer)
			{
				$order = $observer->getOrder();
				$data = Mage::getModel('deliverycomment/order')->load($order->getEntityId(), 'order_id')->getData();

				if (isset($data['order_id'])) {
					unset($data['entity_id'], $data['order_id']);
					if (strtotime($data['delivery_date'])) {
						$formattedDate = Mage::getSingleton('core/locale')->date($data['delivery_date'], Zend_Date::ISO_8601, null, false)->toString(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_FULL));
						$data['delivery_date_formated'] = $formattedDate;
					} else {
						unset($data['delivery_date']);
					}
					$order->addData($data);
				}
			}
		
			public function saveCsOrderData(Varien_Event_Observer $observer)
			{
				$order = $observer->getOrder();
				$quote = Mage::getSingleton('checkout/session')->getQuote();
				try {
					/** @var $deliverycommentOrderModel Netstarter_DeliveryComment_Model_Order */
					$deliverycommentOrderModel = Mage::getModel('deliverycomment/order');
					$data = $deliverycommentOrderModel->load($order->getEntityId(), 'order_id')->getData();
					if (!isset($data['order_id'])) {
						$deliverycommentOrderModel->setOrderId($order->getEntityId());
						$deliverycommentOrderModel->setDeliveryDate($quote->getDeliveryDate());
						$deliverycommentOrderModel->setCustomerComment($quote->getCustomerComment());
						$deliverycommentOrderModel->save();
					}
					$order->setDeliveryDate($quote->getDeliveryDate());
					$formattedDate = Mage::getSingleton('core/locale')->date($quote->getDeliveryDate(), Zend_Date::ISO_8601, null, false)->toString(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_FULL));
					$order->setDeliveryDateFormated($formattedDate);
					$order->setCustomerComment($quote->getCustomerComment());
				} catch(Exception $e) {
					Mage::log($e, null, 'deliverycomment_error.log');
				}
			}
		
			public function saveCsQuoteData(Varien_Event_Observer $observer)
			{
				$helper  = Mage::helper('deliverycomment');

				$quote   = $observer->getQuote();
				$request = Mage::app()->getRequest();

				$deliverycommentQuoteModel   = Mage::getModel('deliverycomment/quote')->load($quote->getEntityId(), 'quote_id');

				if (!$deliverycommentQuoteModel->getEntityId()) {
					$deliverycommentQuoteModel->setQuoteId($quote->getEntityId());
				}

				$data = array();
				$date = Mage::app()->getLocale()->date();

				if ($request->getParam('delivery_date')) {
					$deliveryDate = Date('Y-m-d', strtotime($request->getParam('delivery_date')));
				} /*elseif (!$deliverycommentQuoteModel->getEntityId()) {
					$date->setDay($date->toString('d') + intval($helper->getConfigData('min_day')));
					$deliveryDate = Date('Y-m-d', $date->getTimeStamp()+$date->getGmtOffset());
				}*/

				if (isset($deliveryDate)) {
					$data['delivery_date'] = $deliveryDate;
				}

				if ($customer_comment = $request->getParam('customer_comment')) {
					$data['customer_comment'] = $customer_comment;
				}

				if (!empty($data)) {
					$quote->addData($data);
					try {
						$deliverycommentQuoteModel->addData($data)->save();
					} catch (Exception $e) {
						Mage::log($e, null, 'deliverycomment_error.log');
					}
				}
			}

			public function beforeBlockToHtml(Varien_Event_Observer $observer)
			{
				$grid = $observer->getBlock();
				/*get Block class*/
				//echo get_class($grid);
				/*get Block name */
				//echo $grid->getName();
			   /*get Block type*/
				//echo $type = $grid->getType();

				/**
				 * Mage_Adminhtml_Block_Sales_Order_Grid
				 */
				if ($grid instanceof Mage_Adminhtml_Block_Sales_Order_Grid) {
					$observer->getBlock()->addColumnAfter('delivery_date', array(
						'header' => Mage::helper('sales')->__('Delivery Date'),
						'type' => 'date',
						'index' => 'delivery_date',
						'width' => '160px',
						'filter' => false,
					), 'shipping_name');

					$observer->getBlock()->addColumnAfter('customer_comment', array(
						'header' => Mage::helper('sales')->__('Customer Comment'),
						'type' => 'text',
						'index' => 'customer_comment',
						'width' => '160px',
						'filter' => false,
					), 'delivery_date');
				}

				/**
				 * Mage_Adminhtml_Block_Customer_Edit_Tab_Orders
				 */
				if ($grid instanceof Mage_Adminhtml_Block_Customer_Edit_Tab_Orders) {
					$observer->getBlock()->addColumnAfter('delivery_date', array(
						'header' => Mage::helper('sales')->__('Delivery Date'),
						'type' => 'date',
						'index' => 'delivery_date',
						'width' => '160px',
						'filter' => false,
					), 'shipping_name');

					$observer->getBlock()->addColumnAfter('customer_comment', array(
						'header' => Mage::helper('sales')->__('Customer Comment'),
						'type' => 'text',
						'index' => 'customer_comment',
						'width' => '160px',
						'filter' => false,
					), 'delivery_date');
				}


			}

			public function beforeCollectionLoad(Varien_Event_Observer $observer)
			{
				$collection = $observer->getCollection();
				if (!isset($collection)) {
					return;
				}

				/**
				 * Mage_Sales_Model_Resource_Order_Collection
				 */
				if ($collection instanceof Mage_Sales_Model_Resource_Order_Collection ) {
					$collection->getSelect()->joinLeft
					(Mage::getConfig()->getTablePrefix().'netstarter_sales_order',
						'main_table.entity_id ='.Mage::getConfig()->getTablePrefix().'netstarter_sales_order.order_id', array('customer_comment','delivery_date'));
				}

			}
		
}
