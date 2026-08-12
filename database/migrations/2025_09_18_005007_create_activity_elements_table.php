<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('activity_elements', function (Blueprint $table) {
      $table->id();
      $table->string("code_activities");
      $table->string("name");
      $table->string("ratio");
      $table->string("sets");
      $table->timestamps();
      $table->foreign('code_activities')->references('code_attachments')->on('activities')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('activity_elements');
  }
};
