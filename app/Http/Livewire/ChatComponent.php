<?php

namespace App\Http\Livewire;

use App\Models\Contact;
use Livewire\Component;

class ChatComponent extends Component
{
    public $search;

    public function getContactsProperty()
    {
        return Contact::where('user_id', auth()->id())
            ->when($this->search, function ($query) {

                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($query) {
                            $query->where('email', 'like', '%' . $this->search . '%');
                        });
                });
            })->get() ?? [];
    }
    public function render()
    {
        return view('livewire.chat-component')->layout('layouts.chat');
    }
}
