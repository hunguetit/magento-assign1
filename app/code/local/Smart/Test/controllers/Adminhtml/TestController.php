<?php
class Smart_Test_Adminhtml_TestController extends Mage_Adminhtml_Controller_Action{
    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }

    public function productAction(){
        $id = $this->getRequest()->getParam('id',false);
        Mage::register('customer_id', $id);

        $this->loadLayout();
        $this->renderLayout();
    }

    public function orderAction(){
        $id = $this->getRequest()->getParam('id',false);
        Mage::register('customer_id', $id);

        $this->loadLayout();
        $this->renderLayout();
    }

    public function searchAction(){
        $data = $this->getRequest()->getPost();
//        Zend_Debug::dump($data);
//        die();

        Mage::register('dataSearch', $data);

        $this->loadLayout();
        $this->renderLayout();
    }
    public function totalAction(){
        $dataPost = $this->getRequest()->getPost();

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

}