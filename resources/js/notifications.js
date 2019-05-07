require("jquery")

$(document).ready(function() {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
        },
        type: "GET",
        url: "/notifications/friend-requests",
        success: function (data) {
            // console.log("success:");
            // console.log(data);
            if (data["hasFriendRequests"]) {
                recolorFriendsLink();
            }
        },
        error: function (errorData) {
            console.log("error data:");
            console.log(errorData);
        },
        dataType: "json",
    });

    
    Echo.private(`friend-request.${$("meta[name='curr-user-id']").attr("content")}`)
    .listen("FriendRequestSent", function (e) {
        console.log("private friend request listener got called");
        recolorFriendsLink();
    });

    function recolorFriendsLink() {
        friendsLink = $("#topnav-friends-link");
        friendsLink.addClass("text-info");
    }
});