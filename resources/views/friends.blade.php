@extends ("templates/master")

@section ("content")

<div class ="container">
    <div class="row">
        <div class="col-sm-8"> 
           <h1>Add a new friend</h1>

           <hr>
           <form class="form-inline" method="POST" action="/friends/createrequest">

            {{ csrf_field() }}

            <label class="sr-only" for="name">Name</label>
            <input type="text" class="form-control mb-2 mr-sm-2" id="name" name="name" placeholder="Name" required>

            <button type="submit" class="btn btn-primary mb-2">Send Friend Request</button>

            @include ("errors/fielderrors", ["fieldName" => "name"])
            @include ("flash-messages/success", ["successVar" => "requestSuccess"])
    
            </form>
    
            <hr>
            @if (count($userFriendRequestsReceived) > 0)
            <h3>You have received friend requests from the following users:</h3>
    
            <form class="form-inline" id="friendRequest" method="POST" action="/friends/create">
    
                {{ csrf_field() }}
    
                <label class="sr-only" for="requester_name">Name</label>
                <select class="custom-select mb-2 mr-sm-2" id="requester_name" name="id" required>
                    @foreach ($userFriendRequestsReceived as $request)
                    <option value="{{ $request->id }}">{{ $request->name }}</option>
                    @endforeach
                </select>
    
                <button type="submit" class="btn btn-primary mb-2">Accept Friend Request</button>
                <button type="submit" class="btn btn-primary mb-2 ml-2" onclick="changeToDecline()">Decline Friend Request</button>
    
            @include ("errors/fielderrors", ["fieldName" => "id"])
            @include ("flash-messages/success", ["successVar" => "friendSuccess"])
            @include ("flash-messages/success", ["successVar" => "declineSuccess"])
    
            </form>
            <script type="text/javascript">
                function changeToDecline() {
                    var friendRequestForm = document.getElementById("friendRequest");
                    if (friendRequestForm.hasAttribute("action")) {
                        friendRequestForm.setAttribute("action", "/friends/declinerequest");
                    }
                }
            </script>
            @else
            <h3>You have not received any friend requests</h3>
            @endif
    
            <hr>
            @if (count($userFriendRequestsSent) > 0)
            <h3>You have sent friend requests to the following users:</h3>
    
            <form class="form-inline" id="friendRequestSend" method="POST" action="/friends/cancelrequest">
    
                {{ csrf_field() }}
    
                <label class="sr-only" for="receiver_name">Name</label>
                <select class="custom-select mb-2 mr-sm-2" id="receiver_name" name="receiver_id" required>
                    @foreach ($userFriendRequestsSent as $request)
                    <option value="{{ $request->id }}">{{ $request->name }}</option>
                    @endforeach
                </select>
    
                <button type="submit" class="btn btn-primary mb-2">Cancel Friend Request</button>
    
            @include ("errors/fielderrors", ["fieldName" => "receiver_id"])
            @include ("flash-messages/success", ["successVar" => "cancelSuccess"])
    
            </form>
            @else
            <h3>You have not sent out any friend requests</h3>
            @endif
    
            <hr>
    
            @if (count($userFriends) > 0)
            <h3>Friends List:</h3>
    
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($userFriends as $friend)
    
    
    
                            <div class="d-inline-flex" id="{{ $friend->id }}">
                                <img class="ml" src="/uploads/avatars/{{ $friend->avatar }}" style="width:36px; height:36px; position:relative; right:8px; border-radius:50%">
                                    <div class="btn-group dropright">
                                      <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ $friend->name }}
                                      </button>
                                      <div class="dropdown-menu shadow">
                                        <!-- Dropdown menu links -->
                                        <a class="dropdown-item" href="/friends/{{ $friend->id }}">Movie Reviews</a>
                                        <button class="dropdown-item" type="button" onclick="deleteFriend({{ $friend->id }})">Remove</button>
                                      </div>
                                    </div>
                                <div id="delete-message-{{ $friend->id }}"></div>
                            </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
    
    
    
            <script type="text/javascript">
                function deleteFriend(friendId) {
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                        }
                    });
                    $.ajax({type: "POST",
                        url: "/friends/delete",
                        data: { "toDeleteId": friendId },
                        success: function (data) {
                            if (data["success"]) {
                                $("#" + friendId).remove();
                                console.log(data["success"]);
                            }
                            else {
                                $("#delete-message-" + friendId).append(data["html"]);
                                console.log(data["success"]);
                            }
                        },
                        error: function (errorData) {
                            console.log(errorData);
                        },
                        dataType: "json",
                    });
                }
            </script>
    
            @else
            <h3>You currently have no friends. 😞</h3>
            <p>Check out the discussion board for some potential new buddies.</p>
            @endif
        </div>
    </div>
</div>

@endsection
