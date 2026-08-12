<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('histories', function (Blueprint $table) {
      $table->id();
      $table->string("code");
      $table->string("code_client")->nullable();
      $table->string("code_employee")->nullable();
      $table->string("name");
      $table->string("state");
      $table->string("amount")->nullable();
      $table->string("attachment")->nullable();
      $table->string("registered_entity")->nullable();
      $table->timestamps();
      $table->foreign('code_client')->references('code')->on('clients');
      $table->foreign('code_employee')->references('code')->on('employees');
    });
  }
  public function down(): void {
    Schema::dropIfExists('histories');
  }
};
