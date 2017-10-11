function hasClass(id, selector) {
    var className = " " + selector + " ";
    var element = $(id);
    var elemClassName = element.attr("class");
    if ((" " + elemClassName + " ").replace(/[\n\t]/g, " ").indexOf(className) > -1) {
        return true;
    }

    return false;
}

function openModal(id){
    console.log(hasClass(id,"is-hidden"));
     if (hasClass(id,"is-hidden")) {
         $(id).removeClass("is-hidden");
     }
}

function closeModal(id){
    console.log(hasClass(id,"is-hidden"));
    if (!hasClass(id, "is-hidden")) {
        $(id).addClass("is-hidden");
    }
}