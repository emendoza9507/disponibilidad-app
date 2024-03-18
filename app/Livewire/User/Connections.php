<?php

namespace App\Livewire\User;

use App\Models\Connection;
use App\Models\ConnectionRoleUser;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function Laravel\Prompts\alert;

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
    public $user_connections_collection;

    public $connection;

    public $role;

    public $role_to_remove;

    #[On('role.created')]
    public function mount()
    {
        $this->getConnections();
        $this->getRoles();
        $this->getUserConnections();

        $this->connection = $this->connections[0]->id;
        $this->role = $this->roles[0]->id;
    }


    public function render()
    {
        return view('livewire.user.connections');
    }

    public function assignRoleToTaller()
    {

        if(ConnectionRoleUser::where(['connection_id' => $this->connection, 'user_id' => $this->user->id, 'rol_id' => $this->role])->count() == 0) {
            ConnectionRoleUser::create([
                'connection_id' => $this->connection,
                'user_id' => $this->user->id,
                'rol_id' => $this->role
            ]);

            $this->dispatch('role.created');
        }
    }

    public function dettachRoleOfTaller($role_id) {
        ConnectionRoleUser::find($role_id)->delete();

        $this->dispatch('role.created');
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
                $fixed[$key] = ['connection' => $connectionRoleUser->connection, 'roles' => [], 'id' => $connectionRoleUser->id];
            }

            $fixed[$key]['roles'][] = $connectionRoleUser->role;
        }

        $this->user_connections_collection = $connectionRoleUsers;
        $this->user_connections = $fixed;
    }
}
