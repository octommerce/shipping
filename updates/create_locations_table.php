<?php namespace Octommerce\Shipping\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('octommerce_shipping_locations', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('country_code')->index();
            $table->string('code')->index();
            $table->string('name');
            $table->text('aliases')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octommerce_shipping_locations');
    }
}
