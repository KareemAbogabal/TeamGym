<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('client_goals', function (Blueprint $table) {
      $table->id();
      $table->string('code_client');
      $table->string('code_coach')->nullable();
      $table->string('title');
      $table->text('description')->nullable();
      $table->decimal('target_value', 12, 2)->nullable();
      $table->decimal('current_value', 12, 2)->nullable();
      $table->string('unit')->nullable();
      $table->date('start_date')->nullable();
      $table->date('target_date')->nullable();
      $table->string('status')->default('active');
      $table->timestamps();

      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');
      $table->foreign('code_coach')->references('code')->on('employees')->onDelete('cascade');

      $table->index('code_client');
      $table->index('code_coach');
    });
  }

  public function down(): void {
    Schema::dropIfExists('client_goals');
  }
};
