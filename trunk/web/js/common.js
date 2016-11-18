function processFormErrors(data, form)
{
    try
    {
        data = $.parseJSON(data);
        if (typeof(data.error) != "undefined" && data.error == 1)
        {
            for (var field in data.data)
            {
                var input = form.find("input[name='model[" + field + "]'], textarea[name='model[" + field + "]']");
                if (input.length > 0)
                {
                    var errors = input.parent().find(".errors");
                    if (errors.length == 0)
                    {
                        errors = input.parent().append('<div class="errors"></div>');
                        errors = input.parent().find(".errors");
                    }
                    input.closest(".form-group").addClass("has-error");
                    for (var index in data.data[field])
                        errors.append("<div>" + data.data[field][index] + "</div>");
                }
            }
            return true;
        }
    }
    catch (e)
    {
    }
    return false;
}

function showCreateContactPopup(requestId)
{
    $.ajax({
        url: "/contact/popup-create-show",
        type: "post",
        data: {
            request_id: requestId
        },
        success: function(data) {
            $("body > .wrap > .container").append(data);
            // Autocomplete for category selection
            if ($("#popup-create-contact input[name='model[category_id]']").val() == '0')
            {
                $("#popup-create-contact input[name='model[category_title]']").autocomplete({
                    source: "/contact/search-category",
                    minLength: 1,
                    delay: 500,
                    select: function(event, ui) {
                        $("#popup-create-contact input[name='model[category_id]']").val(ui.item.id);
                    }
                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                    if (item.disabled)
                        return $("<li class='ui-state-disabled'>").attr("data-value", item.value).append(item.label).appendTo(ul);
                    return $("<li style='padding-left: 15px;'>").attr("data-value", item.value).append(item.label).appendTo(ul);
                };
                if (currentCategory.length > 0)
                {
                    $("#popup-create-contact input[name='model[category_id]']").val(currentCategory);
                    $("#popup-create-contact input[name='model[category_title]']").val(currentCategoryTitle);
                }
            }
            // Autocomplete for city
            $("#popup-create-contact input[name='model[city]']").autocomplete({
                minLength: 1,
                source: "/auth/city-search",
                select: function() {
                    var country = $("#popup-create-contact input[name='model[country]']");
                    if (country.val() == "")
                        country.val(countryUkraine);
                }
            });
            // Autocomplete for country
            $("#popup-create-contact input[name='model[country]']").autocomplete({
                minLength: 1,
                source: "/auth/country-search"
            });
        }
    });
    return false;
}

function checkContactExists(data, form)
{
    try
    {
        data = $.parseJSON(data);
        if (typeof(data.data.exists) != "undefined" && data.data.exists == 1)
        {
            $("body > .wrap > .container").append(data.data.popup);
            $("#popup-confirm-contact-edit button.btn-danger, #popup-confirm-contact-edit .close").click(function() {
                $("#popup-confirm-contact-edit").remove();
            });
            $("#popup-confirm-contact-edit button.btn-success").click(function() {
                for(var key in data.data.contact)
                    form.find("input[name='model[" + key + "]']").attr("disabled", "disabled").val(data.data.contact[key]);
                form.find("input[name='contact_id']").val(data.data.contact["id"]);
                form.find("#phones .form-group:not(:first)").remove();
                form.find("#phones button").attr("disabled", "disabled");
                form.find("#phones input").attr("disabled", "disabled").val(data.data.contact["phone"]);
                $("#popup-confirm-contact-edit").remove();
            });
            return true;
        }
    }
    catch (e)
    {
    }
    return false;
}