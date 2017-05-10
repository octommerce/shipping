<?php namespace Octommerce\Shipping;

use Lang;
use Backend;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use Octommerce\Octommerce\Models\Cart;
use Octommerce\Octommerce\Models\Order;
use Octommerce\Octommerce\Models\OrderStatusLog;
use Octommerce\Octommerce\Controllers\Orders as OrderController;

/**
 * Shipping Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Octommerce.Octommerce', 'RainLab.User'];

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConsoleCommand('shipping.dummy-cost', 'Octommerce\Shipping\Console\DummyCost');
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        User::extend(function($model) {
            $model->hasMany['addresses'] = [
                'Octommerce\Shipping\Models\Address',
            ];

            $model->addDynamicMethod('primaryAddress', function() use ($model) {
                return $model->addresses()->filterPrimaryAddress()->first();
            });
        });

        Cart::extend(function($cartModel) {
            $cartModel->implement[] = 'Octommerce\Shipping\Behaviors\ShippingCost';
        });

        Order::extend(function($orderModel) {
            $orderModel->implement[] = 'Octommerce\Shipping\Behaviors\ShippingCost';
            $orderModel->implement[] = 'Octommerce\Shipping\Behaviors\ShippingDetails';
        });

        OrderController::extendFormFields(function($form, $model, $context) {
            if ( ! $model instanceof OrderStatusLog) return;

            $form->addFields([
                'awb' => [
                    'label' => 'AWB',
                    'trigger' => [
                        'action'    => 'show',
                        'field'     => 'status',
                        'condition' => 'value[shipped]',
                    ]
                ]
            ]);
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Octommerce\Shipping\Components\Locations'        => 'locations',
            'Octommerce\Shipping\Components\CheckoutShipping' => 'checkoutShipping',
        ];
    }

    /**
     * Register new Twig variables
     * @return array
     */
    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'form_select_province'    => ['Octommerce\Shipping\Models\Location', 'formSelectProvince'],
                'form_select_city'        => ['Octommerce\Shipping\Models\Location', 'formSelectCity'],
                'form_select_district'    => ['Octommerce\Shipping\Models\Location', 'formSelectDistrict'],
                'form_select_subdistrict' => ['Octommerce\Shipping\Models\Location', 'formSelectSubdistrict'],
            ]
        ];
    }
}
