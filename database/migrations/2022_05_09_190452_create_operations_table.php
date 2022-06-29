<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name', 55)->nullable();
            $table->string('description', 255)->nullable();
            $table->uuid('work_uuid');
            $table->foreign('work_uuid')
                ->references('uuid')
                ->on('works')
                ->onDelete('cascade');

            $table->uuid('layer_uuid');
            $table->foreign('layer_uuid')
                ->references('uuid')
                ->on('layers')
                ->onDelete('cascade');

            $table->unsignedBigInteger('season_id');
            $table->foreign('season_id')
                ->references('id')
                ->on('seasons')
                ->onDelete('cascade');

            $table->uuid('treatment_uuid')->nullable();
            $table->foreign('treatment_uuid')
                ->references('uuid')
                ->on('treatments')
                ->onDelete('cascade');

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')
                ->references('id')
                ->on('statuses');

            $table->integer('treatment_quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operations');
    }
}
