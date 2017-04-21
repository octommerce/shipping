<?php namespace Octommerce\Shipping\Classes;

use File;
use Response;
use Cms\Classes\Theme;
use Cms\Classes\Partial;
use System\Classes\PluginManager;
use October\Rain\Support\Collection;

/**
 * Manages payment gateways
 *
 * @package Responsiv.Pay
 * @author Responsiv Internet
 */
class CourierManager
{
    use \October\Rain\Support\Traits\Singleton;

    /**
     * @var array Cache of registration callbacks.
     */
    private $callbacks = [];

    /**
     * @var array List of registered gateways.
     */
    private $couriers;

    /**
     * @var System\Classes\PluginManager
     */
    protected $pluginManager;

    /**
     * Initialize this singleton.
     */
    protected function init()
    {
        $this->pluginManager = PluginManager::instance();
    }

    /**
     * Loads the menu items from modules and plugins
     * @return void
     */
    protected function loadCouriers()
    {
        /*
         * Load module items
         */
        foreach ($this->callbacks as $callback) {
            $callback($this);
        }

        /*
         * Load plugin items
         */
        $plugins = $this->pluginManager->getPlugins();

        foreach ($plugins as $id => $plugin) {
            if (!method_exists($plugin, 'registerShippingCouriers'))
                continue;

            $couriers = $plugin->registerShippingCouriers();
            if (!is_array($couriers))
                continue;

            $this->registerCouriers($id, $couriers);
        }
    }

    /**
     * Registers a callback function that defines a courier.
     * The callback function should register gateways by calling the manager's
     * registerGateways() function. The manager instance is passed to the
     * callback function as an argument. Usage:
     * <pre>
     *   GatewayManager::registerCallback(function($manager){
     *       $manager->registerGateways([...]);
     *   });
     * </pre>
     * @param callable $callback A callable function.
     */
    public function registerCallback(callable $callback)
    {
        $this->callbacks[] = $callback;
    }

    /**
     * Registers the courier.
     * The argument is an array of the gateway classes.
     * @param string $owner Specifies the menu items owner plugin or module in the format Author.Plugin.
     * @param array $classes An array of the courier classes.
     */
    public function registerCouriers($owner, array $classes)
    {
        if (!$this->couriers)
            $this->couriers = [];

        foreach ($classes as $class => $alias) {
            $courier = (object)[
                'owner' => $owner,
                'class' => $class,
                'alias' => $alias,
            ];

            $this->couriers[$alias] = $courier;
        }
    }

    /**
     * Returns a list of the payment gateway classes.
     * @param boolean $asObject As a collection with extended information found in the class object.
     * @return array
     */
    public function listCouriers($asObject = true)
    {
        if ($this->couriers === null) {
            $this->loadCouriers();
        }

        if (!$asObject) {
            return $this->couriers;
        }

        /*
         * Enrich the collection with gateway objects
         */
        $collection = [];
        foreach ($this->couriers as $courier) {
            if (!class_exists($courier->class))
                continue;

            $courierObj = new $courier->class;
            $courierDetails = $courierObj->courierDetails();
            $collection[$courier->alias] = (object)[
                'owner'       => $courier->owner,
                'class'       => $courier->class,
                'alias'       => $courier->alias,
                'object'      => $courierObj,
                'name'        => array_get($courierDetails, 'name', 'Undefined'),
                'description' => array_get($courierDetails, 'description', 'Undefined'),
            ];
        }

        return new Collection($collection);
    }

    /**
     * Returns a list of the payment gateway objects
     * @return array
     */
    public function listCourierObjects()
    {
        $collection = [];
        $couriers = $this->listCouriers();
        foreach ($couriers as $courier) {
            $collection[$courier->alias] = $courier->object;
        }

        return $collection;
    }

    /**
     * Returns a gateway based on its unique alias.
     */
    public function findByAlias($alias, $asInstance = false)
    {
        $couriers = $this->listCouriers(false);
        if (!isset($couriers[$alias]))
            return false;

        if ($asInstance) {
            return new $couriers[$alias]->class;
        }

        return $couriers[$alias];
    }

    //
    // Partials
    //

    /**
     * Loops over each payment type and ensures the editing theme has a payment form partial,
     * if the partial does not exist, it will create one.
     * @return void
     */
    public static function createPartials()
    {
        $partials = Partial::lists('baseFileName', 'baseFileName');
        $paymentMethods = TypeModel::all();

        foreach ($paymentMethods as $paymentMethod) {
            $class = $paymentMethod->class_name;

            if (!$class || get_parent_class($class) != 'Octommerce\Shipping\Classes\CourierBase')
                continue;

            $partialName = 'shipping/'.strtolower(class_basename($class));
            $partialExists = array_key_exists($partialName, $partials);

            if (!$partialExists) {
                $filePath = dirname(File::fromClass($class)).'/'.strtolower(class_basename($class)).'/shipping_form.htm';
                self::createPartialFromFile($partialName, $filePath, Theme::getEditTheme());
            }
        }
    }

    /**
     * Creates a partial using the contents of a specified file.
     * @param  string $name      New Partial name
     * @param  string $filePath  File containing partial contents
     * @param  string $themeCode Theme to create the partial
     * @return void
     */
    protected static function createPartialFromFile($name, $filePath, $themeCode)
    {
        if (!File::exists($filePath))
            return;

        $partial = Partial::inTheme($themeCode);
        $partial->fill([
            'fileName' => $name,
            'markup' => File::get($filePath)
        ]);
        $partial->save();
    }

}
