<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layers', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('parcel_uuid');
            $table->string('name', 256);
            $table->polygon('data');
            $table->uuid('culture_uuid')->nullable();
            $table->timestamps();

            $table->foreign('parcel_uuid')
                ->references('uuid')
                ->on('parcels')
                ->onDelete('cascade');
            $table->foreign('culture_uuid')->references('uuid')->on('cultures');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layers');
    }
}
