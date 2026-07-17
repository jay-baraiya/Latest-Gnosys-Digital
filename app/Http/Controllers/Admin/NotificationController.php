<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\SystemNotification;

class NotificationController extends Controller
{
    public function dropdown()
    {
        return view('admin.components.notification-dropdown');
    }

    public function markRead(Request $request, $id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function read($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            
            if (isset($notification->data['file_path'])) {
                $filePath = $notification->data['file_path'];
                if (Storage::disk('public')->exists($filePath)) {
                    return Storage::disk('public')->download($filePath);
                }
            }
        }
        
        abort(404, 'Notification or file not found.');
    }

    public function testNotification(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->notify(new SystemNotification([
                'title' => 'Test Notification',
                'message' => 'This is a test notification from Laravel Reverb!',
                'type' => 'test',
                'time' => now()->toDateTimeString(),
            ]));
            return response()->json(['success' => true, 'message' => 'Test notification dispatched successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
    }
}
