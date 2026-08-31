<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('attendance_sessions', function (Blueprint $table) {
      $table->id();
      $table->string('code_client');
      $table->timestamp('entrance_at')->nullable();
      $table->timestamp('exit_at')->nullable();
      $table->string('entrance_source')->nullable();
      $table->string('exit_source')->nullable();
      $table->string('entrance_employee')->nullable();
      $table->string('exit_employee')->nullable();
      $table->string('entrance_device')->nullable();
      $table->string('exit_device')->nullable();
      $table->string('status')->default('open');
      $table->timestamps();

      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');

      $table->index('code_client');
      $table->index('entrance_at');
      $table->index('status');
    });
  }

  public function down(): void {
    Schema::dropIfExists('attendance_sessions');
  }
};
