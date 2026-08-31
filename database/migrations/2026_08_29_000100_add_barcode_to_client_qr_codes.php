<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Support\Ean13;

return new class extends Migration {
  public function up(): void {
    Schema::table('client_qr_codes', function (Blueprint $table) {
      $table->string('barcode', 13)->nullable()->after('code_client');
      $table->index('barcode');
    });

    // Attendance barcode rows have no token, so the token_hash column must be
    // nullable. Login QR tokens remain the only hashed values. The existing
    // unique index must be dropped first, then recreated as a filtered unique
    // index (SQL Server cannot hold multiple NULLs in a plain unique index).
    $driver = DB::connection()->getDriverName();
    if ($driver === 'sqlsrv') {
      DB::statement('DROP INDEX client_qr_codes_token_hash_unique ON client_qr_codes');
      DB::statement('ALTER TABLE client_qr_codes ALTER COLUMN token_hash VARCHAR(64) NULL');
      DB::statement('CREATE UNIQUE INDEX client_qr_codes_token_hash_unique ON client_qr_codes (token_hash) WHERE token_hash IS NOT NULL');
    } else {
      Schema::table('client_qr_codes', function (Blueprint $table) {
        $table->dropUnique(['token_hash']);
      });
      Schema::table('client_qr_codes', function (Blueprint $table) {
        $table->string('token_hash', 64)->nullable()->change();
      });
      Schema::table('client_qr_codes', function (Blueprint $table) {
        $table->unique('token_hash');
      });
    }

    // Uniqueness applies only to the (non-NULL) attendance barcodes.
    if ($driver === 'sqlsrv') {
      DB::statement('CREATE UNIQUE INDEX client_qr_codes_barcode_unique ON client_qr_codes (barcode) WHERE barcode IS NOT NULL');
    } else {
      DB::statement('CREATE UNIQUE INDEX client_qr_codes_barcode_unique ON client_qr_codes (barcode)');
    }

    // Backfill: ensure every client has exactly one active ATTENDANCE barcode.
    $clientCodes = DB::table('clients')->pluck('code');

    foreach ($clientCodes as $code) {
      $exists = DB::table('client_qr_codes')
        ->where('code_client', $code)
        ->where('purpose', 'attendance')
        ->where('status', 'active')
        ->exists();
      if ($exists) {
        continue;
      }

      do {
        $barcode = Ean13::generate();
      } while (DB::table('client_qr_codes')->where('barcode', $barcode)->exists());

      DB::table('client_qr_codes')->insert([
        'code_client' => $code,
        'barcode' => $barcode,
        'token_hash' => null,
        'token_version' => 1,
        'purpose' => 'attendance',
        'status' => 'active',
        'scan_count' => 0,
        'created_by' => $code,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }

  public function down(): void {
    Schema::table('client_qr_codes', function (Blueprint $table) {
      $table->dropIndex(['barcode']);
      $table->dropIndex(['client_qr_codes_barcode_unique']);
      $table->dropColumn('barcode');
    });
  }};
