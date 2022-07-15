<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(table: 'tasks', callback: function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(model: User::class, column: 'owner_id');
            $table->string(column: 'title');
            $table->dateTime(column: 'due');
            $table->enum(column: 'status', allowed: ['todo', 'doing', 'done']);
            $table->timestamps();
            $table->index(columns: ['uuid', 'owner_id', 'title', 'due', 'status']);
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
