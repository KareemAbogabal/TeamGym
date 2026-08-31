<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('inbody_measurements', function (Blueprint $table) {
      $table->id();
      $table->string('code_client');
      $table->timestamp('measured_at')->nullable();
      $table->decimal('weight', 10, 2)->nullable();
      $table->decimal('bmi', 10, 2)->nullable();
      $table->decimal('pbf', 10, 2)->nullable();
      $table->decimal('smm', 10, 2)->nullable();
      $table->decimal('kcal', 10, 2)->nullable();
      $table->decimal('water', 10, 2)->nullable();
      $table->decimal('fat_mass', 10, 2)->nullable();
      $table->decimal('protein', 10, 2)->nullable();
      $table->decimal('left_arm_lean', 10, 2)->nullable();
      $table->decimal('right_arm_lean', 10, 2)->nullable();
      $table->decimal('left_leg_lean', 10, 2)->nullable();
      $table->decimal('right_leg_lean', 10, 2)->nullable();
      $table->decimal('left_arm_fat', 10, 2)->nullable();
      $table->decimal('right_arm_fat', 10, 2)->nullable();
      $table->decimal('left_leg_fat', 10, 2)->nullable();
      $table->decimal('right_leg_fat', 10, 2)->nullable();
      $table->string('source')->nullable();
      $table->string('image_path')->nullable();
      $table->string('created_by')->nullable();
      $table->timestamps();

      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');

      $table->index('code_client');
      $table->index('measured_at');
    });
  }

  public function down(): void {
    Schema::dropIfExists('inbody_measurements');
  }
};
