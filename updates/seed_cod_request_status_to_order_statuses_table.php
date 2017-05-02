<?php namespace Octommerce\Shipping\Updates;

use Model;
use Seeder;
use Octommerce\Octommerce\Models\OrderStatus;

class SeedCodRequestStatusToOrderStatusesTable extends Seeder
{
    public function run()
    {
        $statuses = json_decode(file_get_contents(storage_path() . '/database/cod_status.json'), true);

        Model::unguard();

        foreach($statuses as $status) {
            OrderStatus::create($status);
        }

        Model::reguard();
    }
}
