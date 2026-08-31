<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('workout_plan_days', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('workout_plan_id');
      $table->string('day_name');
      $table->integer('position')->default(0);
      $table->text('coach_note')->nullable();
      $table->timestamps();

      $table->foreign('workout_plan_id')->references('id')->on('workout_plans')->onDelete('cascade');

      $table->index('workout_plan_id');
    });
  }

  public function down(): void {
    Schema::dropIfExists('workout_plan_days');
  }
};
