function setActivTab(idTabs, noTab) {
    $("#"+idTabs+" ul li").removeClass("active");
    $("#"+idTabs+" ul li.TTAB:nth-child("+noTab+")").addClass("active");
    $("#"+idTabs+" .tab-content div").removeClass("active").addClass("fade");
    $("#"+idTabs+" .tab-content div.CTAB:nth-child("+noTab+")").addClass("active").removeClass("fade");
}

function setNextTab(idTabs) {
    var maxTab = $("#"+idtabs+"BtnLast").val();
    var active = $("#"+idTabs+" .TTAB.active").attr("data-tabs");
    var next = active + 1;

    if (next == maxTab) {
        $("#"+idtabs+"BtnLast").attr('disabled', 'disabled');
        $("#"+idtabs+"BtnNext").attr('disabled', 'disabled');
    } else {
        $("#"+idtabs+"BtnLast").removeAttr('disabled');
        $("#"+idtabs+"BtnNext").removeAttr('disabled');
    }
    setActivTab(idTabs, next);
}

function setPreviousTab(idTabs) {
    var active = $("#"+idTabs+" .TTAB.active").attr("data-tabs");
    var previous = active - 1;

    if (previous == 1) {
        $("#"+idtabs+"BtnFirst").attr('disabled', 'disabled');
        $("#"+idtabs+"BtnPrevious").attr('disabled', 'disabled');
    } else {
        $("#"+idtabs+"BtnFirst").removeAttr('disabled');
        $("#"+idtabs+"BtnPrevious").removeAttr('disabled');
    }
    setActivTab(idTabs, previous);
}