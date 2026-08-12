<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('activity_attachments', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('code');
      $table->string("img");
      $table->string("video");
      $table->timestamps();
      $table->foreign('code')->references('id')->on('activity_elements')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('activity_attachments');
  }
};
