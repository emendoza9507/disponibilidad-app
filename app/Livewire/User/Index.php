<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[On('user.created'), On('user.deleted'), On('user.updated')]
    public function render()
    {
        $users = User::paginate();

        return view('livewire.user.index', compact('users'));
    }
}
