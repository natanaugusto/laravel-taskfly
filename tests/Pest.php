<?php

use App\Mail\ModelChanges;
use App\Models\User;
use App\Notifications\ModelEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
 */

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
 */

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
 */

function something()
{
    // ..
}

function createLivewireComponentInstance(string $name, array $params = []): array
{
    $component = Livewire::test($name, $params);
    $component->assertHasNoErrors();
    $instance = $component->instance();
    expect(value:$instance)->toBeInstanceOf(class :$name);

    return compact(var_name:['component', 'instance']);
}

function assertModelEvent(ModelEvent $notification, User $user, Model $model)
{
    expect(value:$notification->event->getModel()->id)->toBe(expected:$model->id);
    $mail = $notification->toMail(notifiable:$user);
    expect(value:$mail)->toBeInstanceOf(class:ModelChanges::class);
    expect(value:$mail->markdownView)->toBe(expected:$notification->event->markdownView);
}
