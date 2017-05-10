<?php namespace Prosehat\Wallet\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddAdditionalColumnsToOrdersTable2 extends Migration
{
    public function up()
    {
        Schema::table('octommerce_octommerce_orders', function(Blueprint $table) {
            $table->integer('address_id')->unsigned()->index()->nullable()->after('shipping_service');
            $table->string('location_code')->nullable()->after('address_id');
            $table->decimal('latitude', 11, 8)->nullable()->after('location_code');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    public function down()
    {
        Schema::table('octommerce_octommerce_orders', function(Blueprint $table) {
            $table->dropColumn('address_id');
            $table->dropColumn('location_code');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
