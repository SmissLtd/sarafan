$(function() {
    // More contacts button click
    $("#more-contacts a").click(function() {
        $.ajax({
            url: "/contact/list",
            type: "post",
            dataType: "json",
            data: {
                id: $(this).attr("data-id"),
                page: $(this).attr("data-page"),
                order: $("#header-contacts select").val()
            },
            success: function(data) {
                if (data.data.length > 0)
                {
                    $("#list-contacts").append(data.data);
                    $("#more-contacts a").attr("data-page", parseInt($("#more-contacts a").attr("data-page")) + 1);
                }
                if (data.is_last)
                    $("#more-contacts").hide();
            }
        });
        return false;
    });
    
    // More requests button click
    $("#more-requests a").click(function() {
        $.ajax({
            url: "/request/list",
            type: "post",
            dataType: "json",
            data: {
                id: $(this).attr("data-id"),
                page: $(this).attr("data-page"),
                order: $("#header-requests select").val()
            },
            success: function(data) {
                if (data.data.length > 0)
                {
                    $("#list-requests").append(data.data);
                    $("#more-requests a").attr("data-page", parseInt($("#more-requests a").attr("data-page")) + 1);
                }
                if (data.is_last)
                    $("#more-requests").hide();
            }
        });
        return false;
    });
    
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
    
    // Change order of contacts
    $("#header-contacts select[name='sortby']").change(function() {
        $.ajax({
            url: "/contact/list",
            type: "post",
            dataType: "json",
            data: {
                id: $(this).attr("data-id"),
                page: 0,
                order: $(this).val()
            },
            success: function(data) {
                if (data.data.length > 0)
                {
                    $("#list-contacts").html(data.data);
                    $("#more-contacts a").attr("data-page", 1);
                }
                if (!data.is_last)
                    $("#more-contacts").show();
            }
        });
        return false;
    });
    
    // Change order of requests
    $("#header-requests select[name='sortby']").change(function() {
        $.ajax({
            url: "/request/list",
            type: "post",
            dataType: "json",
            data: {
                id: $(this).attr("data-id"),
                page: 0,
                order: $(this).val()
            },
            success: function(data) {
                if (data.data.length > 0)
                {
                    $("#list-requests").html(data.data);
                    $("#more-requests a").attr("data-page", 1);
                }
                if (!data.is_last)
                    $("#more-requests").show();
            }
        });
        return false;
    });
    
    // Show delete contact confirm
    $("#list-contacts").on("click", ".admin-contact-delete", function() {
        $.ajax({
            url: "/admin/contact/show-confirm-delete",
            type: "post",
            data: {
                id: $(this).attr("data-id")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
            }
        });
    });
    
    // Close delete contact confirm
    $("body").on("click", "#confirm-delete-contact .close, #confirm-delete-contact .btn-default", function() {
        $("#confirm-delete-contact").remove();
    });
    
    // Delete contact
    $("body").on("submit", "#confirm-delete-contact form", function() {
        $.ajax({
            url: "/admin/contact/delete",
            type: "post",
            data: $(this).serialize(),
            success: function() {
                window.location.reload();
            }
        });
        return false;
    });
    
    // Show contact edit popup
    $("#list-contacts").on("click", ".admin-contact-edit", function() {
        $.ajax({
            url: "/admin/contact/show-edit",
            type: "post",
            data: {
                id: $(this).attr("data-id")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
                // City autocomplete
                $("#popup-contact-edit input[name='model[city]']").autocomplete({
                    minLength: 1,
                    source: "/auth/city-search",
                    select: function() {
                        var country = $("#popup-contact-edit input[name='model[country]']");
                        if (country.val() == "")
                            country.val(countryUkraine);
                    }
                });
                // Country autocomplete
                $("#popup-contact-edit input[name='model[country]']").autocomplete({
                    minLength: 1,
                    source: "/auth/country-search"
                });
                // Add phone
                $("#popup-contact-edit").on("click", "#add-phone", function() {
                    var count = $("#popup-contact-edit #phones > div").length;
                    if (count >= 5)
                        return;
                    $(this).closest("#phones").append(
                            '<div class="container-fluid form-group">' +
                                '<div class="col-sm-offset-2 col-sm-8">' +
                                    '<input type="text" name="model[phone_' + count + ']" value="" class="form-control" />' +
                                '</div>' +
                                '<div class="col-sm-1" style="padding-left: 0;">' +
                                    '<button type="button" class="btn btn-default btn-block" id="add-phone">+</button>' +
                                '</div>' +
                                '<div class="col-sm-1" style="padding-left: 0;">' +
                                    '<button type="button" class="btn btn-default btn-block" id="delete-phone">-</button>' +
                                '</div>' +
                            '</div>');
                });
                // Delete phone
                $("#popup-contact-edit").on("click", "#delete-phone", function() {
                    var count = $("#popup-contact-edit #phones > div").length;
                    if (count <= 1)
                        return;
                    if ($(this).closest(".form-group").find("label").length > 0)
                        return;
                    $(this).closest(".form-group").remove();
                });
            }
        });
    });
    
    // Close contact edit popup
    $("body").on("click", "#popup-contact-edit .close", function() {
        $("#popup-contact-edit").remove();
    });
    
    // Submit contact edit popup
    $("body").on("submit", "#popup-contact-edit form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/admin/contact/submit-edit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-contact-edit").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#popup-contact-edit")))
                    window.location.reload();
            }
        });
        return false;
    });
    
    // Clear errors on focus on input on contact edit popup
    $("body").on("focus", "#popup-contact-edit input", function() {
        $(this).closest(".form-group").removeClass("has-error");
        $(this).closest(".form-group").find(".errors").remove();
    });
    
    // Show delete request confirm
    $("#list-requests").on("click", ".admin-request-delete", function() {
        $.ajax({
            url: "/admin/request/show-confirm-delete",
            type: "post",
            data: {
                id: $(this).attr("data-id")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
            }
        });
    });
    
    // Close delete request confirm
    $("body").on("click", "#confirm-delete-request .close, #confirm-delete-request .btn-default", function() {
        $("#confirm-delete-request").remove();
    });
    
    // Delete request
    $("body").on("submit", "#confirm-delete-request form", function() {
        $.ajax({
            url: "/admin/request/delete",
            type: "post",
            data: $(this).serialize(),
            success: function() {
                window.location.reload();
            }
        });
        return false;
    });
    
    // Show request edit popup
    $("#list-requests").on("click", ".admin-request-edit", function() {
        $.ajax({
            url: "/admin/request/show-edit",
            type: "post",
            data: {
                id: $(this).attr("data-id")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
                // City autocomplete
                $("#popup-request-edit input[name='model[city]']").autocomplete({
                    minLength: 1,
                    source: "/auth/city-search",
                    select: function() {
                        var country = $("#popup-request-edit input[name='model[country]']");
                        if (country.val() == "")
                            country.val(countryUkraine);
                    }
                });
                // Country autocomplete
                $("#popup-request-edit input[name='model[country]']").autocomplete({
                    minLength: 1,
                    source: "/auth/country-search"
                });
            }
        });
    });
    
    // Close request edit popup
    $("body").on("click", "#popup-request-edit .close", function() {
        $("#popup-request-edit").remove();
    });
    
    // Submit request edit popup
    $("body").on("submit", "#popup-request-edit form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/admin/request/submit-edit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-request-edit").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#popup-request-edit")))
                    window.location.reload();
            }
        });
        return false;
    });
    
    // Clear errors on focus on input on request edit popup
    $("body").on("focus", "#popup-request-edit input, #popup-request-edit textarea", function() {
        $(this).closest(".form-group").removeClass("has-error");
        $(this).closest(".form-group").find(".errors").remove();
    });
    
    $("#add-contact-button").click(function() {
        $("#popup-create-contact-show a").click();
    });
    $("#add-request-button").click(function() {
        $("#popup-create-request-show a").click();
    });
});