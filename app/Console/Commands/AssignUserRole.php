<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AssignUserRole extends Command
{
    protected $signature = 'user:assign-role {userId} {role}';
    protected $description = 'Assign a role to a user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('userId');
        $role = $this->argument('role');

        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        $user->assignRole($role);
        $this->info("Role '{$role}' has been assigned to user with ID {$userId}.");
    }
}
