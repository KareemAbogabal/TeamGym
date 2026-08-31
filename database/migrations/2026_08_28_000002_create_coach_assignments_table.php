<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('coach_assignments', function (Blueprint $table) {
      $table->id();
      $table->string('code_client');
      $table->string('code_coach');
      $table->string('requested_by_type')->default('client');
      $table->unsignedBigInteger('requested_by_id')->nullable();
      $table->string('direction')->default('client_to_coach');
      $table->string('status')->default('pending');
      $table->text('reason')->nullable();
      $table->text('admin_note')->nullable();
      $table->text('rejection_reason')->nullable();
      $table->timestamp('requested_at')->nullable();
      $table->timestamp('approved_at')->nullable();
      $table->timestamp('rejected_at')->nullable();
      $table->timestamp('cancelled_at')->nullable();
      $table->timestamp('started_at')->nullable();
      $table->timestamp('ended_at')->nullable();
      $table->string('approved_by')->nullable();
      $table->string('rejected_by')->nullable();
      $table->timestamps();

      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');
      $table->foreign('code_coach')->references('code')->on('employees')->onDelete('cascade');

      $table->index('code_client');
      $table->index('code_coach');
      $table->index('status');
      $table->index('requested_at');
    });
  }

  public function down(): void {
    Schema::dropIfExists('coach_assignments');
  }
};
