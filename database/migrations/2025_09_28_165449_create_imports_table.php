<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('imports', function (Blueprint $table) {
      $table->id();
      $table->string("code")->unique();
      $table->string("code_employee");
      $table->string("name");
      $table->string("state");
      $table->string("type")->nullable();
      $table->string("amount");
      $table->integer("quantity");
      $table->string("code_supplements")->nullable();
      $table->string("code_snaks")->nullable();
      $table->timestamps();
      $table->foreign('code_snaks')->references('code')->on('snacks');
      $table->foreign('code_supplements')->references('code')->on('supplements');
      $table->foreign('code_employee')->references('code')->on('employees');
    });
  }
  public function down(): void {
    Schema::dropIfExists('imports');
  }
};
