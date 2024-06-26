<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCriteria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_criteria', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('c_name');
            $table->enum('c_type',['Benefit','Cost'])->default('Benefit');
            $table->double('weight');
            $table->uuid('id_officer');
            $table->uuid('id_period');
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
        Schema::dropIfExists('table_criteria');
    }
}
