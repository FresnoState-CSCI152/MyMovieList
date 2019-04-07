<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FriendRequestUnique;
use App;
use App\Rules\FriendRequestExists;
use App\Rules\FriendshipExists;
use Validator;

class FriendsController extends Controller
{
	public function createFriendRequest()
	{
        $this->validate(request(), [
            'name' => [
                'bail',
                'exists:users',
                new FriendRequestUnique,
                function ($attribute, $value, $fail) {
                    if ($value == Auth::user()->name) {
                        $fail("You can't send a friend request to yourself!");
                    }
                },
            ]
        ]);

        $sender = Auth::user();
        $receiverId = App\User::where('name', '=', request('name'))->get()[0]->id;

        // Create a new friend request
        $sender->friendRequestsSent()->attach($receiverId);

        request()->session()->flash("requestSuccess", "You have successfully made the friend request.");
        return redirect()->back();
    }

    public function cancelFriendRequest()
    {
        return $this->deleteFriendRequest("sender");
    }

    public function declineFriendRequest()
    {
        return $this->deleteFriendRequest("receiver");
    }

    public function createFriendship()
    {
        $this->validate(request(), [
            'id' => new FriendRequestExists("receiver"),
        ]);

        $receiver = Auth::user();
        $sender = App\User::find((int)request('id'));

        $sender->friends()->attach($receiver->id);
        $receiver->friends()->attach($sender->id);
        $sender->friendRequestsSent()->detach($receiver->id);

        request()->session()->flash("friendSuccess", "You are now friends.");

        return redirect()->route('friends');
    }

    public function deleteFriendRequest(String $userType)
    {
        if ($userType == "receiver") {
            $name = "id";
        }
        else if ($userType == "sender") {
            $name = "receiver_id";
        }
        $this->validate(request(), [
            $name => new FriendRequestExists($userType),
        ]);

        if ($userType == "receiver") {
            $sender = App\User::find((int)request($name));
            $receiver = Auth::user();
        }
        else if ($userType == "sender") {
            $sender = Auth::user();
            $receiver = App\User::find((int)request($name));
        }

        $sender->friendRequestsSent()->detach($receiver->id);

        if ($userType == "receiver" ) {
            request()->session()->flash("declineSuccess", "You have successfully declined the friend request.");
        }
        else if ($userType == "sender") {
            request()->session()->flash("cancelSuccess", "You have successfully canceled the friend request.");
        }

        return redirect()->route('friends');
    }

    public function deleteFriendship(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'toDeleteId' => new FriendshipExists,
        ]);

        if ($validatedData->fails()) {
            $viewFailure = view("flash-messages/alert-ajax")
                               ->with("failureMessage", "You are not friends with this user.")
                               ->render();
            return response()->json([
                "html" => $viewFailure,
                "success" => false,
            ]);
        }

        $deleter = Auth::user();
        $deletee = App\User::find(request('toDeleteId'));

        $deleter->friends()->detach($deletee->id);
        $deletee->friends()->detach($deleter->id);

        return response()->json(["success" => true]);
        //return redirect()->route('friends');
    }

	// Return a page which shows a list of the logged in user's friends.
	public function friends()
	{
		$user = Auth::user();
        $userFriends = $user->friends()->get();
        $userFriendRequestsReceived = $user->friendRequestsReceived()->get();
        $userFriendRequestsSent = $user->friendRequestsSent()->get();

		return view('friends', compact('userFriends', 'userFriendRequestsReceived', 'userFriendRequestsSent'));
    }
}
