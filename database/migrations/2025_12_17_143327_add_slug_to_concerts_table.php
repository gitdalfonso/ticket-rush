<?php

use App\Models\Concert;
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
        Schema::table('concerts', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Rellenar los slugs de los datos existentes
        Concert::all()->each(function ($concert) {
            $concert->slug = \Illuminate\Support\Str::slug($concert->title) . '-' . $concert->id;
            $concert->save();
        });

        // Ahora que están llenos, lo hacemos único y requerido
        Schema::table('concerts', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concerts', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
