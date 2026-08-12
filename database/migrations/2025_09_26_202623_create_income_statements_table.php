<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('income_statements', function (Blueprint $table) {
      $table->id();
      $table->string("code")->unique();
      $table->string("name");
      $table->string("state");
      $table->string("type");
      $table->string("amount");
      $table->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('income_statements');
  }
};
