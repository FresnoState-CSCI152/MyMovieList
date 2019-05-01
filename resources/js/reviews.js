require('jquery')

$(document).ready(function() {
    // Filter review cards based on the specified genre
    $('#filter-and-sort-button_reviews').on('click', function(event) {
        let genre = $("#genre-select-menu_reviews").val();
        let userId = Number($('#pills-reviews').attr('user-id'));
        let sortUserScore = $("#sort-user-score_reviews").val();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });
        $.ajax({type: "GET",
                url: "/reviews",
                data: {
                    "userId": userId,
                    "genre": genre,
                    "sortUserScore": sortUserScore,
                },
                success: function (reviewCardsHtml) {
                    if (reviewCardsHtml) {
                        $("#review-cards").empty();
                        $("#review-cards").append(reviewCardsHtml);
                        $("#filter-and-sort-button_reviews").prop("disabled", true);
                    }
                },
                error: function (errorData) {
                    console.log(errorData);
                },
                dataType: "html",
        });
    });

    // Filter recommend cards based on the specified genre
    $('#filter-and-sort-button_recommends').on('click', function(event) {
        let genre = $("#genre-select-menu_recommends").val();
        let userId = Number($('#pills-recommends').attr('user-id'));
        let sortCreationDate = $("#sort-creation-date_recommends").val();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });
        $.ajax({type: "GET",
                url: "/recommends",
                data: {
                    "userId": userId,
                    "genre": genre,
                    "sortCreationDate": sortCreationDate,
                },
                success: function (recommendCardsHtml) {
                    if (recommendCardsHtml) {
                        $("#recommend-cards").empty();
                        $("#recommend-cards").append(recommendCardsHtml);
                        $("#filter-and-sort-button_recommends").prop("disabled", true);
                    }
                },
                error: function (errorData) {
                    console.log(errorData);
                },
                dataType: "html",
        });
    });

    // Allow the user to filter reviews if they have selected a different genre.
    $('#genre-select-menu_reviews, #sort-user-score_reviews').on('change', function(event) {
        $("#filter-and-sort-button_reviews").prop("disabled", false);
    });
    
    // Allow the user to filter recommends if they have selected a different genre.
    $('#genre-select-menu_recommends, #sort-creation-date_recommends').on('change', function(event) {
        $("#filter-and-sort-button_recommends").prop("disabled", false);
    });
});
