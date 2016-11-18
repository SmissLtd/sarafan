$(function() {
    // Select category
    $("#categories").on("click", ".list > div > div", function() {
        $("#subcategories").empty().hide();
        var wasActive = $(this).hasClass("active");
        $("#categories .list > div > div.active").removeClass("active");
        $("#subcategories").empty();
        if (wasActive)
            return;
        $(this).addClass("active");
        $.ajax({
            url: "/category/list-subcategories",
            type: "post",
            data: {
                id: $(this).attr("data-id"),
                filter: '',
                page: 0
            },
            success: function(data) {
                $("#subcategories").html(data).show();
                window.location.href = "#subcategories";
                setTimeout(function() {
                    $("#subcategories input:first").focus();
                }, 500);
            }
        });
    });
    
    // Filter categories
    $("#categories").on("keypress", "input[name='filter']", function(event) {
        if (event.keyCode == 13)
        {
            $.ajax({
                url: "/category/list-categories",
                type: "post",
                data: {
                    filter: $(this).val(),
                    page: 0
                },
                success: function(data) {
                    $("#categories").html(data).show();
                }
            });
        }
    });
    
    // Filter categories by button
    $("#categories").on("click", "#filter-apply", function(event) {
        $.ajax({
            url: "/category/list-categories",
            type: "post",
            data: {
                filter: $("#categories input[name='filter']").val(),
                page: 0
            },
            success: function(data) {
                $("#categories").html(data).show();
            }
        });
    });
    
    // Clear categories filter
    $("#categories").on("click", "#filter-clear", function(event) {
        $("#categories input[name='filter']").val('');
        $.ajax({
            url: "/category/list-categories",
            type: "post",
            data: {
                filter: '',
                page: 0
            },
            success: function(data) {
                $("#categories").html(data).show();
            }
        });
    });
    
    // Show prev & next categories page
    $("#categories").on("click", ".prev:not(.disabled), .next:not(.disabled)", function() {
        $.ajax({
            url: "/category/list-categories",
            type: "post",
            data: {
                filter: $("#categories input[name='filter']").val(),
                page: $(this).attr("data-page")
            },
            success: function(data) {
                $("#categories").html(data).show();
            }
        });
    });
    
    // Filter subcategories
    $("#subcategories").on("keypress", "input[name='filter']", function(event) {
        if (event.keyCode == 13)
        {
            $.ajax({
                url: "/category/list-subcategories",
                type: "post",
                data: {
                    id: $("#subcategories input[name='parent_id']").val(),
                    filter: $(this).val(),
                    page: 0
                },
                success: function(data) {
                    $("#subcategories").html(data).show();
                }
            });
        }
    });
    
    // Filter subcategories by button
    $("#subcategories").on("click", "#filter-apply", function(event) {
        $.ajax({
            url: "/category/list-subcategories",
            type: "post",
            data: {
                id: $("#subcategories input[name='parent_id']").val(),
                filter: $("#subcategories input[name='filter']").val(),
                page: 0
            },
            success: function(data) {
                $("#subcategories").html(data).show();
            }
        });
    });
    
    // Clear subcategories filter
    $("#subcategories").on("click", "#filter-clear", function(event) {
        $("#subcategories input[name='filter']").val('');
        $.ajax({
            url: "/category/list-subcategories",
            type: "post",
            data: {
                id: $("#subcategories input[name='parent_id']").val(),
                filter: '',
                page: 0
            },
            success: function(data) {
                $("#subcategories").html(data).show();
            }
        });
    });
    
    // Show prev & next subcategories page
    $("#subcategories").on("click", ".prev:not(.disabled), .next:not(.disabled)", function() {
        $.ajax({
            url: "/category/list-subcategories",
            type: "post",
            data: {
                id: $("#subcategories input[name='parent_id']").val(),
                filter: $("#subcategories input[name='filter']").val(),
                page: $(this).attr("data-page")
            },
            success: function(data) {
                $("#subcategories").html(data).show();
            }
        });
    });
    
    // Select subcategory
    $("#subcategories").on("click", ".list > div > div", function() {
        window.location.href = "/category/contacts?id=" + $(this).attr("data-id");
    });
    
    // Clear input errors on focus
    $("#form-create-contact input, #form-create-request input, #form-create-contact textarea, #form-create-request textarea").focus(function() {
        $(this).closest(".form-group").removeClass("has-error");
        $(this).closest(".form-group").find(".errors").remove();
    });
    
    // Create contact category selection
    $("#form-create-contact input[name='model[category_title]']").autocomplete({
        source: "/contact/search-category",
        minLength: 1,
        delay: 500,
        select: function(event, ui) {
            $("#form-create-contact input[name='model[category_id]']").val(ui.item.id);
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (item.disabled)
            return $("<li class='ui-state-disabled'>").attr("data-value", item.value).append(item.label).appendTo(ul);
        return $("<li style='padding-left: 15px;'>").attr("data-value", item.value).append(item.label).appendTo(ul);
    };
    
    // Create contact add phone
    $("#form-create-contact").on("click", "#add-phone", function() {
        $(this).closest(".col-sm-5").append(
                '<div class="form-group">' +
                    '<div class="col-sm-11" style="padding: 0;">' +
                        '<input type="text" name="model[phone_' + nextPhoneIndex + ']" value="" class="form-control" />' +
                    '</div>' +
                    '<div class="col-sm-1" style="padding: 0;">' +
                        '<button type="button" class="btn btn-default btn-block" id="add-phone">+</button>' +
                    '</div>' +
                '</div>');
        nextPhoneIndex++;
        if (nextPhoneIndex > 4)
            $("#form-create-contact #add-phone").remove();
    });
    
    // Create request category selection
    $("#form-create-request input[name='model[category_title]']").autocomplete({
        source: "/contact/search-category",
        minLength: 1,
        delay: 500,
        select: function(event, ui) {
            $("#form-create-request input[name='model[category_id]']").val(ui.item.id);
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (item.disabled)
            return $("<li class='ui-state-disabled'>").attr("data-value", item.value).append(item.label).appendTo(ul);
        return $("<li style='padding-left: 15px;'>").attr("data-value", item.value).append(item.label).appendTo(ul);
    };
    
    // Submit create contact form
    $("#form-create-contact form").submit(function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/contact/popup-create-submit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#form-create-contact").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!checkContactExists(data, $("#form-create-contact")))
                    if (!processFormErrors(data, $("#form-create-contact")))
                        window.location.href = "/contact/view?id=" + data;
            }
        });
        return false;
    });
    
    // Submit create request form
    $("#form-create-request form").submit(function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/request/popup-create-submit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#form-create-request").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#form-create-request")))
                    window.location.href = "/request/view?id=" + data;
            }
        });
        return false;
    });
    
    // Click on "Request" button, activate first field
    $("a[href='#request']").click(function() {
        setTimeout(function() {
            $("#form-create-request input[name='model[category_title]']:first").focus();
        }, 500);
        return true;
    });
    
    // Click on "Contact" button, activate first field
    $("a[href='#contact']").click(function() {
        setTimeout(function() {
            $("#form-create-contact input[name='model[category_title]']:first").focus();
        }, 500);
        return true;
    });
    
    // Click on "Search" button, activate search field
    $("a[href='#categories']").click(function() {
        setTimeout(function() {
            $("#categories input:first").focus();
        }, 500);
        return true;
    });
    
    // Autocomplete for city in create contact form
    $("#form-create-contact input[name='model[city]']").autocomplete({
        minLength: 1,
        source: "/auth/city-search",
        select: function() {
            var country = $("#form-create-contact input[name='model[country]']");
            if (country.val() == "")
                country.val(countryUkraine);
        }
    });
    
    // Autocomplete for country in create contact form
    $("#form-create-contact input[name='model[country]']").autocomplete({
        minLength: 1,
        source: "/auth/country-search"
    });
    
    // Autocomplete for city in create request form
    $("#form-create-request input[name='model[city]']").autocomplete({
        minLength: 1,
        source: "/auth/city-search",
        select: function() {
            var country = $("#form-create-request input[name='model[country]']");
            if (country.val() == "")
                country.val(countryUkraine);
        }
    });
    
    // Autocomplete for country in create request form
    $("#form-create-request input[name='model[country]']").autocomplete({
        minLength: 1,
        source: "/auth/country-search"
    });
});