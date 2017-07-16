<?php namespace Octommerce\Shipping\Behaviors;

use Octommerce\Shipping\Models\Address;

class ShippingDetails extends \October\Rain\Extension\ExtensionBase
{
    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function addShippingDetails($data)
    {
        $address = Address::find($data['shipping_address_id']);

        $this->parent->is_same_address = 0;
        $this->parent->shipping_address_id = $address->id;
        $this->parent->shipping_location_code = $address->location_code;
        $this->parent->shipping_latitude = $address->latitude;
        $this->parent->shipping_longitude = $address->longitude;
        $this->parent->shipping_name = $address->phone ? $address->phone : $this->parent->name;
        $this->parent->shipping_phone = $address->phone ? $address->phone : $this->parent->phone;
        $this->parent->shipping_address = $address->street;
        $this->parent->save();
    }

}
