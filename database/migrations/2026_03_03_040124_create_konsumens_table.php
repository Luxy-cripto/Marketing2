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
                Schema::create('konsumens', function (Blueprint $table) {

                        $table->id();

                        $table->string('nama');

                        $table->string('no_hp')->nullable();

                        $table->string('email')->nullable();

                        $table->text('alamat')->nullable();

                        $table->string('sumber_lead')->nullable();

                        $table->enum('status', [
                                'Prospek',
                                'Deal',
                                'Tidak Tertarik'
                        ])->default('Prospek');

                        $table->foreignId('user_id')
                                ->constrained()
                                ->onDelete('cascade');

                        $table->timestamps();

                });
        }

        /**
         * Reverse the migrations.
         */

        public function down(): void
        {
                Schema::dropIfExists('konsumens');
        }
};
