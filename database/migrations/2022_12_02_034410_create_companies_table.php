<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); // unsignedBigInteger (Actually : it's an alias of the bigIncrements method) )-> UNSIGNED BIG INTEGER PRIMARY KEY AUTO_INCREMNT
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->comment('Email of the company');
            $table->timestamps(); // created_at , updated_at -> TIMESTAMP (used to track when it was first created and updated)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
