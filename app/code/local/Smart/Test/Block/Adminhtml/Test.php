<?php
class Smart_Test_Block_Adminhtml_Test extends Mage_Core_Block_Template{
    public function __construct()
    {
        parent::__construct();
        $customers = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('*')
            ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
            ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
            ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
            ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
            ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left');
        $this->setCollection($customers);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array('all'=>'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getCustomer(){
        $customers = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('*')
            ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
            ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
            ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
            ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
            ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left');
        return $customers;
    }

    public function getOrderCustomer(){
        $customer_id = Mage::registry ('customer_id');
//        Zend_Debug::dump($customer_id);
//        die();

        $customers = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('*')
            ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
            ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
            ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
            ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
            ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
            ->addAttributeToFilter('entity_id', $customer_id);
//        Zend_Debug::dump($customers->getData());
//        die();
        return ($customers);
    }

    public function getProductSearch(){
        $dataSearch = Mage::registry ('dataSearch');
        if (is_numeric($dataSearch['search'])){
            $search_id = $dataSearch['search'];
        } else {
            $search_name = $dataSearch['search'];
        }
//        Zend_Debug::dump($search_id);
//        Zend_Debug::dump($search_name);
//        die();
//        $productModel = Mage::getModel('catalog/product');
//        $products = $productModel->load($search_id);
//        Zend_Debug::dump($search_id);
//        Zend_Debug::dump($search_name);
//        die('Hung');
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter(array(
                array(
                    'attribute' => 'entity_id',
                    'in'        => array($search_id),
                ),
                array(
                    'attribute' => 'name',
                    'like'      => $search_name,
                ),
            ));
        foreach ($collection as $product){
            Zend_Debug::dump($product->name);
            die('Hung');
        }

//        Zend_Debug::dump($productModel);
//        die();
//        //$products = $productModel->load(905);
//        Zend_Debug::dump($productModel->getData());
//        die();
        return $collection;
    }

    public function getProduct(){
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*') // select all attributes
            ->setPageSize(5000) // limit number of results returned
            ->setCurPage(1);

//        $products = Mage::getModel("catalog/product")
//            ->getCollection();

        return $collection;
    }
    public function getOrder(){
        $orderModel = Mage::getResourceModel('sales/order_grid_collection')
                            ->addAttributeToFilter('shipping_name', array('like' => '%ber%'));
       // var_dump($orderModel->getData());die();
        //$orders = $orderModel->load();
        var_dump($orderModel->getData());die();
    }

    public function getTotal(){
        $orderQty = Mage::registry('orderQty');

        $orderId = Mage::registry('orderId');
//        Zend_Debug::dump($orderId);
//        die();
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->AddAttributeToFilter('entity_id', array ('in' => array ($orderId)));
//        foreach ($collection as $product){
//            Zend_Debug::dump($product->price);
//            die();
//        }
        $orderPrice = array();
        foreach ($collection as $product){
            $orderPrice[] = $product->price;
        }
        $total = 0;
        for ($i=0; $i<count($orderId); $i++){
            $total += $orderQty[$i] * $orderPrice[$i];
        }
        $total = number_format($total, 0 ,'.', ',');
        return $total;
    }

    public function getProductOrder(){
//        $orderQty = Mage::registry('orderQty');
        $orderId = Mage::registry('orderId');

        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->AddAttributeToFilter('entity_id', array ('in' => array ($orderId)));

        return $collection;
    }

    public function getSaveUrl(){
        return $this->getUrl('*/*/save', array('_current'=> true));
    }
    public function getQtyOrder(){
        $Qty = Mage::registry('orderQty');
        $Id = Mage::registry('orderId');
        $qtyOrder = array();
        for ($i=0; $i<count($Id); $i++){
            $qtyOrder[] = [
                'product_id' => $Id[$i],
                'qty'        => $Qty[$i],
            ];
        }
        return $qtyOrder;
    }

    public function saveOrder(){
        $shipprice = 0;
        $productQty = Mage::registry('productOrderQty');
        $productsId = Mage::registry('productOrderId');
        $productsOrder = array();
        for ($i=0; $i<count($productsId); $i++){
            $productsOrder[] = [
                'product_id' => $productsId[$i],
                'qty'        => $productQty[$i],
            ];
        }

        $customerId = Mage::registry('customerId');
        $dataPost = Mage::registry('dataPost');

        $quote = Mage::getModel('sales/quote')->setStoreId(1);
        $quote->setCurrency($order->AdjustmentAmount->currencyID);
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(1)
            ->load($customerId);

        $quote->assignCustomer($customer);

        $quote->setSendConfirmation(1);
        foreach($productsOrder as $productOrder){
            $product=Mage::getModel('catalog/product')->load($productOrder['product_id']);
            $quote->addProduct($product,new Varien_Object(array('qty' => $productOrder['qty'])));
        }
        $billingAddress = $quote->getBillingAddress()->addData(array(
            'customer_address_id' => '',
            'prefix' => '',
            'firstname' => $dataPost['firstname'],
            'middlename' => '',
            'lastname' =>$dataPost['lastname'],
            'suffix' => '',
            'company' =>'',
            'street' => array(
                '0' => 'Noida',
                '1' => 'Sector 64'
            ),
            'city' => $dataPost['billing_city'],
            'country_id' => 'IN',
            'region' => $dataPost['billing_region'],
            'postcode' => $dataPost['billing_postcode'],
            'telephone' => $dataPost['billing_telephone'],
            'fax' => 'gghlhu',
            'vat_id' => '',
            'save_in_address_book' => 1
        ));

        $shippingAddress = $quote->getShippingAddress()->addData(array(
            'customer_address_id' => '',
            'prefix' => '',
            'firstname' => $dataPost['firstname'],
            'middlename' => '',
            'lastname' =>$dataPost['lastname'],
            'suffix' => '',
            'company' =>'',
            'street' => array(
                '0' => 'Noida',
                '1' => 'Sector 64'
            ),
            'city' => $dataPost['billing_city'],
            'country_id' => 'IN',
            'region' => $dataPost['billing_region'],
            'postcode' => $dataPost['billing_postcode'],
            'telephone' => $dataPost['billing_telephone'],
            'fax' => 'gghlhu',
            'vat_id' => '',
            'save_in_address_book' => 1
        ));

        if($shipprice==0){
            $shipmethod='freeshipping_freeshipping';
        }
        // Collect Rates and Set Shipping & Payment Method
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('freeshipping_freeshipping')
            ->setPaymentMethod('cashondelivery');
        // Set Sales Order Payment
        $quote->getPayment()->importData(array('method' => 'cashondelivery'));
        // Collect Totals & Save Quote
        $quote->collectTotals()->save();

        // Create Order From Quote
        $service = Mage::getModel('sales/service_quote', $quote);
        $service->submitAll();
        $increment_id = $service->getOrder()->getRealOrderId();

        $quote = $customer = $service = null;
        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Create Order Success.'));

        $order = Mage::getModel("sales/order")->loadByIncrementId($increment_id);
        try {
            if(!$order->canInvoice()) {
                Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));
            }

            $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();

            if (!$invoice->getTotalQty()) {
                Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
            }

            $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
            $invoice->register();
            $transactionSave = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder());

