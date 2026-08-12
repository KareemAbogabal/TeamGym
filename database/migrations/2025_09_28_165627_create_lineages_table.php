<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('lineages', function (Blueprint $table) {
      $table->id();
      $table->string("code");
      $table->string("code_supplements")->nullable();
      $table->string("code_systems")->nullable();
      $table->string("code_snacks")->nullable();
      $table->string("inputs")->nullable();
      $table->string("IncomeStatement")->nullable();
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
      $table->foreign('code_supplements')->references('code')->on('supplements');
      $table->foreign('code_systems')->references('code')->on('systems');
      $table->foreign('code_snacks')->references('code')->on('snacks');
      $table->foreign('inputs')->references('code')->on('imports');
      $table->foreign('IncomeStatement')->references('code')->on('income_statements');
    });
  }
  public function down(): void {
    Schema::dropIfExists('lineages');
  }
};
