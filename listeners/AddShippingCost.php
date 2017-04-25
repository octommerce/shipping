<?php namespace Octommerce\Shipping\Listeners;

use Prosehat\Wallet\Models\Voucher;
use Prosehat\Wallet\Validation\Voucher\Classes\Validator;
use Prosehat\Wallet\Validation\Campaign\Classes\Validator as CampaignValidator;

class AddShippingCost 
{

    public function handle($order, $data, $cart)
    {
        $order->addShippingCost($cart->shipping_cost);
    }

}
