<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->index();
            $table->date('attendance_date')->index();
            $table->string('user_type', 20)->index();
            $table->unsignedBigInteger('entity_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('status', 20)->default('present');
            $table->text('remark')->nullable();
            $table->unsignedBigInteger('marked_by')->nullable()->index();
            $table->timestamps();

            $table->unique(['client_id', 'attendance_date', 'user_type', 'entity_id'], 'attendances_unique_entry');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