            $transactionSave->save();
        }
        catch (Mage_Core_Exception $e) {

        }
        $qty=array();
        foreach($order->getAllItems() as $eachOrderItem){
            $Itemqty=0;
            $Itemqty = $eachOrderItem->getQtyOrdered()
                - $eachOrderItem->getQtyShipped()
                - $eachOrderItem->getQtyRefunded()
                - $eachOrderItem->getQtyCanceled();
            $qty[$eachOrderItem->getId()] = $Itemqty;

        }
        $email=true;
        $includeComment=true;
        $comment="test Shipment";

        if ($order->canShip()) {
            $shipment = $order->prepareShipment($qty);
            if ($shipment) {
                $shipment->register();
                $shipment->addComment($comment, $email && $includeComment);
                $shipment->getOrder()->setIsInProcess(true);
                try {
                    $transactionSave = Mage::getModel('core/resource_transaction')
                        ->addObject($shipment)
                        ->addObject($shipment->getOrder())
                        ->save();
                    $shipment->sendEmail($email, ($includeComment ? $comment : ''));
                } catch (Mage_Core_Exception $e) {
                    var_dump($e);
                }
            }
        }
        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The order created success.'));
    }
    public function getPaymentMethod(){
//        $paymentMethod = array();
//        $allActivePaymentMethods = Mage::getModel('payment/config')->getActiveMethods();
//        foreach ($allActivePaymentMethods as $allActivePaymentMethod) {
////            Zend_Debug::dump($allActivePaymentMethod);
////            die('Hung');
//            $paymentMethod[] = [
//                'code'  => $allActivePaymentMethod->getCode(),
//                'title' => $allActivePaymentMethod->getCode(),
//            ];
//        }
//
//        Zend_Debug::dump($paymentMethod);
//        die('Hung');

        $payments = Mage::getSingleton('payment/config')->getActiveMethods();
//        $methods = array(array(
//            'value'=>'',
//            'label'=>Mage::helper('adminhtml')->__('--Please Select--')
//        ));
        $paymentMethods = array();

        foreach ($payments as $paymentCode=>$paymentModel) {
            $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
            $paymentMethods[$paymentCode] = array(
                'label'   => $paymentTitle,
                'code' => $paymentCode,
            );
        }
        return $paymentMethods;
    }

    public function getShipmentMethod(){
        $methods = Mage::getSingleton('shipping/config')->getActiveCarriers();
        $shipMethods = array();
        foreach ($methods as $shippingCode=>$shippingModel)
        {
            $shippingTitle = Mage::getStoreConfig('carriers/'.$shippingCode.'/title');
            $shipMethods[$shippingCode] = array(
                'label'   => $shippingTitle,
                'code' => $shippingCode,
            );
        }
        Zend_Debug::dump($shipMethods);
//        die('Hung');
        return $shipMethods;
    }
}