<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('coach_profiles', function (Blueprint $table) {
      $table->id();
      $table->string('code_employee');
      $table->string('specialization')->nullable();
      $table->integer('max_active_clients')->default(10);
      $table->boolean('is_active')->default(true);
      $table->string('availability')->nullable();
      $table->timestamps();

      $table->foreign('code_employee')->references('code')->on('employees')->onDelete('cascade');
      $table->unique('code_employee');
    });
  }

  public function down(): void {
    Schema::dropIfExists('coach_profiles');
  }
};
