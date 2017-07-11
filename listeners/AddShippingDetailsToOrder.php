<?php namespace Octommerce\Shipping\Listeners;

class AddShippingDetailsToOrder 
{

    public function handle($order, $data, $cart)
    {
        $order->addShippingDetails($data);
    }

}
