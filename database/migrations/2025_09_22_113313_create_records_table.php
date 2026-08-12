<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('records', function (Blueprint $table) {
      $table->id();
      $table->string("code_client");
      $table->string("name_client");
      $table->string("state");
      $table->string("amount");
      $table->string("attachment")->nullable();
      $table->string("code_employee");
      $table->string("name_employee");
      $table->string("phone_employee");
      $table->string("job_role_employee");
      $table->timestamps();
      $table->foreign('code_client')->references('code')->on('clients');
      $table->foreign('code_employee')->references('code')->on('employees');
    });
  }
  public function down(): void {
    Schema::dropIfExists('records');
  }
};
