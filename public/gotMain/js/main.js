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
            case "odonoff":
                var datas = odonoff_getData(obj, '');
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
            case "odinput":
                odinput_setData(id, value);
                break;
            case "odselect":
                odselect_setData(id, value)
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

function odselect_getData(obj, evt) {
    var chps = "id=" + obj.attr("id");
    chps = chps + "&value='" + obj.find("select").val().join("$") + "'";
    return chps;
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

function odonoff_getData(obj, evt) {
    var chps = "id=" + obj.attr("id");
    if  ($("#"+obj.attr('id')).is(":checked")) {
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
 * Sauvegarde et restauration de données de formulaire (HTML5 SessionStorage)
 * (voir à étendre à des données pures (hors formulaire)
 */

function saveForm(id, form) {
    // fonction visant à faire un stockage local du contenu d'un formulaire
    var idSto = $(id).val();
    var datas = getFormDatas(form);
    sessionStorage.setItem(idSto, datas);
}

function reastoreForm(idSto, form) {
    var datas = sessionStorage.getItem(idSto);
    setFormData(form, datas)
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

