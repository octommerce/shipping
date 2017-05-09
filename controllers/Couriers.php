<?php namespace Octommerce\Shipping\Controllers;

use Model;
use BackendMenu;
use Backend\Classes\Controller;
use Octommerce\Shipping\Classes\CourierManager;

/**
 * Couriers Back-end Controller
 */
class Couriers extends Controller
{
    protected $manager;

    protected $couriers;

    protected $formWidget;

    public function __construct()
    {
        parent::__construct();

        $this->manager = CourierManager::instance();

        BackendMenu::setContext('Octommerce.Shipping', 'shipping', 'couriers');
    }

    public function index()
    {
        $this->vars['couriers'] = $this->couriers = $this->manager->listCouriers();
    }

    public function formRender($courierCode)
    {
        $courier = $this->couriers[$courierCode];

        $config = $courier->object->getFieldConfig();
        $config->model = new Model;

        $this->formWidget = $this->makeWidget('Backend\Widgets\Form', $config);

        return $this->formWidget->render();
    }
}
