<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function userHasFriendRequests() {
        $currUserFriendRequests = Auth::user()->friendRequestsReceived()->get();
        $hasFriendRequests = $currUserFriendRequests->isNotEmpty();
        return response()->json(compact('hasFriendRequests'));
    }
}
