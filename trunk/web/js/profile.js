$(function() {
    // Create code button
    $("body").on("click", "#codes #create-code", function() {
        $.ajax({
            url: "/profile/generate-code",
            type: "post",
            error: function(hdr) {
                alert(hdr.responseText);
            },
            success: function(data) {
                alert(data);
                $.ajax({
                    url: "/profile/list-codes",
                    type: "post",
                    data: {page: 1000000},
                    success: function(data) {
                        $("#codes").html(data);
                    }
                });
            }
        });
    });
    
    // Process pagination
    $("body").on("click", "#codes .pagination a", function() {
        $.ajax({
            url: "/profile/list-codes",
            type: "post",
            data: {page: $(this).attr("data-page")},
            success: function(data) {
                $("#codes").html(data);
            }
        });
        return false;
    });
    
    // Delete code buttons
    $("body").on("click", "#codes .delete-code", function() {
        $.ajax({
            url: "/profile/delete-code",
            type: "post",
            data: {
                id: $(this).attr("data-id"),
                page: $("#codes .pagination li.active a").attr("data-page")
            },
            success: function(data) {
                $("#codes").html(data);
            }
        });
    });
    
    // Show edit profile form
    $("#show-edit-profile").click(function() {
        $.ajax({
            url: "/profile/popup-profile-show",
            type: "post",
            success: function(data) {
                $("body > .wrap > .container").append(data);
                // Autocomplete for city
                $("#popup-profile input[name='model[city]']").autocomplete({
                    minLength: 1,
                    source: "/auth/city-search",
                    select: function() {
                        var country = $("#popup-profile input[name='model[country]']");
                        if (country.val() == "")
                            country.val(countryUkraine);
                    }
                });
                // Autocomplete for country
                $("#popup-profile input[name='model[country]']").autocomplete({
                    minLength: 1,
                    source: "/auth/country-search"
                });
            }
        });
    });
    
    // Submit profile form
    $("body").on("submit", "#popup-profile form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/profile/popup-profile-submit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-profile").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#popup-profile")))
                    window.location.reload();
            }
        });
        return false;
    });
    
    // Clear input errors on focus for profile form
    $("body").on("focus", "#popup-profile input", function() {
        $(this).closest(".form-group").removeClass("has-error");
        $(this).closest(".form-group").find(".errors").remove();
    });
    
    // Close popup
    $("body").on("click", "#popup-profile .close", function(e) {
        $("#popup-profile").remove();
    });
});