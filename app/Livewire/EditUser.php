<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class EditUser extends Component
{
    public User $user;
    public int $admin;
    public function mount(User $user)
    {
        $this->user = $user;
        $this->admin = $user->is_admin ? 1 : 0;
    }

    public function updatedAdmin($value)
    {
        $this->user->is_admin = ($value == "1");
        $this->user->save();
    }
    public function render()
    {
        return view('livewire.edit-user');
    }
}
