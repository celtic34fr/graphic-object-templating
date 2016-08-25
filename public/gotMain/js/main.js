/**
 * méthode invokeAjax
 * @param datas : ensemble des données à communiquer à la callback appelé
 *
 * appel du module de gestion des appels aux callbacks
 */
function invokeAjax(datas) {
    var urlGotCallback = $("#gotCallback").html();
    $.ajax({
        url: urlGotCallback,
        type: 'POST',
        data: datas,
        success: function (returnDatas) {
            $.each(returnDatas, function (i, ret) {
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
                console.log(code);
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
        dataType: "json"
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

        switch (object) { // traitement suivant l'objet (type ODContent)
            case "odbutton":
                break;
            case "odinput":
                var datas = odinput_getData(obj, '');
                break;
            case "odselect":
                var datas = odselect_getData(obj, '');
                break;
            case "odcheckbox":
                var  datas = odcheckbox_getData(obj, '');
                break;
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
 * méthode resetFormDatas
 * @param form
 *
 * méthode d'initialisation des données regroupées dans un 'formulaire'
 */
function resetFormDatas(form) {
    var selection = "[data-form='" + form + "']";
    $('*').find(selection).each(function () {
        var obj = $(this);
        var object = obj.attr('data-objet');

        switch (object) {
            case "odcheckbox":
                odcheckbox_setData(obj.attr('id'), "");
                break;
            case "odradio":
                odradio_setData(obj.attr('id'), "");
                break;
            case "odinput":
                odinput_setData(obj.attr('id'), "");
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
    chps = chps + "&value='" + obj.val() + "'";
    chps = chps + "&type='" + obj.attr('type') + "'";
    if (evt.length > 0) {
        var dataEvt = 'data-' + evt;
        var routine = obj.parent().attr(dataEvt);
        if (routine.length > 0) {
            chps = chps + "&callback='" + routine + "'";
        }
    }
    return chps;
}

function odselect_getData(obj, evt) {
    var chps = "id=" + obj.attr("id");
}

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

/* méthode d'affection de valeur aux objets */

/**
 * méthode odbutton_setData
 * @param obj
 * @param data
 *
 * méthode visant à affecter une valeur à un objet de type ODButton
 */
function odbutton_setData(obj, data) {
    obj.val(data);
}

/**
 * méthode ocinput_setData
 * @param id
 * @param data
 *
 * méthode visant à affecter une valeur à un objet de type OCInput
 */
function odinput_setData(id, data) {
    $("#"+id+" input").val(data);
}

function odselect_setData(obj, data) {

}

function odcheckbox_setData(id, tabData) {
    for (var key in tabData) {
        if (key != 'replaceAll') {
            $('#'+id+' .checkbox input#'+id+key).checked = tabData.key;
        } else {
            $('#'+id+' .checkbox input').each(function () {
                this.checked = false;
            });
            break;
        }
    }
}

function odradio_setData(id, tabData) {
    for (var key in tabData) {
        if (key != 'replaceAll') {
            $('#'+id+' .radio input#'+id+key).checked = tabData.key;
        } else {
            $('#'+id+' .radio input').each(function () {
                this.checked = false;
            });
            break;
        }
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