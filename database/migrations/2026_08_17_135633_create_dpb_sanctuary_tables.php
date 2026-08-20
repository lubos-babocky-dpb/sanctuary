<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            table: 'dpb_sanctuary_model_ghost',
            callback: function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('uuid')->unique();
                $table->timestamps();
            }
        );

        Schema::create(
            table: 'dpb_sanctuary_model_ghostsession',
            callback: function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->foreignId('ghost_id')
                    ->constrained('dpb_sanctuary_model_ghost')
                    ->cascadeOnDelete();

                $table->unsignedBigInteger('authenticatable_id');
                $table->string('authenticatable_type');

                $table->unsignedBigInteger('token_id')->nullable();

                $table->timestamp('logged_in_at');
                $table->timestamp('logged_out_at')->nullable();

                $table->index(
                    ['authenticatable_type', 'authenticatable_id'],
                    'ghostsession_authenticatable_index'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(table: 'dpb_sanctuary_model_ghostsession');
        Schema::dropIfExists(table: 'dpb_sanctuary_model_ghost');
    }
};