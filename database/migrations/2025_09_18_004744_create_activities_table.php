<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('activities', function (Blueprint $table) {
      $table->id();
      $table->string("code")->unique();
      $table->string("code_client");
      $table->string("code_employee");
      $table->string("name");
      $table->string("description");
      $table->string("state");
      $table->string("day")->nullable();
      $table->string("month");
      $table->integer("times")->defult(1);
      $table->integer("visits");
      $table->string("statement")->defult("true");
      $table->string("code_attachments")->unique();
      $table->timestamps();
      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');
      $table->foreign('code_employee')->references('code')->on('employees')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('activities');
  }
};
