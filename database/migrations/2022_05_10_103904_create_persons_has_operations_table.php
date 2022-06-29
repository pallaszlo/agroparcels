<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsHasOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons_has_operations', function (Blueprint $table) {
            $table->uuid('person_uuid');
            $table->uuid('operation_uuid');
            $table->integer('hours_worked');

            $table->foreign('person_uuid')->references('uuid')->on('persons');
            $table->foreign('operation_uuid')->references('uuid')->on('operations');

            $table->primary(['person_uuid', 'operation_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons_has_operations');
    }
}
