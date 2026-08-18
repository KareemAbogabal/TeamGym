<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  public function up(): void {
    Schema::table('clients', function (Blueprint $table) {
      $table->integer('year_inbody')->nullable()->after('password');
    });
    DB::table('clients')->update(['year_inbody' => (int) now()->format('Y')]);
  }
  public function down(): void {
    Schema::table('clients', function (Blueprint $table) {
      $table->dropColumn('year_inbody');
    });
  }
};
