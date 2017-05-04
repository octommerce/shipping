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
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => Lang::get('octommerce.shipping::lang.name'),
            'description' => Lang::get('octommerce.shipping::lang.description'),
            'author'      => 'Octommerce',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

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
        });

        OrderController::extendFormFields(function($form, $model, $context) {
            if ( ! $model instanceof OrderStatusLog) return;

            $form->addFields([
                'awb' => [
                    'label' => 'AWB',
                ]
            ]);
        });
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'octommerce.shipping.some_permission' => [
                'tab' => 'Shipping',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'shipping' => [
                'label'       => 'Shipping',
                'url'         => Backend::url('octommerce/shipping/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['octommerce.shipping.*'],
                'order'       => 500,
                'sideMenu' => [

                ]
            ],
        ];
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
