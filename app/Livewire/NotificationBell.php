<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth; // ⬅️ penting

class NotificationBell extends Component
{
    public int $unread = 0;
    public $items = [];

    public function mount(): void
    {
        $this->reload();
    }

    public function reload(): void
    {
        $u = Auth::user();
        if (!$u) { $this->unread = 0; $this->items = []; return; }

        $this->unread = $u->unreadNotifications()->count();
        $this->items  = $u->notifications()->latest()->limit(10)->get();
    }

    public function markRead(string $id): void
    {
        if ($u = Auth::user()) {
            $n = $u->notifications()->whereKey($id)->first();
            if ($n && is_null($n->read_at)) $n->markAsRead();
            $this->reload();
        }
    }

    public function markAllRead(): void
    {
        if ($u = Auth::user()) {
            $u->unreadNotifications->markAsRead();
            $this->reload();
        }
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
