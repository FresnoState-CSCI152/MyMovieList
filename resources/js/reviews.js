require('jquery')

$(document).ready(function() {
    // Filter review cards based on the specified genre
    $('#genre-select-menu-button_reviews').on('click', function(event) {
        let genre = $("#genre-select-menu_reviews").val();
        let userId = Number($('#reviews').attr('user-id'));
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });
        $.ajax({type: "GET",
                url: `/reviews/${userId}/${genre}`,
                success: function (reviewCardsHtml) {
                    if (reviewCardsHtml) {
                        $("#review-cards").empty();
                        $("#review-cards").append(reviewCardsHtml);
                        $("#genre-select-menu-button_reviews").prop("disabled", true);
                    }
                },
                error: function (errorData) {
                    console.log(errorData);
                },
                dataType: "html",
        });
    });

    // Filter recommend cards based on the specified genre
    $('#genre-select-menu-button_recommends').on('click', function(event) {
        let genre = $("#genre-select-menu_recommends").val();
        let userId = Number($('#recommends').attr('user-id'));
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });
        $.ajax({type: "GET",
                url: `/recommends/${userId}/${genre}`,
                success: function (recommendCardsHtml) {
                    if (recommendCardsHtml) {
                        $("#recommend-cards").empty();
                        $("#recommend-cards").append(recommendCardsHtml);
                        $("#genre-select-menu-button_recommends").prop("disabled", true);
                    }
                },
                error: function (errorData) {
                    console.log(errorData);
                },
                dataType: "html",
        });
    });
});

// Allow the user to filter reviews if they have selected a different genre.
$('#genre-select-menu_reviews').on('change', function(event) {
    $("#genre-select-menu-button_reviews").prop("disabled", false);
});

// Allow the user to filter recommends if they have selected a different genre.
$('#genre-select-menu_recommends').on('change', function(event) {
    $("#genre-select-menu-button_recommends").prop("disabled", false);
});
