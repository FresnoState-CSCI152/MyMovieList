require("jquery")

$(document).ready(function() {
    $("#private-chat-input").on("submit", function(event) {
        event.preventDefault();
        let senderName = $("#private-chat").attr("curr-user-name");
        let message = $("#private-chat-input-box").val();
        addMessageToBox(senderName, message);
        sendMessage(message);
        $("#private-chat-input-box").val("");
    })

    if ($("#private-chat").length > 0) {
        let senderId = $("#private-chat").attr("other-user-id");
        let receiverId = $("#private-chat").attr("curr-user-id");
        Echo.private(`message.${senderId}.${receiverId}`)
            .listen('MessageSent', function (e) {
                console.log("private message listener got called");
                addMessageToBox(e.senderName, e.message);
            });
    }

    function addMessageToBox(senderName, message) {
        let senderNameElem = $("<span></span>");
        senderNameElem.attr("id", "message-user-name");
        senderNameElem.text(senderName);
        let messageDiv = $('<div></div>');
        messageDiv.addClass("mt-1");
        messageDiv.text(`: ${message}`);
        messageDiv.prepend(senderNameElem);
        $("#private-chat-message-display").prepend(messageDiv);
    }

    function sendMessage(message) {
        let receiverId = $("#private-chat").attr("other-user-id");
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            },
            type: "POST",
            url: "/chat/private",
            data: {
                "receiverId": receiverId,
                "message": message,
            },
            success: function (data) {
                console.log("success:");
                console.log(data);
            },
            error: function (errorData) {
                console.log("error data:");
                console.log(errorData);
            },
            dataType: "json",
        });
    }
});