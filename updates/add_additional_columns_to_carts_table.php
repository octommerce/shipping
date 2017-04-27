<?php namespace Prosehat\Wallet\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddAdditionalColumnsToCartsTable extends Migration
{
    public function up()
    {
        Schema::table('octommerce_octommerce_carts', function(Blueprint $table) {
            $table->string('shipping_courier')->nullable()->after('shipping_cost');
            $table->string('shipping_service')->nullable()->after('shipping_courier');
        });
    }

    public function down()
    {
        Schema::table('octommerce_octommerce_carts', function(Blueprint $table) {
            $table->dropColumn('shipping_courier');
            $table->dropColumn('shipping_service');
        });
    }
}
