<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonHasWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_has_work', function (Blueprint $table) {
            $table->uuid('person_uuid');
            $table->foreign('person_uuid')
                ->references('uuid')
                ->on('persons')
                ->onDelete('cascade');

            $table->uuid('work_uuid');
            $table->foreign('work_uuid')
                ->references('uuid')
                ->on('works')
                ->onDelete('cascade');

            $table->primary(['person_uuid', 'work_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_work');
    }
}
