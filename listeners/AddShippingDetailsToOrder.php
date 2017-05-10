<?php namespace Octommerce\Shipping\Listeners;

use Prosehat\Wallet\Models\Voucher;
use Prosehat\Wallet\Validation\Voucher\Classes\Validator;
use Prosehat\Wallet\Validation\Campaign\Classes\Validator as CampaignValidator;

class AddShippingDetailsToOrder 
{

    public function handle($order, $data, $cart)
    {
        $shippingDetails = [
            'address_id'    => $data['selected_address_id'],
            'location_code' => $data['location'],
            'latitude'      => $data['latitude'],
            'longitude'     => $data['longitude']
        ];

        $order->addShippingDetails($shippingDetails);
    }

}
