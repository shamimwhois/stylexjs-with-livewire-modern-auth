<?php

namespace App\Livewire\Pages\Admin\UserList;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('User management')]
class Index extends Component
{
    use WithPagination;

    /** @var string */
    public $search = '';

    /**
     * Reset pagination when the search term changes.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Delete the given user, guarding self-deletion.
     */
    public function delete(int $userId): void
    {
        $user = User::findOrFail($userId);

        Gate::allowIf($user->getKey() !== auth()->id(), 'You cannot delete your own account.');

        $user->delete();

        session()->flash('status', "\"{$user->name}\" was deleted.");

        $this->redirect(self::class);
    }

    /**
     * Temporarily suspend the given user, ending their active sessions.
     */
    public function suspend(int $userId): void
    {
        $user = User::findOrFail($userId);

        Gate::allowIf($user->getKey() !== auth()->id(), 'You cannot suspend your own account.');

        $user->suspend();

        $this->clearActiveSessions($user);

        session()->flash('status', "\"{$user->name}\" was suspended.");

        $this->redirect(self::class);
    }

    /**
     * Permanently block the given user, ending their active sessions.
     */
    public function block(int $userId): void
    {
        $user = User::findOrFail($userId);

        Gate::allowIf($user->getKey() !== auth()->id(), 'You cannot block your own account.');

        $user->block();

        $this->clearActiveSessions($user);

        session()->flash('status', "\"{$user->name}\" was blocked.");

        $this->redirect(self::class);
    }

    /**
     * Restore the given user to a fully active account.
     */
    public function restore(int $userId): void
    {
        $user = User::findOrFail($userId);

        Gate::allowIf($user->getKey() !== auth()->id(), 'You cannot restore your own account.');

        $user->restoreAccount();

        session()->flash('status', "\"{$user->name}\" can sign in again.");

        $this->redirect(self::class);
    }

    /**
     * Delete all database sessions belonging to the given user.
     */
    private function clearActiveSessions(User $user): void
    {
        DB::table('sessions')->where('user_id', $user->getKey())->delete();
    }

    /**
     * Render the user list.
     */
    public function render()
    {
        $users = User::query()
            ->with('roles.permissions')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('username', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.admin.userlist.index', [
            'users' => $users,
        ]);
    }
}
