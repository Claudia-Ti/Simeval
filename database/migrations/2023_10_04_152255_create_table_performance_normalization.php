<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePerformanceNormalization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_performance_normalization', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_criteria');
            $table->uuid('id_officer');
            $table->uuid('id_performance');
            $table->double('norm_value');
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
        Schema::dropIfExists('table_performance_normalization');
    }
}
