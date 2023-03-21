<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"name","email","password"},
 *
 *     @OA\Property(property="id",type="integer"),
 *     @OA\Property(property="name",type="string"),
 *     @OA\Property(property="email_verified_at",type="string"),
 *     @OA\Property(property="password",type="string"),
 *     @OA\Property(property="remember_token",type="string"),
 *     @OA\Property(property="created_at",type="string"),
 *     @OA\Property(property="updated_at",type="string"),
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(related: Task::class);
    }
}
