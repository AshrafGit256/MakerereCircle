<?php

namespace App\Livewire;

use App\Models\ClassNotification;
use Livewire\Component;

class StudentNotifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = ClassNotification::where('user_id', auth()->id())
            ->with(['timetable.courseUnit'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->unreadCount = $this->notifications->filter(function($notification) {
            return !$notification->is_read;
        })->count();
    }

    public function markAsRead($notificationId)
    {
        $notification = ClassNotification::find($notificationId);
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->update(['is_read' => true]);
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        ClassNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $this->loadNotifications();
    }

    public function deleteNotification($notificationId)
    {
        $notification = ClassNotification::find($notificationId);
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->delete();
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.student-notifications');
    }
}
