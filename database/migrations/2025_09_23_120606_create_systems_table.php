<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('systems', function (Blueprint $table) {
      $table->id();
      $table->string("code")->unique();
      $table->string("name");
      $table->string("code_features");
      $table->string("defult")->defult("false");
      $table->string("amount");
      $table->string("duration");
      $table->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('systems');
  }
};
