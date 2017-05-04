<?php namespace Octommerce\Shipping\Listeners;

use Octommerce\Octommerce\Models\OrderStatusLog;
use Octommerce\Octommerce\Models\OrderStatus;

class MarkAsCod
{

    public function handle($order, $data, $cart)
    {
        if (isset($data['is_cod']) && $data['is_cod'] == false) return;

        $order->is_cod = true;
        $order->save();

        $orderStatus = OrderStatus::whereCode('cod-request')->first();

        OrderStatusLog::createRecord($orderStatus, $order, null);
    }

}
