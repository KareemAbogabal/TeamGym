<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->string("code")->unique();
      $table->string("code_client");
      $table->string("code_employee");
      $table->string("code_supplements")->nullable();
      $table->string("code_systems")->nullable();
      $table->string("code_snacks")->nullable();
      $table->string("code_request_payment")->nullable();
      $table->string("order_name");
      $table->string("type");
      $table->string("paymonth");
      $table->string("payday");
      $table->bigInteger('amount');
      $table->bigInteger("paid")->defult(0);
      $table->timestamps();
      $table->foreign('code_client')->references('code')->on('clients');
      $table->foreign('code_employee')->references('code')->on('employees');
      $table->foreign('code_supplements')->references('code')->on('supplements');
      $table->foreign('code_systems')->references('code')->on('systems');
      $table->foreign('code_snacks')->references('code')->on('snacks');
      $table->foreign('code_request_payment')->references('code')->on('requests_payments');
    });
  }
  public function down(): void {
    Schema::dropIfExists('payments');
  }
};
