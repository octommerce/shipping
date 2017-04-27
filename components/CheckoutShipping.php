<?php namespace Octommerce\Shipping\Components;

use Cart;
use Cms\Classes\ComponentBase;
use Octommerce\Octommerce\Components\Checkout;
use Octommerce\Shipping\Classes\CourierManager;

class CheckoutShipping extends ComponentBase
{
    public $parentComponent = 'Octommerce\Octommerce\Components\Checkout';

    public $couriers;

    public $services;

    private $courierManager;

    public function componentDetails()
    {
        return [
            'name'        => 'Shipping Component',
            'description' => 'Checkout with shipping module component.'
        ];
    }

    public function init()
    {
        $this->addComponent(
            'Octommerce\Octommerce\Components\Checkout',
            'checkout',
            []
        );

        $this->addComponent(
            'Octommerce\Shipping\Components\Locations',
            'locations',
            []
        );

        $this->courierManager = CourierManager::instance();
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->couriers = $this->page['couriers'] = $this->loadCouriers();
    }

    public function onSelectCourier()
    {
        $this->services = $this->page['services'] = $this->loadServices();
    }

    public function onSelectService()
    {
        $cart = Cart::get();
        $shippingData = [
            'cost'    => $this->getCourier()->getShippingCost(post(), $cart),
            'courier' => post('courier'),
            'service' => post('service')
        ];

        $cart->addShippingCost($shippingData);

        $this->page['shippingCost'] = $shippingData['cost'];
    }

    protected function loadCouriers()
    {
        return $this->courierManager->listCouriers(true);
    }

    protected function loadServices()
    {
        return $this->getCourier()->getAvailableServices(post(), Cart::get());
    }

    /**
     * Get courier object
     *
     * @param boolean $asInstance Instance of courier class instead of courier plugin definition
     */
    private function getCourier($asInstance = true)
    {
        return $this->courierManager->findByAlias(post('courier'), $asInstance);
    }

}
