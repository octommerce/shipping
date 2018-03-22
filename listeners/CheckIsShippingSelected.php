<?php namespace Octommerce\Shipping\Listeners;

use ApplicationException;

class CheckIsShippingSelected
{

    public function handle($cart, $data)
    {
        //TODO: Validate if there is no active shipping method

        if ( ! isset($data['is_cod'])) {
            throw new ApplicationException('Shipping method not selected!');
        }

        if ( ($courier = array_get($data, 'courier')) && ($service = array_get($data, 'service')) ) {
            $cart->setShipping($courier, $service, $data);
        }

        if (is_null($cart->shipping_courier)) {
            throw new ApplicationException('You have to choose a support shipping courier');
        }
    }

}
