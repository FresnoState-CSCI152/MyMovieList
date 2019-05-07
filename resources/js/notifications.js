require("jquery")

$(document).ready(function() {
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