<?php namespace Octommerce\Shipping\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('octommerce_shipping_addresses', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('location_code', 15)->nullable();
            $table->string('address_name', 100)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('phone', 15)->nullable();
            $table->text('street')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_primary')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octommerce_shipping_addresses');
    }
}
