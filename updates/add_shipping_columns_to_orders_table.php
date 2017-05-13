<?php namespace Prosehat\Wallet\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddShippingColumnsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('octommerce_octommerce_orders', function(Blueprint $table) {
            $table->integer('shipping_address_id')->nullable()->unsigned()->after('shipping_postcode');
            $table->string('shipping_location_code', 15)->nullable()->after('shipping_address_id');
            $table->decimal('shipping_latitude', 11, 8)->nullable()->after('shipping_location_code');
            $table->decimal('shipping_longitude', 11, 8)->nullable()->after('shipping_latitude');
        });
    }

    public function down()
    {
        Schema::table('octommerce_octommerce_orders', function(Blueprint $table) {
            $table->dropColumn('shipping_address_id');
            $table->dropColumn('shipping_location_code');
            $table->dropColumn('shipping_latitude');
            $table->dropColumn('shipping_longitude');
        });
    }
}
