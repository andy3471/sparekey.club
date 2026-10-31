<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table): void {
            $table->text('discord_webhook_url')->nullable()->change();
        });

        Schema::table('keys', function (Blueprint $table): void {
            $table->text('key')->change();
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table): void {
            $table->string('discord_webhook_url')->nullable()->change();
        });

        Schema::table('keys', function (Blueprint $table): void {
            $table->string('key')->change();
        });
    }
};
