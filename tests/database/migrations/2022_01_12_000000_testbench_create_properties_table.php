<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->uuid('uuid');
            $table->string('name')->index();

            $table->string('type')->index()->nullable();
            $table->string('status')->index()->nullable();
            $table->json('options')->nullable();
            $table->json('contact')->nullable();
            $table->json('address')->nullable();

            $table->foreignId('company_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropDatabaseIfExists('properties');
    }
};
