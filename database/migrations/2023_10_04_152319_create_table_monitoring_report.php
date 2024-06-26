<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMonitoringReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_monitoring_report', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_officer');
            $table->uuid('id_mon_crit');
            $table->uuid('id_period');
            $table->double('mon_value');
            $table->double('preference_value')->nullable()->default(0);
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
        Schema::dropIfExists('table_monitoring_report');
    }
}
