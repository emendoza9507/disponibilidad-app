<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Update extends Component
{
    public $open = false;

    public $name;

    public $email;

    public $password;

    public $password_confirmation;

    public $user_roles = [];

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;

        $this->name = $user->name;
        $this->email = $user->email;

        foreach ($this->user->roles as $rol) {
            $this->user_roles[] = $rol->id;
        }
    }

    public function render()
    {

        $roles = Role::all();

        return view('livewire.user.update', compact('roles'));
    }

    public function update()
    {
        $rules = [
            'name' => 'required|min:5',
            'email' => 'required|max:50|unique:users,email,'.$this->user->id
        ];

        if($this->password != '') {
            $rules['password'] = 'required|confirmed';
        }

        $validated = $this->validate($rules);

        if($this->password != '') {
            $validated['password'] = Hash::make($validated['password']);
        }

        $this->user->fill($validated);
        $this->user->save();

        $roles = Role::whereIn('id', array_values($this->user_roles))->get();
        $this->user->syncRoles($roles);

        $this->dispatch('user.updated');
        $this->open = false;
    }
}
