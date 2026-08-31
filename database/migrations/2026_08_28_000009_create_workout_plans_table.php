<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('workout_plans', function (Blueprint $table) {
      $table->id();
      $table->string('code_client');
      $table->string('code_coach')->nullable();
      $table->string('title');
      $table->text('description')->nullable();
      $table->integer('version')->default(1);
      $table->string('status')->default('active'); // active | paused | archived
      $table->string('created_by')->nullable();
      $table->timestamps();

      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');
      $table->foreign('code_coach')->references('code')->on('employees')->onDelete('cascade');

      $table->index('code_client');
      $table->index('code_coach');
      $table->index('status');
    });
  }

  public function down(): void {
    Schema::dropIfExists('workout_plans');
  }
};
