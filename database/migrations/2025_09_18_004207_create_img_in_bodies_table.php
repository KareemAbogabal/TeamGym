<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('img_in_bodies', function (Blueprint $table) {
      $table->id();
      $table->string("code");
      $table->string("img");
      $table->timestamps();
      $table->foreign('code')->references('code')->on('clients')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('img_in_bodies');
  }
};
