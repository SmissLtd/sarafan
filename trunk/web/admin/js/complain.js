$(function() {
    // Show complains page
    $("#complains").on("click", ".pagination a:not(.disabled)", function() {
        $.ajax({
            url: "/admin/complain/list",
            type: "post",
            data: {
                page: $(this).attr("data-page")
            },
            success: function(data) {
                $("#complains").html(data);
            }
        });
        return false;
    });
    
    // Goto users page with specified user selected
    $("#complains").on("click", "table .user", function() {
        window.location.href = "/admin/user/index?id=" + $(this).attr("data-id");
    });
});