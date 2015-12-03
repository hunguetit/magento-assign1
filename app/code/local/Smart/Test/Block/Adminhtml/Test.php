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

}