<?php

class Mage_Coingate_Model_ReceiveCurrencies
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'btc', 'label' => 'Bitcoin (฿)'),
            array('value' => 'eur', 'label' => 'Euros (€)'),
            array('value' => 'usd', 'label' => 'US Dollars ($)'),
            array('value' => 'DO_NOT_CONVERT', 'label' => 'Do not convert')
        );
    }
}