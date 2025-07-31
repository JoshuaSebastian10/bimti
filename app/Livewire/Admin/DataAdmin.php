<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DataAdmin extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap'; 

    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $adminQuery = User::role('admin');

        if ($this->search) {
            $adminQuery->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
        }

        $admin = $adminQuery->latest()->paginate(10);

        return view('livewire.admin.data-admin', [
            'admin' => $admin,
        ]);
    }
    
    public function deleteAdmin($id)
    {
        try {
     
            $admin = User::findOrFail($id);
            $admin->delete();

            session()->flash('successdelete', 'Data admin berhasil dihapus.');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data.');
        }
    }
}
