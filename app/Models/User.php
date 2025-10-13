<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Service\Enums\UserPositionEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'position',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'position' => UserPositionEnum::class,
        ];
    }

    public function tasks(): hasMany
    {
        return $this->hasMany(Task::class);
    }

    public function comments(): hasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    public function notifications(): hasMany
    {
        return $this->hasMany(TaskNotification::class);
    }

    public function scopePosition(Builder $builder, UserPositionEnum $position)
    {
        return $builder->where('position', '=', $position->value);
    }

    public function scopeManager(Builder $builder)
    {
        return $builder->position(UserPositionEnum::MANAGER);
    }
}
