<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'place_directions');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            table: 'place_directions',
            callback: function (Blueprint $table): void {
                $table->id(column: 'id')->primary()->unique();
                $table->foreignId(column: 'address_id')
                    ->nullable()
                    ->constrained(
                        table: 'addresses',
                        column: 'id',
                    )
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->string(column: 'place_id')->fulltext();
                $table->string(column: 'description')->fulltext();
                $table->string(column:'main_text')->fulltext();
                $table->string(column:'secondary_text')->fulltext();
                $table->timestamps();
            },
        );
    }
};
