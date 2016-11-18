$(function() {
    // Rate contacts
    $("#list-contacts").on("click", "#rating button", function() {
        var container = $(this).closest("#rating");
        $.ajax({
            url: "/contact/rate",
            type: "post",
            data: {
                id: $(this).attr("data-id"),
                value: $(this).attr("data-value")
            },
            success: function(data) {
                container.html(data);
            }
        });
    });
    
    // Show all contact ratings
    $("#list-contacts").on("click", "#all", function() {
        $.ajax({
            url: "/contact/popup-ratings",
            type: "post",
            data: {
                id: $(this).attr("data-id"),
                value: $(this).attr("data-value")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
            }
        });
        return false;
    });
    
    // Close all ratings popup
    $("body").on("click", "#ratings .close", function() {
        $(this).closest("#ratings").remove();
    });
    
    // Show delete recommendation confirm
    $("#list-contacts").on("click", ".admin-recommendation-delete", function() {
        $.ajax({
            url: "/admin/contact/show-confirm-recommendation-delete",
            type: "post",
            data: {
                id: $(this).attr("data-id")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
            }
        });
    });
    
    // Close delete recommendation confirm
    $("body").on("click", "#confirm-delete-recommendation .close, #confirm-delete-recommendation .btn-default", function() {
        $("#confirm-delete-recommendation").remove();
    });
    
    // Delete recommendation
    $("body").on("submit", "#confirm-delete-recommendation form", function() {
        $.ajax({
            url: "/admin/contact/recommendation-delete",
            type: "post",
            data: $(this).serialize(),
            success: function() {
                window.location.reload();
            }
        });
        return false;
    });
    
    // Show recommendation edit popup
    $("#list-contacts").on("click", ".admin-recommendation-edit", function() {
        $.ajax({
            url: "/admin/contact/show-recommendation-edit",
            type: "post",
            data: {
                id: $(this).attr("data-id")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
            }
        });
    });
    
    // Close recommendation edit popup
    $("body").on("click", "#popup-recommendation-edit .close", function() {
        $("#popup-recommendation-edit").remove();
    });
    
    // Submit recommendation edit popup
    $("body").on("submit", "#popup-recommendation-edit form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/admin/contact/submit-recommendation-edit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-recommendation-edit").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#popup-recommendation-edit")))
                    window.location.reload();
            }
        });
        return false;
    });
    
    // Clear errors on focus on input on recommendation edit popup
    $("body").on("focus", "#popup-recommendation-edit input, #popup-recommendation-edit textarea", function() {
        $(this).closest(".form-group").removeClass("has-error");
        $(this).closest(".form-group").find(".errors").remove();
    });
});