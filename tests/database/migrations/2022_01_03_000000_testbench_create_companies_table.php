<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->json('settings')->nullable();
            $table->string('type')->index()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropDatabaseIfExists('companies');
    }
};
