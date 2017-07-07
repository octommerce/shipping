<?php namespace Octommerce\Shipping\Behaviors;

use ApplicationException;
use Octommerce\Shipping\Classes\CourierManager;

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

    public function setShipping($courier, $service, $data = [])
    {
        $courierManager = CourierManager::instance();
        $courierInstance = $courierManager->findByAlias($courier, true);

        if (! $courierInstance) {
            throw new ApplicationException('Courier \'' . $courier . '\' not found.');
        }

        $shippingData = [
            'cost'    => $courierInstance->getShippingCost($data, $this->parent),
            'courier' => $courier,
            'service' => $service,
        ];

        $this->addShippingCost($shippingData);

        return $shippingData;
    }

}
