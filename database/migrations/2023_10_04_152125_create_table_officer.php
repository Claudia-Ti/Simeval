<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOfficer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_officer', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('o_name');
            $table->string('position',25);
            $table->string('unit',50);
            $table->enum('gender',['P','L'])->default('L');
            $table->date('dob');
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
        Schema::dropIfExists('table_officer');
    }
}
