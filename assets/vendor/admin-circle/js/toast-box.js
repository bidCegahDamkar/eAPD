///////////////////////////////////////////////////////////////////////////
// Toast
// trigger toast
function toastbox(target, time) {
    var a = "#" + target;
    //$(".toast-box").removeClass("show");
    setTimeout(() => {
        //$(a).addClass("show");
        $(a).modal("show");
    }, 100);
    if (time) {
        time = time + 100;
        setTimeout(() => {
            //$(".toast-box").removeClass("show");
            $(a).modal("hide");
        }, time);
    }
};

///////////////////////////////////////////////////////////////////////////