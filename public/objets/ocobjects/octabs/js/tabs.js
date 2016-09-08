function setActivTab(idTabs, noTab) {
    $("#"+idTabs+" ul li").removeClass("active");
    $("#"+idTabs+" ul li."+idTabs+"_TTAB_"+noTab).addClass("active");
    $("#"+idTabs+" .tab-content div").removeClass("active").addClass("fade");
    $("#"+idTabs+" .tab-content div#"+idTabs+"_CTAB_"+noTab).addClass("active").removeClass("fade");
}
