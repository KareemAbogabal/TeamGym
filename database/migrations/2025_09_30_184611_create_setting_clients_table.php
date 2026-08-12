<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('setting_clients', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->string('code_client');
      $table->boolean('class_reminders')->default(false);
      $table->boolean('payment_date')->default(false);
      $table->boolean('promotions')->default(false);
      $table->timestamps();
      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('setting_clients');
  }
};
