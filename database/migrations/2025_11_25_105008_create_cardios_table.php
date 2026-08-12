<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('cardios', function (Blueprint $table) {
      $table->id();
      $table->string("code");
      $table->string("code_client");
      $table->string("name");
      $table->string("minutes");
      $table->string("distance");
      $table->string("start_latitude")->nullable();
      $table->string("start_longitude")->nullable();
      $table->string("end_latitude")->nullable();
      $table->string("end_longitude")->nullable();
      $table->timestamps();
      $table->foreign('code_client')->references('code')->on('clients')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('cardios');
  }
};
