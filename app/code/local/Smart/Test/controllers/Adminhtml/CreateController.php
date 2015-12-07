<?php
class Smart_Test_Adminhtml_CreateController extends Mage_Adminhtml_Controller_Action{
    public function indexAction(){
        $id = $this->getRequest()->getParam('customer_id',false);
        Mage::register('customer_id', $id);

        $dataPost = $this->getRequest()->getPost();
//        Zend_Debug::dump($data);
//        die();
        $orderQty = array();
        $orderId = array();
        $productsQty = $dataPost['productAddQty'];
        $productsId = $dataPost['productAddId'];
        foreach ($productsQty as $productQty){
            if (is_numeric($productQty)){
                $orderQty[] = $productQty;
            }
        }
        foreach ($productsId as $productId){
            $orderId[] = $productId;
        }
        Mage::register('orderQty', $orderQty);
        Mage::register('orderId', $orderId);
        //Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Success.'));
        $this->loadLayout();
        $this->renderLayout();
    }
    public function saveAction(){
        $customerId = $this->getRequest()->getParam('customer_id',false);
//        Zend_Debug::dump($customerId);
//        die('OK');
        Mage::register('customerId', $customerId);

        $dataPost = $this->getRequest()->getPost();
        Mage::register('dataPost', $dataPost);
//        Zend_Debug::dump($dataPost);
//        die();
        $productOrderQty = array();
        $productOrderId = array();
        $productsQty = $dataPost['productQty'];
        $productsId = $dataPost['productId'];
        foreach ($productsQty as $productQty){
            if (is_numeric($productQty)){
                $productOrderQty[] = $productQty;
            }
        }
        foreach ($productsId as $productId){
            $productOrderId[] = $productId;
        }
        Mage::register('productOrderQty', $productOrderQty);
        Mage::register('productOrderId', $productOrderId);

        $this->loadLayout();
        $this->renderLayout();

        //return $this->_redirect('*/*/success', array('_current'=> true));

//
//        $billingAddress = Mage::getModel('sales/order_address')
//            ->setStoreId(1)
//            ->setFirstname($dataPost['firstname'])
//            ->setLastname($dataPost['lastname'])
//            ->setCity($dataPost['billing_city'])
//            ->setRegion($dataPost['billing_region'])
//            ->setPostcode($dataPost['billing_postcode'])
//            ->setTelephone($dataPost['billing_telephone'])
//            ->save();

 //       $orderPayment = Mage::getModel('sales/order_payment')
//            ->setStoreId(1)
//            ->setCustomerPaymentId(0)
//            ->setMethod($this->_paymentMethod)
//            ->setPoNumber(' – ')
//            ->save();

//        $dataPost = $this->getRequest()->getPost();
//        Zend_Debug::dump($dataPost);
//        die();
//        $order = Mage::getModel('sales/order');

//        $order = $this->_getOrderCreateModel()
//            ->setIsValidate(true)
//            ->importPostData($this->getRequest()->getPost('order'))
//            ->createOrder();
//        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The order has been created.'));

    }
}