<?php

namespace Josefo727\FilamentGeneralSettings\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Context;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Josefo727\FilamentGeneralSettings\Tests\Database\Factories\UserFactory;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;

    protected $table = 'users';
    protected $guarded = [];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public function canAccessFilament(Context $context): bool
    {
        return true;
    }
}
