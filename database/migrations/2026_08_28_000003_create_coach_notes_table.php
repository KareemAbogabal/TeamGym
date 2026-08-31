<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('coach_notes', function (Blueprint $table) {
      $table->id();
      $table->string('code_client');
      $table->string('code_coach');
      $table->text('note');
      $table->string('visibility')->default('private_to_coach');
      $table->timestamps();

      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');
      $table->foreign('code_coach')->references('code')->on('employees')->onDelete('cascade');

      $table->index('code_client');
      $table->index('code_coach');
    });
  }

  public function down(): void {
    Schema::dropIfExists('coach_notes');
  }
};
