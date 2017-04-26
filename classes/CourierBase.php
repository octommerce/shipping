<?php namespace Octommerce\Shipping\Classes;

use Db;
use Str;
use URL;
use File;
use System\Classes\ModelBehavior;

/**
 * Represents the generic courier.
 * All other courier must be derived from this class
 */
class CourierBase extends ModelBehavior
{
    use \System\Traits\ConfigMaker;

    protected $orderModel = 'Octommerce\Octommerce\Models\Order';

    protected $orderStatusModel = 'Octommerce\Octommerce\Models\OrderStatus';

    /**
     * Returns information about the courier
     * Must return array:
     *
     * [
     *      'name'        => 'FedEx',
     *      'description' => 'FedEx simple integration method with hosted payment form.'
     * ]
     *
     * @return array
     */
    public function courierDetails()
    {
        return [
            'name'        => 'Unknown',
            'description' => 'Unknown courier.'
        ];
    }

    /**
     * @var mixed Extra field configuration for the payment type.
     */
    protected $fieldConfig;

    /**
     * @var string Regex pattern for AWB.
     */
    protected $AWBPattern = '/[A-z0-9 ]+/';

    /**
     * Constructor
     */
    public function __construct($model = null)
    {
        parent::__construct($model);

        /*
         * Parse the config
         */
        $this->configPath = $this->guessConfigPathFrom($this);
        $this->fieldConfig = $this->makeConfig($this->defineFormFields());

        if (!$model)
            return;

        $this->boot($model);
    }

    /**
     * Boot method called when the payment gateway is first loaded
     * with an existing model.
     * @return array
     */
    public function boot($host)
    {
        // Set default data
        if (!$host->exists)
            $this->initConfigData($host);

        // Apply validation rules
        $host->rules = array_merge($host->rules, $this->defineValidationRules());
    }

    /**
     * Extra field configuration for the payment type.
     */
    public function defineFormFields()
    {
        return 'fields.yaml';
    }

    /**
     * Initializes configuration data when the payment method is first created.
     * @param  Model $host
     */
    public function initConfigData($host){}

    /**
     * Defines validation rules for the custom fields.
     * @return array
     */
    public function defineValidationRules()
    {
        return [];
    }

    /**
     * Render setup help
     * @return string
     */
    public function getPartialPath()
    {
        return $this->configPath;
    }

    /**
     * Registers available services from this courier.
     * @return array Returns an array containing list of available services.
     */
    public function registerServices()
    {
        return [];
    }

    /**
     * Returns the field configuration used by this model.
     */
    public function getFieldConfig()
    {
        return $this->fieldConfig;
    }

    /**
     * Returns true if the payment type is applicable for a specified invoice amount
     * @param array $data Posted payment form data.
     * @param Model $cart Cart model object containing configuration fields values.
     * @return array list of available services
     */
    public function getAvailableServices($data, $cart)
    {
        return $this->registerServices();
    }

    /**
     * Get the shipping cost from data.
     * @param array $data Posted payment form data.
     * @param Model $cart Cart model object containing configuration fields values.
     */
    public function getShippingCost($data, $cart) { }

    /**
     * Get cost record by given location
     *
     * @param string $location
     */
    protected function getCostRecord($location)
    {
        return Db::table($this->table)
            ->where('location', '=', $location)
            ->orWhere('location', 'like', substr($location, 0, 2))
            ->orWhere('location', 'like', substr($location, 0, 5))
            ->orWhere('location', 'like', substr($location, 0, 8))
            ->orderByRaw('LENGTH(`location`) desc')
            ->first();
    }

}
