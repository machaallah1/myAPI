<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'addresses');
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(
            table:'addresses',
            callback: function (Blueprint $table): void {
                $table->id(column: 'id')->primary()->unique();
                $table->string(column: 'place_id')
                    ->nullable()
                    ->fulltext();
                $table->string(column: 'place_name')
                    ->nullable()
                    ->fulltext();
                $table->string(column: 'formated_address')->nullable();
                $table->decimal(column: 'longitude')
                    ->nullable();
                $table->decimal(column: 'latitude')
                    ->nullable();
                $table->string(column: 'street_name')
                    ->nullable()
                    ->fulltext();
                $table->foreignId(column: 'user_id')
                    ->nullable()
                    ->constrained(
                        table: 'users',
                        column: 'id',
                    )
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->timestamps();
            },
        );
    }
};
