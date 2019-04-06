require('jquery')

$(document).ready(function() {
    $('#genre-select-menu_reviews').on('change', function(event) {
        let userId = Number($('#reviews').attr('user-id'));
        let genre = $('#genre-select-menu_reviews').val();
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
                    }
                },
                error: function (errorData) {
                    console.log(errorData);
                },
                dataType: "html",
        });
    });

    $('#genre-select-menu_recommends').on('change', function(event) {
        let userId = Number($('#recommends').attr('user-id'));
        let genre = $('#genre-select-menu_recommends').val();
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
                    }
                },
                error: function (errorData) {
                    console.log(errorData);
                },
                dataType: "html",
        });
    });
});
