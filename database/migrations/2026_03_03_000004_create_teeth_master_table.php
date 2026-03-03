<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teeth_master', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tooth_number')->unsigned()->unique();
            $table->enum('quadrant', ['upper_right', 'upper_left', 'lower_left', 'lower_right']);
            $table->string('standard_name');
            $table->string('alternate_name')->nullable();
            $table->string('tooth_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teeth_master');
    }
};
