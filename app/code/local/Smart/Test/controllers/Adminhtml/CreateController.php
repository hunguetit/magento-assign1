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

        $this->loadLayout();
        $this->renderLayout();
    }
    public function saveAction(){
        $dataPost = $this->getRequest()->getPost();
                Zend_Debug::dump($dataPost);
        die();

    }
}