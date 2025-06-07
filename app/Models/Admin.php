<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin'; // Guard name for admin authentication

    protected $fillable = [
        'name', 'email', 'password',
        // Add 'name' to the fillable fields for mass assignment
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Additional admin-specific logic, relationships, etc.
}
