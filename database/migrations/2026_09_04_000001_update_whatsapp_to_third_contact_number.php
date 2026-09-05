<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_settings')
            ->where('key', 'whatsapp')
            ->where('value', '225778969396')
            ->update(['value' => '225708236417', 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->where('key', 'whatsapp')
            ->where('value', '225708236417')
            ->update(['value' => '225778969396', 'updated_at' => now()]);
    }
};
