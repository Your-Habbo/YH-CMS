<?php

namespace App\Http\Controllers;

use App\Http\Traits\PjaxTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use PjaxTrait;

    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return $this->view('notifications.index', compact('notifications'));
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        // Return a JSON response for PJAX requests
        if (request()->pjax()) {
            return response()->json(['success' => 'All notifications marked as read.']);
        }
        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark a specific notification as read.
     *
     * @param  int  $id
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        // Return a JSON response for PJAX requests
        if (request()->pjax()) {
            return response()->json(['success' => 'Notification marked as read.']);
        }
        return back()->with('success', 'Notification marked as read.');
    }
}
