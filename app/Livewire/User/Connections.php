<?php

namespace App\Livewire\User;

use App\Models\Connection;
use App\Models\ConnectionRoleUser;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Connections extends Component
{

    public function __construct()
    {

    }

    public $open = false;

    public User $user;

    public $connections;

    public $roles;

    public $user_connections;

    public function mount()
    {
        $this->getConnections();
        $this->getRoles();
        $this->getUserConnections();
    }

    public function render()
    {
        return view('livewire.user.connections');
    }

    public function getConnections()
    {
        $this->connections = Connection::all();
    }

    public function getRoles()
    {
        $this->roles = Role::all();
    }

    public function getUserConnections()
    {
        $connectionRoleUsers = ConnectionRoleUser::where('user_id', $this->user->id)->get();
        $fixed = [];

        foreach ($connectionRoleUsers as $connectionRoleUser) {
            $key = $connectionRoleUser->connection_id;
            if(!isset($fixed[$key])) {
                $fixed[$key] = ['connection' => $connectionRoleUser->connection, 'roles' => []];
            }

            $fixed[$key]['roles'][] = $connectionRoleUser->role;
        }

        $this->user_connections = $fixed;
    }
}
