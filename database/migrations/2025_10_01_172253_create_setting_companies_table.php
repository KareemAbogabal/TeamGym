<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('setting_companies', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->string('code_employee');
      $table->boolean('view_logs_logins')->default(true);
      $table->boolean('supplements_requests')->default(true);
      $table->boolean('subscription_requests')->default(true);
      $table->boolean('add_employees')->default(false);
      $table->boolean('subscription_application_form')->default(true);
      $table->timestamps();
      $table->foreign('code_employee')->references('code')->on('employees')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('setting_companies');
  }
};
