<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 32)->nullable()->unique()->after('identity_number');
            $table->string('nis', 32)->nullable()->unique()->after('nik');
            $table->string('nisn', 32)->nullable()->unique()->after('nis');
            $table->string('nip', 32)->nullable()->unique()->after('nisn');
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['nik','nis','nisn','nip']));
    }
};
