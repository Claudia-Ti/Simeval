<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePerformanceReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_performance_report', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_period');
            $table->uuid('id_criteria');
            $table->uuid('id_officer');
            $table->double('perf_value');
            $table->double('norm_value')->nullable();
            $table->double('preference_value')->nullable();
            $table->string('notes',100)->nullable();
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
        Schema::dropIfExists('table_performance_report');
    }
}
