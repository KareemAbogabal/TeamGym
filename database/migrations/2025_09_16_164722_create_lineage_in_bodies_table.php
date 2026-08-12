<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('lineage_in_bodies', function (Blueprint $table) {
      $table->id();
      $table->string("code");
      $table->string('name');
      $table->string('january')->nullable();
      $table->string('february')->nullable();
      $table->string('march')->nullable();
      $table->string('april')->nullable();
      $table->string('may')->nullable();
      $table->string('june')->nullable();
      $table->string('july')->nullable();
      $table->string('august')->nullable();
      $table->string('september')->nullable();
      $table->string('october')->nullable();
      $table->string('november')->nullable();
      $table->string('december')->nullable();
      $table->timestamps();
      $table->foreign('code')->references('code')->on('clients')->onDelete('cascade');
    });
  }
  public function down(): void {
    Schema::dropIfExists('lineage_in_bodies');
  }
};
