console.clear();
"use strict";

$(".toast").toast('show');

$("#showall").on("click", function() {
    $(".toast").toast('show');
});

$("#n_pos .btn").on("click", function(e) {
    var $this = $(this),
        $class = $this.attr("data-position"),
        $toast_wrapper = $("#toast_wrapper"),
        $btn_list = $("#btn_list");

    $class = (typeof($class) != undefined) ? $class : "";

    $btn_list.css({
        "text-align": ($class == "top-left" || $class == "bottom-left") ? "right" : "left"
    });

    $toast_wrapper.removeClass("top-left bottom-right bottom-left").addClass($class);
});
