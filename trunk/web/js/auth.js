$(function() {
    // Submit register form
    $("#form-register form").submit(function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $(this).find(".has-error").removeClass("has-success");
        $.ajax({
            url: "/auth/register-submit",
            type: "POST",
            data: $(this).serialize(),
            error: function(e) {
                $("#form-register form .btn:first").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                $("#form-register .errors").remove();
                if (!processFormErrors(data, $("#form-register")))
                    window.location.href = "/site/index";
            }
        });
        return false;
    });
    
    // Submit login form
    $("#form-login form").submit(function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/auth/login-submit",
            type: "POST",
            data: $(this).serialize(),
            error: function(e) {
                $("#form-login form .btn:first").parent().append('<div class="errors">' + e.responseText.replace("Internal Server Error (#500): ", "") + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#form-login")))
                    window.location.href = "/site/index";
            }
        });
        return false;
    });
    
    // Clear input errors on focus for login and register form
    $("#form-register input, #form-login input").focus(function() {
        $(this).closest(".form-group").removeClass("has-error");
        $(this).closest(".form-group").find(".errors").remove();
    });
    
    // Submit code validation
    $("#form-register input[name='model[code]']").keydown(function(event) {
        if (event.keyCode == 13)
        {
            $("#form-register #step-code button").click();
            return false;
        }
    });
    $("#form-register #step-code button").click(function() {
        $("#form-register .errors").remove();
        $.ajax({
            url: "/auth/validate-code",
            type: "POST",
            data: {
                model: {code: $("#form-register input[name='model[code]']").val()}
            },
            success: function(data) {
                if (!processFormErrors(data, $("#form-register")))
                    $("#form-register #step-social").slideDown(300);
            }
        });
    });
    
    // Decline login using social netwroks
    $("#form-register #step-social button").click(function() {
        $("#form-register #step-register").slideDown(300);
    });
    
    // Validate fields on leave it
    $("#form-register input").blur(function() {
        var field = $(this).attr("data-field");
        if (typeof(field) == "undefined")
            return;
        $.ajax({
            url: "/auth/register-validate",
            type: "post",
            data: {
                field: field,
                value: $("#form-register input[name='model[" + field + "]']").val()
            },
            success: function(data) {
                $("#form-register input[name='model[" + field + "]']").closest(".form-group").find(".errors").empty();
                processFormErrors(data, $("#form-register"));
            }
        });
    });
    
    // Click on restore password
    $("#form-login button[type!='submit']").click(function() {
        $("#form-login").hide();
        $("#form-restore").show();
    });
    
    // Return back to login form
    $("#form-restore button[type!='submit']").click(function() {
        $("#form-restore").hide();
        $("#form-login").show();
    });
    
    // Submit restore password form
    $("#form-restore form").submit(function() {
        $(this).find(".errors").remove();
        $(this).find(".has-error").removeClass("has-error");
        $.ajax({
            url: "/auth/restore-submit",
            type: "POST",
            data: $(this).serialize(),
            error: function(e) {
                $("#form-restore form .btn:first").parent().append('<div class="errors">' + e.responseText + "</div>");
            },
            success: function(data) {
                if (!processFormErrors(data, $("#form-restore")))
                    $("#form-restore").html(data);
            }
        });
        return false;
    });
});