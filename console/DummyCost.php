<?php namespace Octommerce\Shipping\Console;

use Db;
use Illuminate\Console\Command;
use Octommerce\Shipping\Models\Location;
use Octommerce\Shipping\Classes\CourierManager;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressBar;

class DummyCost extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'shipping:dummy-cost';

    /**
     * @var string The console command description.
     */
    protected $description = 'Insert dummy data to courier costs table';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        $this->createDummyCost();
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['courier', InputArgument::REQUIRED,  'Courier code.'],
        ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['level', null, InputOption::VALUE_OPTIONAL, 'Service depth level (Eg. city, state, district or subdistrict)', 'subdistrict'],
        ];
    }

    public function createDummyCost()
    {
        $courier = $this->getCourier();
        $locations = $this->getLocations();

        if ( !$locations || !$courier) return;

        $this->info('Seeding dummy cost...');

        $progressBar = new ProgressBar($this->output, $total = $locations->count());
        $progressBar->setFormat('debug');
        $progressBar->start();

        $perPage = 1000;
        $totalPage = ceil($total / $perPage);
        $serviceColumns = $this->getServiceColumns($courier);

        for($page = 1; $page <= $totalPage; $page++) {
            foreach ($locations->paginate($perPage, $page) as $location) {
                $this->insertDummyCost($courier->table, $location->code, $serviceColumns);
                $progressBar->advance();
            }
        }

        $this->line('');
        $this->info('Done!');
    }

    protected function insertDummyCost($table, $location, $columns)
    {
        $cost = Db::table($table)->where('location', $location)->first();

        if ($cost) return;

        $cost =  array(3000, 5000, 7000, 10000)[rand(0,3)];

        $columns = collect($columns)->map(function($column, $key) use ($cost) {
            return [$column => $cost * ($key + 1)];
        });

        $data = array_merge(['location' => $location], $columns->collapse()->toArray());

        Db::table($table)->insert($data);
    }

    protected function getCourier()
    {
        $courier = $this->argument('courier');
        $courierManager = CourierManager::instance();

        if ( ! $courier = $courierManager->findByAlias($courier, true)) {
            $this->error('Can\'t find courier. Please make sure you input correct alias');
            return null;
        }

        return $courier;
    }

    protected function getServiceColumns($courier)
    {
        return array_map(function($service) use ($courier) {
            return $courier->findServiceColumnByCode($service['code']);
        }, $courier->registerServices());
    }

    protected function getLocations()
    {
        if ( ! $queryArg = $this->getQueryArgument()) {
            $this->error('Level not valid');
            return null;
        }

        $locations = Location::where('code', 'like', $queryArg);

        return $locations;
    }

    protected function getQueryArgument()
    {
        $queryArg = '';

        switch ($this->option('level')) {
        case 'city':
            $queryArg = '__';
            break;
        case 'state':
            $queryArg = '__.__';
            break;
        case 'district':
            $queryArg = '__.__.__';
            break;
        case 'subdistrict':
            $queryArg = '__.__.__.____';
            break;
        default:
            return null;
        }

        return $queryArg;
    }

}
