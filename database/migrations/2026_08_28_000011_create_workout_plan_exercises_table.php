<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('workout_plan_exercises', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('workout_plan_day_id');
      $table->string('exercise_name');
      $table->integer('sets')->nullable();
      $table->integer('repetitions')->nullable();
      $table->decimal('weight', 12, 2)->nullable();
      $table->integer('rest_seconds')->nullable();
      $table->integer('duration_minutes')->nullable();
      $table->text('coach_note')->nullable();
      $table->integer('position')->default(0);
      $table->timestamps();

      $table->foreign('workout_plan_day_id')->references('id')->on('workout_plan_days')->onDelete('cascade');

      $table->index('workout_plan_day_id');
    });
  }

  public function down(): void {
    Schema::dropIfExists('workout_plan_exercises');
  }
};
