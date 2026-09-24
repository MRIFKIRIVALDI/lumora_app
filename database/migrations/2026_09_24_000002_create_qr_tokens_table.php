<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qr_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained('qr_stations')->cascadeOnDelete();
            $table->char('token_hash', 64)->unique();
            $table->timestamp('expires_at')->index();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('qr_token_id')->nullable()->after('station_id')->constrained('qr_tokens')->nullOnDelete();
        });
    }
    public function down(): void
    {
        Schema::table('attendances', fn(Blueprint $table) => $table->dropConstrainedForeignId('qr_token_id'));
        Schema::dropIfExists('qr_tokens');
    }
};
