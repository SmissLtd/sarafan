var reloadPageAfterContactChange = false;

$(function() {
    // Clear input errors on focus
    $("body").on("focus", "#popup-create-contact input, #popup-create-request input, #popup-create-contact textarea, #popup-create-request textarea", function() {
        $(this).closest(".form-group").removeClass("has-error");
        $(this).closest(".form-group").find(".errors").remove();
    });
    
    // Show create contact popup
    $("#popup-create-contact-show a").click(function() {
        reloadPageAfterContactChange = false;
        return showCreateContactPopup(0);
    });
    
    // Submit create contact popup
    $("body").on("submit", "#popup-create-contact form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/contact/popup-create-submit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-create-contact").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!checkContactExists(data, $("#popup-create-contact")))
                    if (!processFormErrors(data, $("#popup-create-contact")))
                        if (reloadPageAfterContactChange)
                            window.location.reload();
                        else
                            window.location.href = "/contact/view?id=" + data;
            }
        });
        return false;
    });
    
    // Close create contact popup
    $("body").on("click", "#popup-create-contact .close", function() {
        $(this).closest("#popup-create-contact").remove();
    });
    
    // Create contact add phone
    $("body").on("click", "#popup-create-contact #add-phone", function() {
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
            $("#popup-create-contact #add-phone").remove();
    });
    
    // Show create request popup
    $("#popup-create-request-show a").click(function() {
        $.ajax({
            url: "/request/popup-create-show",
            type: "post",
            success: function(data) {
                $("body > .wrap > .container").append(data);
                // Autocomplete for category selection
                $("#popup-create-request input[name='model[category_title]']").autocomplete({
                    source: "/contact/search-category",
                    minLength: 1,
                    delay: 500,
                    select: function(event, ui) {
                        $("#popup-create-request input[name='model[category_id]']").val(ui.item.id);
                    }
                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                    if (item.disabled)
                        return $("<li class='ui-state-disabled'>").attr("data-value", item.value).append(item.label).appendTo(ul);
                    return $("<li style='padding-left: 15px;'>").attr("data-value", item.value).append(item.label).appendTo(ul);
                };
                if (currentCategory.length > 0)
                {
                    $("#popup-create-request input[name='model[category_id]']").val(currentCategory);
                    $("#popup-create-request input[name='model[category_title]']").val(currentCategoryTitle);
                }
                // Autocomplete for city
                $("#popup-create-request input[name='model[city]']").autocomplete({
                    minLength: 1,
                    source: "/auth/city-search",
                    select: function() {
                        var country = $("#popup-create-request input[name='model[country]']");
                        if (country.val() == "")
                            country.val(countryUkraine);
                    }
                });
                // Autocomplete for country
                $("#popup-create-request input[name='model[country]']").autocomplete({
                    minLength: 1,
                    source: "/auth/country-search"
                });
            }
        });
        return false;
    });
    
    // Submit create request popup
    $("body").on("submit", "#popup-create-request form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/request/popup-create-submit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-create-request").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#popup-create-request")))
                    window.location.href = "/request/view?id=" + data;
            }
        });
        return false;
    });
    
    // Close create request popup
    $("body").on("click", "#popup-create-request .close", function() {
        $(this).closest("#popup-create-request").remove();
    });
    
    // Show menu items when scrolling homepage
    $(window).scroll(function() {
        if ($(window).scrollTop() == 0)
            $(".navbar li.hidable").hide();
        else
            $(".navbar li.hidable").show();
    });
});