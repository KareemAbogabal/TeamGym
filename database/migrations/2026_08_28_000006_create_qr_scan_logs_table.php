<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('qr_scan_logs', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('qr_code_id')->nullable();
      $table->string('code_client')->nullable();
      $table->timestamp('scanned_at');
      $table->string('ip', 45)->nullable();
      $table->string('user_agent')->nullable();
      $table->string('source')->nullable();
      $table->string('authenticated_user')->nullable();
      $table->boolean('success')->default(false);
      $table->string('reason')->nullable();
      $table->timestamps();

      $table->foreign('qr_code_id')->references('id')->on('client_qr_codes')->onDelete('cascade');

      $table->index('qr_code_id');
      $table->index('code_client');
      $table->index('scanned_at');
      $table->index('success');
    });
  }

  public function down(): void {
    Schema::dropIfExists('qr_scan_logs');
  }
};
