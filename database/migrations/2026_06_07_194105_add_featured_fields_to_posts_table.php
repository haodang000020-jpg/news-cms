<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'is_featured')) {
                $table->boolean('is_featured')
                    ->default(false)
                    ->after('status');
            }

            if (!Schema::hasColumn('posts', 'featured_order')) {
                $table->integer('featured_order')
                    ->default(0)
                    ->after('is_featured');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'featured_order')) {
                $table->dropColumn('featured_order');
            }

            if (Schema::hasColumn('posts', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};