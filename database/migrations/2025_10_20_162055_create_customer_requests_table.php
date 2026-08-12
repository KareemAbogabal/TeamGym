<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('customer_requests', function (Blueprint $table) {
      $table->id();
      $table->string("code");
      $table->string("code_client")->nullable();
      $table->string("code_order");
      $table->string("code_payment")->nullable();
      $table->string("fname");
      $table->string("lname");
      $table->string("email")->nullable();
      $table->string("quantity")->nullable();
      $table->string("phone");
      $table->string("type");
      $table->string("state");
      $table->string("system")->nullable();
      $table->string("supplement")->nullable();
      $table->bigInteger("amount");
      $table->bigInteger("paid")->defult(0);
      $table->timestamps();
      $table->foreign('code_client')->references('code')->on('clients');
      $table->foreign('code_payment')->references('code')->on('payments');
    });
  }
  public function down(): void {
    Schema::dropIfExists('customer_requests');
  }
};
