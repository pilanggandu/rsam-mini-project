<?php

use App\Models\Obat;
use App\Models\Resep;
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
        Schema::create('resep_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Resep::class)->constrained('reseps')->cascadeOnDelete();
            $table->foreignIdFor(Obat::class)->constrained('obats')->restrictOnDelete();
            $table->unsignedInteger('jumlah');
            $table->string('dosis'); // contoh: "3x1", "2x1 sesudah makan"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resep_details');
    }
};
