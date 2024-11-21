<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Eventos;
use Livewire\WithPagination;
use App\Exports\EventsExport;
use Maatwebsite\Excel\Facades\Excel;

class Event extends Component
{
    use WithPagination;

    public $search = ''; 
    public $selectedUserId = ''; 

    public function render()
    {
        $users = \App\Models\User::all();

        $events = Eventos::with(['user' => function ($query) {
            $query->withTrashed();
        }])
            ->when($this->search, function ($query) {
                return $query->where('identificador', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedUserId, function ($query) {
                return $query->where('user_id', $this->selectedUserId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.event', [
            'events' => $events,
            'users' => $users,
        ]);
    }

    public function exportToExcel()
    {
        return Excel::download(new EventsExport($this->search, $this->selectedUserId), 'events.xlsx');
    }
}
