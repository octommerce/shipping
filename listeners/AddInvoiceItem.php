<?php namespace Octommerce\Shipping\Listeners;

use Responsiv\Pay\Models\InvoiceItem;

class AddInvoiceItem
{
    public function handle($order, $invoice)
    {
        if ($order->shipping_cost <= 0)
            return;

        $shippingCostItem = new InvoiceItem([
            'description' => 'Shipping Cost',
            'quantity'    => 1,
            'price'       => $order->shipping_cost,
        ]);

        $invoice->items()->save($shippingCostItem);
    }
}