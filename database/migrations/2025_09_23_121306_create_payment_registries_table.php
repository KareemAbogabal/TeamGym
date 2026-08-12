<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('payment_registries', function (Blueprint $table) {
      $table->id();
      $table->string("code");
      $table->string("order_name");
      $table->string("type");
      $table->string("amount");
      $table->string("paymonth");
      $table->string("code_payments");
      $table->string("code_employee");
      $table->timestamps();
      $table->foreign('code_payments')->references('code')->on('payments');
      $table->foreign('code_employee')->references('code')->on('employees');
    });
  }
  public function down(): void {
    Schema::dropIfExists('payment_registries');
  }
};
