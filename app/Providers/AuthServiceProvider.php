<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Policies\RolePolicy;
use App\Models\User;
use App\Policies\UsuarioPolicy;
use Spatie\Permission\Models\Permission;
use App\Policies\ActivityPolicy;
use Spatie\Activitylog\Models\Activity;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
            // 'App\Models\Model' => 'App\Policies\ModelPolicy',
            Role::class => RolePolicy::class,
            User::class => UsuarioPolicy::class,
            Permission::class => RolePolicy::class,
            Activity::class => ActivityPolicy::class,
             
            
    ];

    public function boot(): void
    {
        Auth::provider('multi-ldap', function ($app, array $config) {
            return new MultiLdapUserProvider();
        });
    }
}