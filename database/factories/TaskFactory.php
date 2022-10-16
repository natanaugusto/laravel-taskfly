<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
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
            'status' => $this->faker->randomElement(array:Status::cases()),
        ];
    }
}
