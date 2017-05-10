<?php namespace Octommerce\Shipping\Behaviors;

class ShippingDetails extends \October\Rain\Extension\ExtensionBase
{
    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function addShippingDetails($data)
    {
        $this->parent->address_id = $data['address_id'];
        $this->parent->location_code = $data['location_code'];
        $this->parent->latitude = $data['latitude'];
        $this->parent->longitude = $data['longitude'];
        $this->parent->save();
    }

}
