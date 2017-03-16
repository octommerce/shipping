<?php namespace Octommerce\Shipping\Components;

use Cms\Classes\ComponentBase;
use Octommerce\Octommerce\Components\Checkout;

class CheckoutShipping extends ComponentBase
{
    public $parentComponent = 'Octommerce\Octommerce\Components\Checkout';

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
    }
}
