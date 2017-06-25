function setActivTab(idTabs, noTab) {
    var maxTab = parseInt($("#"+idTabs+"BtnLast").val());

    $("#"+idTabs+" ul li").removeClass("active");
    $("#"+idTabs+" ul li.TTAB:nth-child("+noTab+")").addClass("active");
    $("#"+idTabs+" .tab-content div").removeClass("active").addClass("fade");
    $("#"+idTabs+" .tab-content div.CTAB:nth-child("+noTab+")").addClass("active").removeClass("fade");

    $("#"+idTabs+"BtnFirst").removeAttr('disabled');
    $("#"+idTabs+"BtnPrevious").removeAttr('disabled');
    $("#"+idTabs+"BtnNext").removeAttr('disabled');
    $("#"+idTabs+"BtnLast").removeAttr('disabled');

    if (noTab == 1) {
        $("#"+idTabs+"BtnFirst").attr('disabled', 'disabled');
        $("#"+idTabs+"BtnPrevious").attr('disabled', 'disabled');
    } else if (noTab == maxTab) {
        $("#"+idTabs+"BtnLast").attr('disabled', 'disabled');
        $("#"+idTabs+"BtnNext").attr('disabled', 'disabled');
    }
}

function setNextTab(idTabs) {
    var maxTab = parseInt($("#"+idTabs+"BtnLast").val());
    var active = $("#"+idTabs+" .TTAB.active").attr("data-tabs");
    var next = parseInt(active) + 1;

    if (next == maxTab) {
        $("#"+idTabs+"BtnLast").attr('disabled', 'disabled');
        $("#"+idTabs+"BtnNext").attr('disabled', 'disabled');
    } else {
        $("#"+idTabs+"BtnLast").removeAttr('disabled');
        $("#"+idTabs+"BtnNext").removeAttr('disabled');
    }
    $("#"+idTabs+"BtnFirst").removeAttr('disabled');
    $("#"+idTabs+"BtnPrevious").removeAttr('disabled');
    setActivTab(idTabs, next);
}

function setPreviousTab(idTabs) {
    var active = $("#"+idTabs+" .TTAB.active").attr("data-tabs");
    var previous = parseInt(active) - 1;

    if (previous == 1) {
        $("#"+idTabs+"BtnFirst").attr('disabled', 'disabled');
        $("#"+idTabs+"BtnPrevious").attr('disabled', 'disabled');
    } else {
        $("#"+idTabs+"BtnFirst").removeAttr('disabled');
        $("#"+idTabs+"BtnPrevious").removeAttr('disabled');
    }
    $("#"+idTabs+"BtnLast").removeAttr('disabled');
    $("#"+idTabs+"BtnNext").removeAttr('disabled');
    setActivTab(idTabs, previous);
}