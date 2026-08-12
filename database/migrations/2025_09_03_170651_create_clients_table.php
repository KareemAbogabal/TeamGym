<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('clients', function (Blueprint $table) {
      $table->id();
      $table->string("code")->unique();
      $table->string("fname");
      $table->string("lname");
      $table->string("email");
      $table->string("phone");
      $table->string("state")->default("client");
      $table->string("category");
      $table->string("documentation")->default("false");
      $table->string("img")->nullable();
      $table->string("password");
      $table->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('clients');
  }
};
