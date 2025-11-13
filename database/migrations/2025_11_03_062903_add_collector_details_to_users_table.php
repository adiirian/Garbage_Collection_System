<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable()->after('role_id');
            $table->integer('age')->nullable()->after('address');
            $table->decimal('daily_salary', 8, 2)->nullable()->after('age');
            $table->enum('status', ['on_duty', 'on_break', 'off_duty'])->default('off_duty')->after('daily_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['address', 'age', 'daily_salary', 'status']);
        });
    }
};
