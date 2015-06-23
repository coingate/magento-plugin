<?php

class Mage_Coingate_Model_ReceiveCurrencies
{
    public function toOptionArray()
    {
        return [
            ['value' => 'eur', 'label' => 'Euros (€)'],
            ['value' => 'usd', 'label' => 'US Dollars ($)'],
            ['value' => 'btc', 'label' => 'Bitcoin (฿)'],
        ];
    }
}