<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('status');

            $table->date('available_at')->nullable();
            $table->boolean('available')->default(1)->index();

            $table->string('title')->fulltext()->nullable();
            $table->text('tagline')->fulltext()->nullable();

            $table->float('rent')->nullable();

            $table->smallInteger('size')->nullable()->index();
            $table->string('beds')->nullable()->index();
            $table->string('baths')->nullable()->index();
            $table->string('pets')->nullable();
            $table->string('type')->nullable();
            $table->boolean('garage')->default(0);

            $table->foreignId('property_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropDatabaseIfExists('units');
    }
};
