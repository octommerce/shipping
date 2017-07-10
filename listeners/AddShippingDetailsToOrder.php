<?php namespace Octommerce\Shipping\Listeners;

class AddShippingDetailsToOrder 
{

    public function handle($order, $data, $cart)
    {
        if ($order->shipping_address) {
            $order->shipping_address = $order->shipping_address->street;
            $order->shipping_location_code = $order->shipping_address->location_code;
            $order->shipping_latitude = $order->shipping_address->latitude;
            $order->shipping_longitude = $order->shipping_address->longitude;

            $order->save();
        }
    }

}
