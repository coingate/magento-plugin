<?php

class Mage_Coingate_PayController extends Mage_Core_Controller_Front_Action
{

    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }

    public function redirectAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setPayQuoteId($session->getQuoteId());
        $session->unsQuoteId();

        $coingate = Mage::getModel('coingate/CoingateFactory');

        $order = Mage::getModel('sales/order');
        $order->load($session->getLastOrderId());

        $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());

        if ($quote->getId()) {
            $quote->setIsActive(1)->setReservedOrderId(null)->save();
            $session->replaceQuote($quote);
        }

        $this->_redirectUrl($coingate->getRequest());
    }

    public function callbackAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('coingate/callback')->toHtml());
    }

    public function cancelAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getPayQuoteId(true));

        $order = Mage::getModel('sales/order');
        $order->load($session->getLastOrderId());

        if ($order->getId()) {
            $order->cancel()->save();
        }

        $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());

        if ($quote->getId()) {
            $quote->setIsActive(1)->setReservedOrderId(null)->save();
            $session->replaceQuote($quote);
        }

        $this->_redirect('checkout/cart');
    }

    public function successAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getPayQuoteId(true));
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $this->_redirect('checkout/onepage/success', array('_secure' => true));
    }

    public function failAction()
    {
        $order = Mage::getModel('sales/order');
        $order->load(Mage::getSingleton('checkout/session')->getLastOrderId());

        if ($order->getId()) {
            $order->cancel()->save();
        }

        $this->_redirect('checkout/onepage/failure');
    }
}
