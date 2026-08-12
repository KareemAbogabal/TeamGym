<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('employees', function (Blueprint $table) {
      $table->id();
      $table->string("code")->unique();
      $table->string("fname");
      $table->string("lname");
      $table->string("job_role");
      $table->string("phone");
      $table->string("img")->nullable();
      $table->string("email");
      $table->string("password");
      $table->string("documentation")->default("false");
      $table->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('employees');
  }
};
