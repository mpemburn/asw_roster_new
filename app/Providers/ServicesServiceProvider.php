<?php
namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use App\Services\AuditService;
use App\Services\GuildService;
use App\Services\RolesService;
use App\Services\MembershipService;
use App\Services\RosterAuthService;
use App\Services\RbacService;

/**
 * Register our Repository with Laravel
 */
class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Registers Interfaces and Classes with Laravel's IoC Container
     *
     */
    public function register()
    {
        App::bind('AuditService', function()
        {
            return new AuditService();
        });

        App::bind('GuildService', function()
        {
            return new GuildService();
        });

        App::bind('MembershipService', function()
        {
            return new MembershipService();
        });

        App::bind('RolesService', function()
        {
            return new RolesService();
        });

        App::bind('RosterAuthService', function()
        {
            return new RosterAuthService();
        });

        App::bind('RbacService', function()
        {
            return new RbacService();
        });

    }
}