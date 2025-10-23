<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinsTable extends Migration
{
    public function up()
    {
        Schema::create('bins', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->enum('status', ['empty', 'full', 'overflowing']);
            $table->integer('level')->default(0); // Level can be a percentage or a specific measurement
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bins');
    }
}