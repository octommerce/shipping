<?php namespace Prosehat\Wallet\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddShippingCostColumnToCartsTable extends Migration
{
    public function up()
    {
        Schema::table('octommerce_octommerce_carts', function(Blueprint $table) {
            $table->decimal('shipping_cost', 12, 2)->unsigned()->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('octommerce_octommerce_carts', function(Blueprint $table) {
            $table->dropColumn('shipping_cost');
        });
    }
}
