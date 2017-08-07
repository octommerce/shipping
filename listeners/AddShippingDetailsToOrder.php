<?php namespace Octommerce\Shipping\Listeners;

class AddShippingDetailsToOrder
{

    public function handle($order, $data, $cart)
    {
        $order->addShippingDetails($data);

        $shippingCost = [
            'cost'    => $cart->shipping_cost,
            'courier' => $cart->shipping_courier,
            'service' => $cart->shipping_service,
        ];

        $order->addShippingCost($shippingCost);
    }

}
