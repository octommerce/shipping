<?php namespace Octommerce\Shipping\Behaviors;

class ShippingCost extends \October\Rain\Extension\ExtensionBase
{
    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function addShippingCost($amount)
    {
        $this->parent->shipping_cost = $amount;
        $this->parent->save();
    }

}
