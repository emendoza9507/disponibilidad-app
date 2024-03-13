<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public $open = false;

    public $name;

    public $email;

    public $password;

    public $password_confirmation;

    public function render()
    {
        return view('livewire.user.create');
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|min:5',
            'email' => 'required|max:50|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::create($validated);

        $this->dispatch('user.created');
        $this->open = false;
    }
}
