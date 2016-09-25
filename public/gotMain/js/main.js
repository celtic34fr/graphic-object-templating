/**
 * méthode invokeAjax
 * @param datas : ensemble des données à communiquer à la callback appelé
 *
 * appel du module de gestion des appels aux callbacks
 */

function invokeAjax(datas) {
    var urlGotCallback = $("#gotCallback").html();
    $.ajax({
        url:        urlGotCallback,
        type:       'POST',
        dataType:   'json',
        async:      true,
        data:       datas,

        success: function (returnDatas, status) {
            $.each(returnDatas, function (i, ret) {
                var id   = "";
                var mode = "";
                var code = "";
                $.each(ret, function (j, k) {
                    switch (j) {
                        case 'id':
                            id = k;
                            break;
                        case 'mode':
                            mode = k;
                            break;
                        case 'html':
                            code = k;
                            break;
                    }
                });
                switch (mode) {
                    case "append":
                    {
                        $("#" + id).append(code);
                        if ($("#" + id).find("#" + id + "Script").length > 0)
                            $.globalEval($("#" + id + "Script").innerHTML);
                        break;
                    }
                    case "update":
                    {
                        $("#" + id).replaceWith(code);
                        if ($("#" + id).find("#" + id + "Script").length > 0)
                            $.globalEval($("#" + id + "Script").innerHTML);
                        break;
                    }
                    case "raz":
                    {
                        $("#" + id).html("");
                        break;
                    }
                        ;
                    case "delete":
                        $("#" + id).remove();
                        break;
                    case "exec":
                        $.globalEval(code);
                        break;
                    case "execID":
                        $.globalEval(($("#" + code)[0]).innerText);
                        break;
                    case "redirect":
                        $(location).attr('href', code);
                }
            });
        },

        error : function(xhr, textStatus, errorThrown) {
            if (xhr.status === 0) {
                alert('Not connected. Verify Network.');
            } else if (xhr.status == 404) {
                alert('Requested page not found. [404]');
            } else if (xhr.status == 500) {
                alert('Server Error [500].');
            } else if (errorThrown === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (errorThrown === 'timeout') {
                alert('Time out error.');
            } else if (errorThrown === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Remote sever unavailable. Please try later, '+xhr.status+"//"+errorThrown+"//"+textStatus);
            }
        }
    });
}


/**
 * méthode getFormDatas
 * @param form
 * @returns {string}
 *
 * méthode de récupération & formatage des données regroupées dans un 'formulaire'
 */
function getFormDatas(form) {
    var selection = "[data-form='" + form + "']";
    var formData = "";
    var eltSelection = $('*').find(selection);

    eltSelection.each(function () {
        var obj = $(this);
        var object = obj.attr('data-objet');
        var datas = '';

        switch (object) { // traitement suivant l'objet (type ODContained)
            case "odbutton":
                break;
            case "odcontent":
                odcontent_getData(obj, "");
                break;
            case "odinput":
                datas = odinput_getData(obj, '');
                break;
            case "odselect":
                datas = odselect_getData(obj, '');
                break;
            case "odcheckbox":
                datas = odcheckbox_getData(obj, '');
                break;
            case "odtoggle":
                datas = odtoggle_getData(obj, '');
                break
        }

        if (datas.length > 0) {
            datas = datas.replaceAll("&", "§");
            formData = formData + "|" + datas;
        }
    });
    if (formData.length > 0) {
        formData = formData.substr(1);
    }
    return formData;
}

/**
 * méthode razFormDatas
 * @param form
 *
 * méthode d'initialisation des données regroupées dans un 'formulaire'
 */
function razFormDatas(form) {
    var selection = "[data-form='" + form + "']";
    var eltSelection = $('*').find(selection);

    eltSelection.each(function () {
        var obj = $(this);
        var object = obj.attr('data-objet');
        var id = obj.attr('id');

        switch (object) { // traitement suivant l'objet (type ODContained)
            case "odbutton":
                odbutton_setData(id, "");
                break;
            case "odcontent":
                odcontent_setData(id, "");
                break;
            case "odinput":
                odinput_setData(id, "");
                break;
            case "odcheckbox":
                odcheckbox_setData(id, "");
                break;
            case "odselect":
                odselect_setData(id, "");
                break;
            case "odradio":
                odradio_setData(id, "");
                break;
        }
    });
}

function setFormDatas(form, datas) {
    var arrayData = datas.split("|");
    arrayData.each(function () {
        var id    = "";
        var value = "";
        var type  = "";

        dataObj = $(this).split("§");
        dataObj.each(function () {
            var pos = $(this).indexOf("=");
            var attr = $(this).substr(0, pos);

            switch (attr) {
                case "id":
                    id = $(this).substr(pos +1);
                    break;
                case "value":
                    value = $(this).substr(pos +1);
                    break;
                case "type":
                    type = $(this).substr(pos +1);
                    break;
            }
        });
        switch (type) {
            case "odbutton":
                odbutton_setData(id, value);
                break;
            case "odcontent":
                odcontent_setData(id, value);
                break;
            case "odinput":
                odinput_setData(id, value);
                break;
            case "odcheckbox":
                odcheckbox_setData(id, value);
                break;
            case "odselect":
                odselect_setData(id, value);
                break;
            case "odradio":
                odradio_setData(id, value);
                break;
        }
    })
}



/* méthode de restitution des valeurs d'objets */

/**
 * méthode odbutton_getData
 * @param obj objet de type ODButton
 * @param evt évènement déclencheur
 * @returns {string}
 *
 * méthode de formatage de la zone de communication avec le gestionnaire de callback
 * pour les objet de type ODButton
 */
function odbutton_getData(obj, evt) {
    var chps = "id=" + obj.attr("id") + "&value='" + obj.val() + "'";
    if (evt.length > 0) {
        var routine = obj.attr('data-' + evt);
        if (routine != undefined) {
            if (routine.length > 0) {
                chps = chps + "&callback='" + routine + "'";
            }
        }
    }

    var type = obj.attr('data-type');
    switch (type) {
        case "custom":
            break;
        case "submit":
            var formName = obj.attr('data-form');
            var dataForm = '(' + getFormDatas(formName) + ')';
            chps = chps + "&form='" + dataForm + "'";
            return chps;
        case "reset":
            break;
    }
    return chps;
}

/**
 * méthode odcontent_getData
 * @param obj
 * @param evt
 * @returns {string}
 */
function odcontent_getData(obj, evt) {
    var chps = "id=" + obj.attr("id");
    chps = chps + "&value='" + obj.html() + "'";
    chps = chps + "&type='" + obj.attr('data-objet') + "'";
    if (evt.length > 0) {
        var dataEvt = 'data-' + evt;
        var routine = obj.parent().attr(dataEvt);
        if (routine.length > 0) {
            chps = chps + "&callback='" + routine + "'";
        }
    }
    return chps;
}

/**
 * méthode odinput_getData
 * @param obj
 * @param evt
 * @returns {string}
 *
 * méthode de formatage de la zone de ciommunication avec le gestionnaire de callback
 * pour les objet de type ODInput
 */
function odinput_getData(obj, evt) {
    var chps = "id=" + obj.attr("id");
    chps = chps + "&value='" + obj.find("input").val() + "'";
    chps = chps + "&type='" + obj.find("input").attr('type') + "'";
    if (evt.length > 0) {
        var dataEvt = 'data-' + evt;
        var routine = obj.parent().attr(dataEvt);
        if (routine.length > 0) {
            chps = chps + "&callback='" + routine + "'";
        }
    }
    return chps;
}

/**
 * méthode odselect_GetData
 * @param obj
 * @param evt
 * @returns {string}
 */
function odselect_getData(obj, evt) {
    var chps = "id=" + obj.attr("id");
//    chps = chps + "&value='" + obj.find("select").val().join("$") + "'";
    chps = chps + "&value='" + obj.find("select").val() + "'";
    return chps;
}

/**
 * méthode odcheckbox_getData
 * @param obj
 * @param evt
 * @returns {string}
 */
function odcheckbox_getData(obj, evt) {
    var chps = "id=" + obj.attr("id");
    var checked = [];
    $.each($("#"+obj.attr('id')+" input:checked"), function(){
        checked.push($(this).val());
    });
    chps = chps + "&value='" + checked.join("$") + "'";
    if (evt.length > 0) {
        var dataEvt = 'data-' + evt;
        var routine = obj.attr(dataEvt);
        if (routine.length > 0) {
            chps = chps + "&callback='" + routine + "'";
        }
    }
    return chps;
}

/**
 * méthode odradio_getData
 * @param obj
 * @param evt
 * @returns {string}
 */
function odradio_getData(obj, evt) {
    var chps = "id=" + obj.attr("id");
    var checked = [];
    $.each($("#"+obj.attr('id')+" input:checked"), function(){
        checked.push($(this).val());
    });
    chps = chps + "&value='" + checked.join("$") + "'";
    if (evt.length > 0) {
        var dataEvt = 'data-' + evt;
        var routine = obj.attr(dataEvt);
        if (routine.length > 0) {
            chps = chps + "&callback='" + routine + "'";
        }
    }
    return chps;
}

/**
 * méthode odtable_getData
 * @param obj
 * @param evt
 * @param nature
 * @returns {string}
 */
function odtable_getData(obj, evt, nature) {
    var chps = "id=" + obj.attr("id");
    chps = chps + "&selected='";
    var child = $("#" + obj.attr("id") + " input").val();
    chps = chps + child + "'";
    if (evt.length > 0) {
        var dataEvt = 'data-' + evt;
        var pipe = child.indexOf("!");
        var col  = child.substr(0, pipe);
        var line = child.substr(pipe + 1);
        var routine = "";
        switch (nature) {
            case "col":
                routine = $("#" + obj.attr("id") + " td:nth-child(" + col + ")").attr(dataEvt);
                break;
            case "line":
                routine = $("#" + obj.attr("id") + " tr:nth-child(" + line + ")").attr(dataEvt);
                break;
            case "cell":
                routine = $("#" + obj.attr("id") + " tr:nth-child(" + line + ")" + " td:nth-child(" + col + ")").attr(dataEvt);
                break;
        }
        if (routine.length > 0) {
            chps = chps + "&callback='" + routine + "'";
        }
    }
    return chps;
}

/**
 * méthode odtoggle_getData
 * @param obj
 * @param evt
 * @returns {string}
 */
function odtoggle_getData(obj, evt) {
    var chps = "id=" + obj.attr("id");
    if  ($("#"+obj.attr('id')+" input").is(":checked")) {
        chps = chps + "&value='checked'";
    } else {
        chps = chps + "&value='unchecked'";
    }
    if (evt.length > 0) {
        var dataEvt = 'data-' + evt;
        var routine = obj.attr(dataEvt);
        if (routine.length > 0) {
            chps = chps + "&callback='" + routine + "'";
        }
    }
    return chps;
}

/* méthode d'affection de valeur aux objets */

/**
 * méthode odbutton_setData
 * @param obj
 * @param data
 *
 * méthode visant à affecter une valeur à un objet de type ODButton
 */
function odbutton_setData(id, data) {
    $("#"+id).val(data);
}

/**
 * méthode odcontent_setData
 * @param id
 * @param data
 */
function odcontent_setData(id, data) {
    $("#"+id).html(data);
}

/**
 * méthode ocinput_setData
 * @param id
 * @param data
 *
 * méthode visant à affecter une valeur à un objet de type OCInput
 */
function odinput_setData(id, data) {
    $("#"+ id +" input").val(data);
}

/**
 * méthode odselect_setData
 * @param id
 * @param data
 */
function odselect_setData(id, data) {
    if (data == "") { // raz des options sélectionnées
        $("#"+ id +" option").removeAttr("selected");
    } else  {
        if ($.isArray(data)) {
            data.each(function(idx, val){
                $("#"+ id +" option:nth_child("+val+")").attr("selected","selected");
            })
        }
    }
}

/**
 * méthode odcheckbox_setData
 * @param id
 * @param tabData
 */
function odcheckbox_setData(id, tabData) {
    if (data == "") { // raz des options sélectionnées
        $("#"+ id +" option").removeAttr("selected");
    } else  {
        if ($.isArray(data)) {
            data.each(function(idx, val){
                $("#"+ id +" option:nth_child("+val+")").attr("selected","selected");
            })
        }
    }
}

/**
 * méthode odradio_setData
 * @param id
 * @param tabData
 */
function odradio_setData(id, tabData) {
    if (data == "") { // raz des options sélectionnées
        $("#"+ id +' .radio input').removeAttr("checked");
    } else  {
        if ($.isArray(tabData)) {
            tabData.each(function(idx, val){
                $("#"+ id +" .radio:nth_child("+val+" input)").attr("checked","checked");
            })
        }
    }
}

/*
gestion des sauvegartde en sessionStorage
 */
function saveSession(id, datas) {
    // fonction visant à faire un stockage local du contenu d'un formulaire
    sessionStorage.setItem(id, datas);
}

function restoreSession(idSto) {
    return sessionStorage.getItem(idSto);
}

function razSession() {
    sessionStorage.clear();
}

function extractSessionKeys(prefix, selector, suffix) {
    var keys =$.map($(selector), function (e) {
        return e.value;
    });
    $.each(keys, function (idx, key) {
        keys[idx] = prefix + key + suffix;
    });
    return keys;
}

/**
 * méthode persistSession
 * permet d'affecter à un objet le contenu d'un ensemble d'item de sessionStorage (format JSON)
 * les objets utilisables sont ODInput, ODContent, ODButton
 * @param idCible
 * @param keys
 */
function persistSessions(idCible, keys) {
    var sessions = [];
    $.each(keys, function(idx, key){
        sessions.push((restoreSession(key) !== null) ? restoreSession(key) : "");
    });
    var type = $("#"+idCible).attr('data-objet');
    var valCible = JSON.stringify(sessions);
    switch (type) {
        case "odinput":
            odinput_setData(idCible, valCible);
            break;
        case "odcontent":
            odcontent_setData(idCible, valCible);
            break;
        case "odbutton":
            odbutton_setData(idCible, valCible);
            break;
    }
}

/**
 * fonction de traitement de chaîne : remplacer tout str1 par str2
 * @param str1      : caractère ou chaîne  à rechercher
 * @param str2      : caractère ou chaîne de remplacement
 * @param ignore    : ignore (true) ou pas (false) la casse
 * @returns {string}
 */
String.prototype.replaceAll = function (str1, str2, ignore) {
    return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g, "\\$&"), (ignore ? "gi" : "g")), (typeof(str2) == "string") ? str2.replace(/\$/g, "$$$$") : str2);
}

$.fn.hasAttr = function(name) {
    return this.attr(name) !== undefined;
};
