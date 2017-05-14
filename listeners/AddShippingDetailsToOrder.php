<?php namespace Octommerce\Shipping\Listeners;

use Prosehat\Wallet\Models\Voucher;
use Prosehat\Wallet\Validation\Voucher\Classes\Validator;
use Prosehat\Wallet\Validation\Campaign\Classes\Validator as CampaignValidator;

class AddShippingDetailsToOrder 
{

    public function handle($order, $data, $cart)
    {
        if ($order->address) {
            $order->shipping_address = $order->address->street;
            $order->shipping_location_code = $order->address->location_code;
            $order->shipping_latitude = $order->address->latitude;
            $order->shipping_longitude = $order->address->longitude;

            $order->save();
        }
    }

}
