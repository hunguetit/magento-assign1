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

        $customers = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('*')
            ->joinAttribute('billing_street', 'customer_address/street', 'default_billing', null, 'left')
            ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
            ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
            ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
            ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
            ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
            ->addAttributeToFilter('entity_id', $customer_id);

        return ($customers);
    }
    public function getRegionId($country_id){
        $collection = Mage::getModel('directory/region_api')->items($country_id);
        Mage::log($collection);
        $i=1;

        $resArr = array();
        foreach ($collection as $values)
        {
            $resArr[$i]['value']=$values['name'];
            $resArr[$i]['label']=$values['name'];
            $i=$i+1;
        }
        return $resArr;
    }
    public function getCountryId(){
        $countryList = Mage::getModel('directory/country')->getResourceCollection()
            ->loadByStore()
            ->toOptionArray(true);

        return $countryList;
    }

    public function getProductSearch(){
        $dataSearch = Mage::registry ('dataSearch');
        if (is_numeric($dataSearch['search'])){
            $search_id = $dataSearch['search'];
        } else {
            $search_name = $dataSearch['search'];
        }
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
        }

        return $collection;
    }

    public function getProduct(){
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*') // select all attributes
            ->setPageSize(5000) // limit number of results returned
            ->setCurPage(1);

        return $collection;
    }
    public function getOrder(){
        $orderModel = Mage::getResourceModel('sales/order_grid_collection')
                            ->addAttributeToFilter('shipping_name', array('like' => '%ber%'));

        var_dump($orderModel->getData());die();
    }

    public function getTotal(){
        $orderQty = Mage::registry('orderQty');

        $orderId = Mage::registry('orderId');
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->AddAttributeToFilter('entity_id', array ('in' => array ($orderId)));

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
        $dataPost = Mage::registry('dataPostFixed');

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
            'street' => array($dataPost['billing_street'],''),
            'city' => $dataPost['billing_city'],
            'country_id' => $dataPost['billing_country_id'],
            'region' => $dataPost['billing_region'],
            'region_id' => $dataPost['billing_region'],
            'postcode' => $dataPost['billing_postcode'],
            'telephone' => $dataPost['billing_telephone'],
            'state/province' =>'NULL',
            'state' =>'NULL',
            'province' =>'NULL',
            'fax' => 'NONE',
            'vat_id' => '',
            'save_in_address_book' => 1
        ));
        $shippingAddress = $quote->getShippingAddress()->addData(array(
            'customer_address_id' => '',
            'prefix' => '',
            'firstname' => $dataPost['shipping_firstname'],
            'middlename' => '',
            'lastname' =>$dataPost['shipping_lastname'],
            'suffix' => '',
            'company' =>'',
            'street' => array($dataPost['shipping_street'],''),
            'city' => $dataPost['shipping_city'],
            'country_id' => $dataPost['shipping_country_id'],
            'region' => $dataPost['shipping_region'],
            'region_id' => $dataPost['shipping_region'],
            'postcode' => $dataPost['shipping_postcode'],
            'telephone' => $dataPost['shipping_telephone'],
            'fax' => 'NONE',
            'vat_id' => '',
            'save_in_address_book' => 1,
            'state/province' =>'NULL',
            'state' =>'NULL',
            'province' =>'NULL',
        ));

        $paymentMethod = $dataPost['payment'];
        $shipping_method = $dataPost['shipment'].'_'.$dataPost['shipment'];
        // Collect Rates and Set Shipping & Payment Method
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($shipping_method)
            ->setPaymentMethod($paymentMethod);
        // Set Sales Order Payment
        $quote->getPayment()->importData(array('method' => $paymentMethod));
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
        catch (Mage_Core_Exception $e){
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
            if ($shipment){
                $shipment->register();
                $shipment->addComment($comment, $email && $includeComment);
                $shipment->getOrder()->setIsInProcess(true);
                try {
                    $transactionSave = Mage::getModel('core/resource_transaction')
                        ->addObject($shipment)
                        ->addObject($shipment->getOrder())
                        ->save();
                    $shipment->sendEmail($email, ($includeComment ? $comment : ''));
                } catch (Mage_Core_Exception $e){
                    var_dump($e);
                }
            }
        }
        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The order created success.'));
    }

    public function getPaymentMethod(){
        $payments = Mage::getSingleton('payment/config')->getActiveMethods();
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

    public function getShipmentMethod()
    {
        $dataPost = Mage::registry('dataPost');
        $orderQty = Mage::registry('orderQty');
        $orderId = Mage::registry('orderId');
        $productsOrder = array();
        for ($i=0; $i<count($orderId); $i++){
            $productsOrder[] = [
                'product_id' => $orderId[$i],
                'qty'        => $orderQty[$i],
            ];
        }
        $itemQty = 0;
        for ($i=0; $i<count($orderQty); $i++){
            $itemQty += $orderQty[$i];
        }
        $total = $this->getTotal();
        $quote = Mage::getModel('sales/quote')->setStoreId(1);
        foreach($productsOrder as $productOrder){
            $product=Mage::getModel('catalog/product')->load($productOrder['product_id']);
            $quote->addProduct($product,new Varien_Object(array('qty' => $productOrder['qty'])));
        }

        $request = Mage::getModel('shipping/rate_request');
        $request->setAllItems($quote->getAllItems());
        $request->setDestCountryId($dataPost['shipping_country_id']);
        $request->setDestRegionId($dataPost['shipping_region']);
        $request->setDestPostcode($dataPost['shipping_postcode']);
        $request->setPackageValue($total);
        $request->setPackageValueWithDiscount($total);
        $request->setFreeMethodWeight(0);
        $request->setPackagePhysicalValue($total);
        $request->setPackageQty($itemQty);
        $request->setStoreId(1);
        $request->setWebsiteId(1);
        $request->setLimitCarrier(null);
        $result = Mage::getModel('shipping/shipping')->collectRates($request)->getResult();

        if ($result){
            $shippingRates = $result->getAllRates();
            $allShippingRates = array();
            foreach ($shippingRates as $key => $shippingRate){
                $allShippingRates[] = $shippingRate->getData();
            }
            return $allShippingRates;
        }else{
            return array();
        }
    }
}