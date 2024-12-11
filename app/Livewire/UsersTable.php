<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    #[URL(history:true)]
    public $perPage = 10;

    #[URL(history:true)]
    public $search = '';

    #[URL(history:true)]
    public $admin = '';

    #[URL(history:true)]
    public $sortBy = 'name';

    #[URL(history:true)]
    public $sortDirection = 'DESC';

    /**
     * Delete user
     * Passes the User model through
     * @param User $user
     */
    public function delete(User $user)
    {
        $user->delete();
    }

    /**
     * Lifecycle hook to reset search.
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     *  Sort Function
     *  If already Ascending, then flips it to Descending and vice versa
     *
     */
    public function setSortBy($sortByField)
    {
        if($this->sortBy === $sortByField)
        {
            // Flips direction of sorting
            $this->sortDirection = ($this->sortDirection == "ASC") ? "DESC" : "ASC";
            return;
        }
    }

    /**
     * Render function, renders component
     *
     */
    public function render()
    {
        return view('livewire.users-table',
        [
            // Allows scopeSearch from User Model, using the Search box, along with pagination.
            'users' => User::search($this->search)
            ->when($this->admin !== '', function($query){
                $query->where('is_admin', $this->admin);
            })
            ->paginate($this->perPage)
        ]
        );
    }
}
