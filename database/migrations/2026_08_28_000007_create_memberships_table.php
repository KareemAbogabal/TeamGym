<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('memberships', function (Blueprint $table) {
      $table->id();
      $table->string('code_client');
      $table->string('system_code')->nullable();
      $table->string('package_name')->nullable();
      $table->dateTime('starts_at')->nullable();
      $table->dateTime('ends_at')->nullable();
      $table->string('status')->default('pending');
      $table->decimal('amount', 12, 2)->default(0);
      $table->decimal('paid', 12, 2)->default(0);
      $table->timestamp('frozen_at')->nullable();
      $table->timestamp('cancelled_at')->nullable();
      $table->string('created_by')->nullable();
      $table->timestamps();

      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');

      $table->index('code_client');
      $table->index('status');
      $table->index('ends_at');
    });
  }

  public function down(): void {
    Schema::dropIfExists('memberships');
  }
};
