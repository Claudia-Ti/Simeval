<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMonitoringCriteria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_monitoring_criteria', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('mc_name');
            $table->string('description',100)->nullable();
            $table->uuid('id_period');
            $table->double('weight');
            $table->uuid('id_officer');
            $table->softDeletes();
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
        Schema::dropIfExists('table_monitoring_criteria');
    }
}
