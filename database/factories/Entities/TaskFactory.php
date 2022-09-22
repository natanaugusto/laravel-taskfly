<?php

namespace Database\Factories\Entities;

use App\Entities\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Entities\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'title' => $this->faker->name,
            'due' => Carbon::now()->addMinute(10)->format(Task::DUE_DATETIME_FORMAT),
            'status' => $this->faker->randomElement(['todo', 'doing', 'done']),
        ];
    }
}
