<?php namespace Octommerce\Shipping\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddPostcodeToAddressesTable extends Migration
{
    public function up()
    {
        Schema::table('octommerce_shipping_addresses', function(Blueprint $table) {
            $table->string('postcode', 10)->after('street')->nullable();
        });
    }

    public function down()
    {
        Schema::table('octommerce_shipping_addresses', function(Blueprint $table) {
            $table->dropColumn('postcode');
        });
    }
}
