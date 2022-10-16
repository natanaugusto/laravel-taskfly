<?php

use App\Models\User;
use App\Enums\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(table: 'tasks', callback: static function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(model: User::class, column: 'creator_id');
            $table->string(column: 'shortcode', length: 10)->unique();
            $table->string(column: 'title');
            $table->dateTime(column: 'due')->default(DB::raw(value: 'CURRENT_TIMESTAMP'));
            $table->enum(
                column: 'status',
                allowed: array_map(
                    callback: static fn ($enum) => $enum->value,
                    array: Status::cases()
                )
            )->default(Status::DEFAULT->value);
            $table->timestamps();
            $table->softDeletes();
            $table->index(columns: ['uuid', 'shortcode', 'creator_id', 'title', 'due', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'tasks');
    }
};
