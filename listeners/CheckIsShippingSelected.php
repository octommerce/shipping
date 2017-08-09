<?php namespace Octommerce\Shipping\Listeners;

class CheckIsShippingSelected
{

    public function handle($cart, $data)
    {
        //TODO: Validate if there is no active shipping method

        if ( ! isset($data['is_cod'])) {
            throw new \ApplicationException('Shipping method not selected!');
        }

        /**
         * User has selected COD and courier
         **/
        if ($data['is_cod'] == 1 && isset($data['courier'])) return;

        if ( ! isset($data['courier']) || ! isset($data['service'])) {
            throw new \ApplicationException('Courier service not selected!');
        }
    }

}
