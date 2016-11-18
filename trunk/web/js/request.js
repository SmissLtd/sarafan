$(function() {
    // Close all ratings popup
    $("body").on("click", "#ratings .close", function() {
        $(this).closest("#ratings").remove();
    });
    
    // Show create contact popup
    $("body").on("click", "#create-contact", function() {
        reloadPageAfterContactChange = true;
        return showCreateContactPopup($(this).attr("data-request"));
    });
    
    // Rate contacts
    $("#contacts").on("click", "#rating button", function() {
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
    $("#contacts").on("click", "#all", function() {
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
    
    // Load more contacts
    $("#more-contacts").click(function() {
        $.ajax({
            url: "/request/list-contacts",
            type: "post",
            data: {
                id: $(this).attr("data-id"),
                page: $(this).attr("data-page")
            },
            success: function(data) {
                if (data.length > 0)
                {
                    $("#contacts").append(data);
                    $("#more-contacts").attr("data-page", parseInt($("#more-contacts").attr("data-page")) + 1);
                    window.location.href = "#more-contacts";
                }
            }
        });
    });
    
    // Show create answer(with/without quoting) popup
    $("body").on("click", "#reply", function() {
        $.ajax({
            url: "/request/popup-create-answer-show",
            type: "post",
            data: {
                root_id: $(this).closest(".contact").attr("data-id"),
                answer_id: $(this).attr("data-answer")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
            }
        });
        return false;
    });
    
    // Close create answer popup
    $("body").on("click", "#popup-create-answer .close", function() {
        $(this).closest("#popup-create-answer").remove();
    })
    
    // Submit create answer popup
    $("body").on("submit", "#popup-create-answer form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/request/popup-create-answer-submit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-create-answer").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#popup-create-answer")))
                {
                    var answerContactId = $("#popup-create-answer input[name='model[root_id]']").val();
                    var containerContactAnswers = $("#contacts .contact[data-id='" + answerContactId + "'] #answers");
                    $("#popup-create-answer").remove();
                    $.ajax({
                        url: "/request/list-answers",
                        type: "post",
                        data: {
                            id: answerContactId,
                            page: 1000000000
                        },
                        success: function(response) {
                            containerContactAnswers.html(response);
                        }
                    });
                }
            }
        });
        return false;
    });
    
    // Process answers pagination
    $("body").on("click", "#contacts #answers .pagination a", function() {
        var container = $(this).closest(".contact");
        $.ajax({
            url: "/request/list-answers",
            type: "post",
            data: {
                id: container.attr("data-id"),
                page: $(this).attr("data-page")
            },
            success: function(data) {
                container.find("#answers").html(data);
            }
        });
        return false;
    });
    
    // Show create complain popup
    $("body").on("click", "#complain", function() {
        $.ajax({
            url: "/complain/popup-create-show",
            type: "post",
            data: {
                id: $(this).attr("data-answer")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
            }
        });
        return false;
    });
    
    // Close create complain popup
    $("body").on("click", "#popup-create-complain .close", function() {
        $(this).closest("#popup-create-complain").remove();
    })
    
    // Submit create complain popup
    $("body").on("submit", "#popup-create-complain form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/complain/popup-create-submit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-create-complain").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#popup-create-complain")))
                    $("#popup-create-complain").remove();
            }
        });
        return false;
    });
    
    // Show delete answer confirm
    $("#contacts").on("click", ".admin-answer-delete", function() {
        $.ajax({
            url: "/admin/request/show-confirm-answer-delete",
            type: "post",
            data: {
                id: $(this).attr("data-id")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
            }
        });
    });
    
    // Close delete answer confirm
    $("body").on("click", "#confirm-answer-delete .close, #confirm-answer-delete .btn-default", function() {
        $("#confirm-answer-delete").remove();
    });
    
    // Delete answer
    $("body").on("submit", "#confirm-answer-delete form", function() {
        $.ajax({
            url: "/admin/request/answer-delete",
            type: "post",
            data: $(this).serialize(),
            success: function() {
                window.location.reload();
            }
        });
        return false;
    });
    
    // Show answer edit popup
    $("#contacts").on("click", ".admin-answer-edit", function() {
        $.ajax({
            url: "/admin/request/show-answer-edit",
            type: "post",
            data: {
                id: $(this).attr("data-id")
            },
            success: function(data) {
                $("body > .wrap > .container").append(data);
            }
        });
    });
    
    // Close answer edit popup
    $("body").on("click", "#popup-answer-edit .close", function() {
        $("#popup-answer-edit").remove();
    });
    
    // Submit answer edit popup
    $("body").on("submit", "#popup-answer-edit form", function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/admin/request/submit-answer-edit",
            type: "post",
            data: $(this).serialize(),
            error : function(e) {
                $("#popup-answer-edit").find("button").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#popup-answer-edit")))
                    window.location.reload();
            }
        });
        return false;
    });
    
    // Clear errors on focus on input on answer edit popup
    $("body").on("focus", "#popup-answer-edit input, #popup-answer-edit textarea", function() {
        $(this).closest(".form-group").removeClass("has-error");
        $(this).closest(".form-group").find(".errors").remove();
    });
});