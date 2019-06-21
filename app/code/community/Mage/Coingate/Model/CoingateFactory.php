<?php

require_once(Mage::getBaseDir() . '/app/code/community/Mage/Coingate/lib/coingate-php/init.php');

define('COINGATE_MAGENTO_VERSION', '1.2.7');

class Mage_Coingate_Model_CoingateFactory extends Mage_Payment_Model_Method_Abstract
{
    protected $_isGateway = true;
    protected $_canAuthorize = true;

    protected $_code = 'coingate';

    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('coingate/pay/redirect');
    }

    public function getRequest()
    {
        $order = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());

        $token = substr(md5(rand()), 0, 32);

        $payment = $order->getPayment();
        $payment->setAdditionalInformation('coingate_order_token', $token);
        $payment->save();

        $title = Mage::app()->getWebsite()->getName();

        $description = array();

        foreach ($order->getAllItems() as $item) {
            $description[] = number_format($item->getQtyOrdered(), 0) . ' × ' . $item->getName();
        }

        $cgConfig = Mage::getStoreConfig('payment/coingate');

        $this->initCoinGate($cgConfig);

        $order = \CoinGate\Merchant\Order::create(array(
            'order_id' => $order->increment_id,
            'price_amount' => number_format($order->grand_total, 8, '.', ''),
            'price_currency' => $order->order_currency_code,
            'receive_currency' => $cgConfig['receive_currency'],
            'success_url' => Mage::getUrl('coingate/pay/success'),
            'cancel_url' => Mage::getUrl('coingate/pay/cancel'),
            'callback_url' => Mage::getUrl('coingate/pay/callback'),
            'title' => $title . ' Order #' . $order->increment_id,
            'description' => join($description, ', '),
            'token' => $token
        ));

        if (!empty($order)) {
            return $order->payment_url;
        }

        return false;
    }

    public function validateCallback()
    {
        try {
            $incrementId = Mage::app()->getRequest()->getParam('order_id');

            if (empty($incrementId)) {
                throw new Exception('Parameter order_id is empty.');
            }

            $order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);

            if (empty($order) || !$order->getIncrementId()) {
                throw new Exception('Magento Order #' . $incrementId . ' does not exist.');
            }

            $payment = $order->getPayment();
            $token = $payment->getAdditionalInformation('coingate_order_token');

            if (empty($token) || strcmp($token, Mage::app()->getRequest()->getParam('token')) !== 0) {
                throw new Exception('CoinGate security token does not match.');
            }

            $cgConfig = Mage::getStoreConfig('payment/coingate');

            $this->initCoinGate($cgConfig);

            $cgOrderId = Mage::app()->getRequest()->getParam('id');
            $cgOrder = \CoinGate\Merchant\Order::find($cgOrderId);

            if (empty($cgOrder)) {
                throw new Exception('CoinGate Order #' . $cgOrderId . ' does not exist.');
            }

            switch ($cgOrder->status) {
                case 'paid':
                    $mageStatus = $cgConfig['invoice_paid'];
                    break;
                case 'canceled':
                    $mageStatus = $cgConfig['invoice_canceled'];
                    break;
                case 'expired':
                    $mageStatus = $cgConfig['invoice_expired'];
                    break;
                case 'invalid':
                    $mageStatus = $cgConfig['invoice_invalid'];
                    break;
                case 'refunded':
                    $mageStatus = $cgConfig['invoice_refunded'];
                    break;
                default:
                    $mageStatus = null;
            }

            if (!is_null($mageStatus)) {

                if ($cgOrder->status == 'expired' || $cgOrder->status == 'canceled' || $cgOrder->status == 'invalid') {
                    if ($payment->getData('method') == 'coingate') {
                        $order->cancel()->save();   //Restocks
                    }
                }
                $order->setState($mageStatus, true)->save();

                if ($cgOrder->status == 'paid') {
                    $order->sendNewOrderEmail()->addStatusHistoryComment('You have confirmed the order to the customer
                     via email.')
                        ->setIsCustomerNotified(true)
                        ->save();

                    $order->setTotalPaid($cgOrder->price)->save();
                }

                if ($cgOrder->status == 'refunded') {
                    $order->setTotalRefunded($cgOrder->price)->save();
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    private function initCoinGate($cgConfig)
    {
        \CoinGate\CoinGate::config((array(
            'auth_token' => (empty($cgConfig['api_auth_token']) ? $cgConfig['api_secret'] : $cgConfig['api_auth_token']),
            'environment' => (int)($cgConfig['test']) == 1 ? 'sandbox' : 'live',
            'user_agent' => 'CoinGate - Magento Extension v' . COINGATE_MAGENTO_VERSION
        )));
    }
}
