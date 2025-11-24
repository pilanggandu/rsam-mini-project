<?php

use App\Models\Pasien;
use App\Models\User;
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
        Schema::create('reseps', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_resep')->unique();
            $table->foreignIdFor(Pasien::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'dokter_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'completed', 'processed'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
