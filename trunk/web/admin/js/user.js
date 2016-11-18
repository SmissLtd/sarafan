$(function() {
    // Sort users
    $("#users").on("click", "table .sortable", function() {
        $.ajax({
            url: "/admin/user/sort",
            type: "post",
            data: {
                field: $(this).attr("data-field")
            },
            success: function(data) {
                $("#users").html(data);
            }
        });
    });
    
    // Filter users
    $("#form-filter").submit(function() {
        $.ajax({
            url: "/admin/user/filter",
            type: "post",
            data: $(this).serialize(),
            success: function(data) {
                $("#users").html(data);
            }
        });
        return false;
    });
    
    // Reset users filter
    $("#form-filter #reset").click(function() {
        $(this).closest("form").find("input").val('');
        $(this).closest("form").find("select[name='show-deleted']").val('exclude');
        $(this).closest("form").find("select[name='show-blocked']").val('all');
        $.ajax({
            url: "/admin/user/filter",
            type: "post",
            data: $(this).serialize(),
            success: function(data) {
                $("#users").html(data);
            }
        });
    });
    
    // Close delete confirm popup
    $("#confirm-delete-user .close, #confirm-delete-user .btn-default").click(function() {
        $("#confirm-delete-user").hide();
    });
    
    // Show confirm delete user
    $("#users").on("click", "#delete", function() {
        var confirm = $("#confirm-delete-user");
        confirm.find("input[name='delete-id']").val($(this).closest("tr").attr("data-id"));
        confirm.find("#title").html($(this).closest("tr").find("td.name").html());
        confirm.show();
    });
    
    // Delete user
    $("#confirm-delete-user .btn-danger").click(function() {
        $("#confirm-delete-user").hide();
        $.ajax({
            url: "/admin/user/delete",
            type: "post",
            data: {
                id: $("#confirm-delete-user input[name='delete-id']").val()
            },
            success: function() {
                $.ajax({
                    url: "/admin/user/list",
                    type: "post",
                    success: function(data) {
                        $("#users").html(data);
                    }
                });
            }
        });
    });
    
    // Restore user
    $("#users").on("click", "#restore", function() {
        $.ajax({
            url: "/admin/user/restore",
            type: "post",
            data: {
                id: $(this).closest("tr").attr("data-id")
            },
            success: function() {
               $.ajax({
                    url: "/admin/user/list",
                    type: "post",
                    success: function(data) {
                        $("#users").html(data);
                    }
                }); 
            }
        });
    });
    
    // Show users page
    $("#users").on("click", ".pagination a:not(.disabled)", function() {
        $.ajax({
            url: "/admin/user/page",
            type: "post",
            data: {
                page: $(this).attr("data-page")
            },
            success: function(data) {
                $("#users").html(data);
            }
        });
        return false;
    });
    
    // Close block user popup
    $("body").on("click", "#popup-user-block .close, #popup-user-block .btn-default", function() {
        $("#popup-user-block").remove();
    });
    
    // Show user block popup
    $("#users").on("click", "#block", function() {
        $.ajax({
            url: "/admin/user/show-block",
            type: "post",
            data: {
                id: $(this).closest("tr").attr("data-id")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
                $("#popup-user-block input[name='model[till]']").datepicker();
            }
        });
    });
    
    // Submit user block popup
    $("body").on("submit", "#popup-user-block form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/admin/user/submit-block",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-user-block").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#popup-user-block")))
                {
                    $("#popup-user-block").remove();
                    $.ajax({
                        url: "/admin/user/list",
                        type: "post",
                        success: function(data) {
                            $("#users").html(data);
                        }
                    });
                }
            }
        });
        return false;
    });
    
    // Clear errors on focus in popups
    $("body").on("focus", "#popup-user-block input, #popup-user-block textarea, #popup-user-edit input, #popup-user-edit textarea", function() {
        $(this).closest(".form-group").removeClass("has-error");
        $(this).closest(".form-group").find(".errors").remove();
    });
    
    // Unblock user
    $("#users").on("click", "#unblock", function() {
        $.ajax({
            url: "/admin/user/unblock",
            type: "post",
            data: {
                id: $(this).closest("tr").attr("data-id")
            },
            success: function() {
               $.ajax({
                    url: "/admin/user/list",
                    type: "post",
                    success: function(data) {
                        $("#users").html(data);
                    }
                }); 
            }
        });
    });
    
    // Show edit user popup
    $("#users").on("click", ".edit", function() {
        $.ajax({
            url: "/admin/user/show-edit",
            type: "post",
            data: {
                id: $(this).closest("tr").attr("data-id")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
                // Autocomplete for city
                $("#popup-user-edit input[name='model[city]']").autocomplete({
                    minLength: 1,
                    source: "/auth/city-search",
                    select: function() {
                        var country = $("#popup-user-edit input[name='model[country]']");
                        if (country.val() == "")
                            country.val(countryUkraine);
                    }
                });
                // Autocomplete for country
                $("#popup-user-edit input[name='model[country]']").autocomplete({
                    minLength: 1,
                    source: "/auth/country-search"
                });
            }
        });
    });
    
    // Show create user popup
    $("#users").on("click", "#create", function() {
        $.ajax({
            url: "/admin/user/show-edit",
            type: "post",
            success: function(data) {
                $("body > .wrap > .container").append(data);
                // Autocomplete for city
                $("#popup-user-edit input[name='model[city]']").autocomplete({
                    minLength: 1,
                    source: "/auth/city-search",
                    select: function() {
                        var country = $("#popup-user-edit input[name='model[country]']");
                        if (country.val() == "")
                            country.val(countryUkraine);
                    }
                });
                // Autocomplete for country
                $("#popup-user-edit input[name='model[country]']").autocomplete({
                    minLength: 1,
                    source: "/auth/country-search"
                });
            }
        });
    });
    
    // Close create/edit user popup
    $("body").on("click", "#popup-user-edit .close", function() {
        $("#popup-user-edit").remove();
    });
    
    // Submit user create/edit popup
    $("body").on("submit", "#popup-user-edit form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/admin/user/submit-edit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-user-edit").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#popup-user-edit")))
                {
                    $("#popup-user-edit").remove();
                    $.ajax({
                        url: "/admin/user/list",
                        type: "post",
                        success: function(data) {
                            $("#users").html(data);
                        }
                    });
                }
            }
        });
        return false;
    });
    
    // Goto list of referrers
    $("#users").on("click", "table .referrers", function() {
        window.location.href = "/admin/user/referrers?id=" + $(this).closest("tr").attr("data-id");
    });
    
    // Show referrers page
    $("#referrers").on("click", ".pagination a:not(.disabled)", function() {
        $.ajax({
            url: "/admin/user/referrers-list",
            type: "post",
            data: {
                page: $(this).attr("data-page"),
                user_id: $("input[name='referrers-owner-id']").val()
            },
            success: function(data) {
                $("#referrers").html(data);
            }
        });
        return false;
    });
});