<?php namespace Octommerce\Shipping\Listeners;

class MarkAsCod 
{

    public function handle($order, $data, $cart)
    {
        $order->is_cod = $data['is_cod'];
        $order->save();
    }

}
