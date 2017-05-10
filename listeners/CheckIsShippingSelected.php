<?php namespace Octommerce\Shipping\Listeners;

class CheckIsShippingSelected
{

    public function handle($order, $data)
    {
        //TODO: Validate if there is no active shipping method

        if ( ! isset($data['is_cod'])) {
            throw new \ApplicationException('Shipping method not selected!');
        }

        if ( ! isset($data['courier']) || ! isset($data['service'])) {
            throw new \ApplicationException('Courier service not selected!');
        }
    }

}
