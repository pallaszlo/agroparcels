<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkHasWorkMachineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_has_work_machine', function (Blueprint $table) {
            $table->uuid('work_uuid');
            $table->foreign('work_uuid')
                ->references('uuid')
                ->on('works')
                ->onDelete('cascade');

            $table->uuid('work_machine_uuid');
            $table->foreign('work_machine_uuid')
                ->references('uuid')
                ->on('work_machines')
                ->onDelete('cascade');
    
            $table->uuid('person_uuid');
            $table->foreign('person_uuid')
                ->references('uuid')
                ->on('persons')
                ->onDelete('cascade');

            $table->primary(['work_uuid', 'work_machine_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_has_work_machine');
    }
}
