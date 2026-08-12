<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('notifications', function (Blueprint $table) {
      $table->id();
      $table->string("code");
      $table->string("code_client")->nullable();
      $table->string("code_employee")->nullable();
      $table->string('code_supplements')->nullable();
      $table->string('code_systems')->nullable();
      $table->text("icon");
      $table->string("name");
      $table->string("type");
      $table->text("description");
      $table->string("amount")->nullable();
      $table->string("residual")->nullable();
      $table->timestamps();
      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');
      $table->foreign('code_employee')->references('code')->on('employees')->onDelete('cascade');
      $table->foreign('code_supplements')->references('code')->on('supplements')->onDelete('cascade');
      $table->foreign('code_systems')->references('code')->on('systems')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('notifications');
  }
};
