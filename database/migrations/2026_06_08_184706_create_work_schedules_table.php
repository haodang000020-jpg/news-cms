<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();

            $table->date('work_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->string('title', 500);
            $table->string('location', 255)->nullable();
            $table->string('chairperson', 255)->nullable();
            $table->text('participants')->nullable();

            $table->boolean('status')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};