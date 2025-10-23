<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bin_id')->constrained('bins')->cascadeOnDelete();
            $table->enum('type', ['level_change', 'public_report', 'collector_action'])->default('level_change');
            $table->string('level')->nullable(); // reported level value
            $table->string('status')->default('open'); // open, in_progress, closed
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('message')->nullable();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alerts');
    }
};
