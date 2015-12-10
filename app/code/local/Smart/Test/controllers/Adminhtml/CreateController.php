<?php
class Smart_Test_Adminhtml_CreateController extends Mage_Adminhtml_Controller_Action{
    public function indexAction(){
        $id = $this->getRequest()->getParam('customer_id',false);
        Mage::register('customer_id', $id);

        $dataPost = $this->getRequest()->getPost();
        Mage::register('dataPost', $dataPost);
//        Zend_Debug::dump($dataPost);
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

        Mage::register('customerId', $customerId);

        $dataPost = $this->getRequest()->getPost();
        Mage::register('dataPostFixed', $dataPost);

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
    }
}