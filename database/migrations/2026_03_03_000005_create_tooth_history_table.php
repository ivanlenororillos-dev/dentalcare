<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tooth_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('tooth_number')->unsigned();
            $table->string('procedure_type');
            $table->enum('status', [
                'healthy', 'cavity', 'filled', 'crowned',
                'extracted', 'root_canal', 'implant',
            ]);
            $table->string('surface')->nullable();
            $table->text('detailed_notes')->nullable();
            $table->foreignId('dentist_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date_of_procedure');
            $table->timestamps();

            $table->index(['client_id', 'tooth_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tooth_history');
    }
};
