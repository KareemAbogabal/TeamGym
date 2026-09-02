<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('income_statements', function (Blueprint $table) {
      $table->index('type');
      $table->index('state');
    });

    Schema::table('records', function (Blueprint $table) {
      $table->index(['state', 'created_at']);
    });

    Schema::table('notifications', function (Blueprint $table) {
      $table->index('type');
      $table->index(['code_client', 'type']);
      $table->index(['code_employee', 'type']);
    });

    Schema::table('lineages', function (Blueprint $table) {
      $table->index('name');
    });
  }

  public function down(): void {
    Schema::table('income_statements', function (Blueprint $table) {
      $table->dropIndex(['type']);
      $table->dropIndex(['state']);
    });

    Schema::table('records', function (Blueprint $table) {
      $table->dropIndex(['state', 'created_at']);
    });

    Schema::table('notifications', function (Blueprint $table) {
      $table->dropIndex(['type']);
      $table->dropIndex(['code_client', 'type']);
      $table->dropIndex(['code_employee', 'type']);
    });

    Schema::table('lineages', function (Blueprint $table) {
      $table->dropIndex(['name']);
    });
  }
};
