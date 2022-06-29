<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('fitoproduct_uuid');
            $table->uuid('culture_uuid');
            $table->uuid('disease_uuid');

            $table->foreign('fitoproduct_uuid')->references('uuid')->on('fito_products')->onDelete('cascade');
            $table->foreign('culture_uuid')->references('uuid')->on('cultures')->onDelete('cascade');
            $table->foreign('disease_uuid')->references('uuid')->on('diseases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treatments');
    }
}
