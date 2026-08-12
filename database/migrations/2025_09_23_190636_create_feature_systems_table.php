<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('feature_systems', function (Blueprint $table) {
      $table->id();
      $table->string("code");
      $table->string("name");
      $table->string("state")->defult("true");
      $table->string("code_system");
      $table->timestamps();
      $table->foreign('code_system')->references('code')->on('systems')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('feature_systems');
  }
};
