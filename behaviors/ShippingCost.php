<?php namespace Octommerce\Shipping\Behaviors;

class ShippingCost extends \October\Rain\Extension\ExtensionBase
{
    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function addShippingCost($data)
    {
        $this->parent->shipping_cost = $data['cost'] ?: 0;
        $this->parent->shipping_courier = $data['courier'];
        $this->parent->shipping_service = $data['service'];
        $this->parent->save();
    }

}
