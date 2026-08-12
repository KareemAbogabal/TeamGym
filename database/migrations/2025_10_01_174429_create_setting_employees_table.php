<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('setting_employees', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->string('code_employee');
      $table->boolean('class_reminders')->default(false);
      $table->boolean('login_alerts')->default(false);
      $table->timestamps();
      $table->foreign('code_employee')->references('code')->on('employees')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('setting_employees');
  }
};
