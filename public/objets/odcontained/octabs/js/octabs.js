
function toggle(id, prev, next) {
    $("#"+id+"_TTab_"+prev).removeClass("in active");
    $("#"+id+"_CTab_"+prev).removeClass("active").attr("aria-expanded", false);
    $("#"+id+"_TTab_"+next).addClass("in active");
    $("#"+id+"_CTab_"+next).addClass("active").attr("aria-expanded", true);
}
