<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_settings')
            ->where('key', 'whatsapp')
            ->whereIn('value', ['0749616161', '225749616161'])
            ->update(['value' => '225778969396', 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->where('key', 'whatsapp')
            ->where('value', '225778969396')
            ->update(['value' => '0749616161', 'updated_at' => now()]);
    }
};
