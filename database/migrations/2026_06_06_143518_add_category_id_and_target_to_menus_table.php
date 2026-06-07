<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
{
    Schema::table('menus', function (Blueprint $table) {
        if (!Schema::hasColumn('menus', 'category_id')) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('parent_id')
                ->constrained('categories')
                ->nullOnDelete();
        }

        if (!Schema::hasColumn('menus', 'target')) {
            $table->string('target', 20)
                ->default('_self')
                ->after('url');
        }
    });
}

public function down(): void
{
    Schema::table('menus', function (Blueprint $table) {
        if (Schema::hasColumn('menus', 'category_id')) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        }

        if (Schema::hasColumn('menus', 'target')) {
            $table->dropColumn('target');
        }
    });
}
};
