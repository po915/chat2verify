<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('admin', __('Team Manager'), [
            'create',
            'read',
            'update',
            'delete',
            'read_messages',
            'send_messages',
            'purchase_numbers',
            'archive_conversations',
        ])->description(__('Administrator users can perform any action.'));

        Jetstream::role('manager', __('Support Manager'), [
            'create',
            'read',
            'read_messages',
            'send_messages',
            'purchase_numbers',
            'archive_conversations',
        ])->description(__('Support managers have the ability to read and send messages as well as purchase additional phone numbers.'));

        Jetstream::role('agent', __('Support Agent'), [
            'archive_conversations',
            'create',
            'read',
            'read_messages',
            'send_messages',
        ])->description(__('Support agents have the ability to read and send messages but cannot purchase new numbers.'));
    }
}
