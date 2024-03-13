<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;

class Delete extends Component
{

    public $open = false;

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.user.delete');
    }

    public function delete()
    {
        if(auth()->user()->getAuthIdentifier() !== $this->user->getAuthIdentifier()) {
            $this->user->delete();

            $this->dispatch('user.deleted');
            $this->open = false;
        }
    }
}
