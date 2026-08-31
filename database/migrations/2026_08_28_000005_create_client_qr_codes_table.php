<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('client_qr_codes', function (Blueprint $table) {
      $table->id();
      $table->string('code_client');
      $table->string('token_hash', 64)->unique();
      $table->integer('token_version')->default(1);
      $table->string('purpose')->default('client_identity');
      $table->string('status')->default('active');
      $table->timestamp('expires_at')->nullable();
      $table->timestamp('last_scanned_at')->nullable();
      $table->unsignedBigInteger('scan_count')->default(0);
      $table->string('created_by')->nullable();
      $table->timestamp('revoked_at')->nullable();
      $table->timestamps();

      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');

      $table->index('code_client');
      $table->index('status');
      $table->index('purpose');
    });
  }

  public function down(): void {
    Schema::dropIfExists('client_qr_codes');
  }
};
