/**
 * méthode invokeAjax
 * @param datas : ensemble des données à communiquer à la callback appelé
 * 
 * appel du module de gestion des appels aux callbacks 
 */
    function invokeAjax(datas) {
        $.ajax({
            url: '/got/callback',
            type: 'POST',
            data: datas,
            success: function(returnDatas){
                $.each(returnDatas, function(i, ret){
                    $.each(ret, function(j, k){
                        switch (j) {
                            case 'id':   id = k;   break;
                            case 'mode': mode = k; break;
                            case 'html': code = k; break;
                        }
                    });
                    console.log(code);
                    switch (mode) {
                        case "append":   {
                            $("#"+ id).append(code);
                            if ($("#"+ id).find("#"+ id + "Script").length > 0)
                               $.globalEval($("#"+ id + "Script").innerHTML);
                            break;
                        }
                        case "update":   {
                            $("#"+ id).replaceWith(code);
                            if ($("#"+ id).find("#"+ id + "Script").length > 0)
                                $.globalEval($("#"+ id + "Script").innerHTML);
                            break;
                        }
                        case "raz":      {
                            $("#"+ id).html("");
                            break;
                        };
                        case "delete":   $("#"+ id).remove();     break;
                        case "exec":     $.globalEval(code);      break;
                        case "execID":   $.globalEval($("#"+code).innerHTML); break;
                        case "redirect": $(location).attr('href', code);
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
        var selection = "[data-form='"+form+"']";
        var formData  = "";
        var eltSelection = $('*').find(selection);

        eltSelection.each(function(){
            var obj = $(this);
            var object = obj.attr('data-objet');
            var datas  = '';

            switch (object) { // traitement suivant l'objet (type ODContent)
                case "ocbutton": break;
                case "ocinput":  var datas = ocinput_getData(obj, ''); break;
            }

            if (datas.length > 0) {
                datas = datas.replace("&", "§");
                formData = formData + "|" + datas;
            }
        });
        if (formData.length > 0) { formData = formData.substr(1); }
        return formData;
    }

/**
 * méthode resetFormDatas
 * @param form
 * 
 * méthode d'initialisation des données regroupées dans un 'formulaire'
 */
    function resetFormDatas(form) {
        var selection = "[data-form='"+form+"']";
        $('*').find(selection).each(function(){
            var obj = $(this);
            var object = obj.attrb('data-objet');

            switch (object) {
                case "ocbutton":        ocbutton_setData(obj, "");          break;
                case "ocinput":         ocinput_setData(obj, "");           break;
            }
        })
    }

    /* méthode de restitution des valeurs d'objets */

/**
 * méthode ocbutton_getData
 * @param obj objet de type OCButton
 * @param evt évènement déclencheur
 * @returns {string}
 * 
 * méthode de formatage de la zone de ciommunication avec le gestionnaire de callback
 * pour les objet de type OCButton
 */
    function ocbutton_getData(obj, evt) {
        var chps = "id="+obj.attr("id")+"&value='" + obj.val() + "'";
        if(evt.length > 0) {
            var routine = obj.attr('data-'+evt);
            if (routine != undefined) {
                if (routine.length > 0) { chps = chps + "&callback='" + routine + "'"; }
            }
        }

        var type = obj.attr('data-type');
        switch (type) {
            case "custom":  break;
            case "submit":
                var formName = obj.attr('data-form');
                var dataForm = '[' + getFormDatas(formName) + ']';
                chps = chps + "&form='" + dataForm + "'";
                return chps;
            case "reset":   break;
        }
        return chps;
    }

/**
 * méthode ocinput_getData
 * @param obj
 * @param evt
 * @returns {string}
 *
 * méthode de formatage de la zone de ciommunication avec le gestionnaire de callback
 * pour les objet de type OCInput
 */
    function ocinput_getData(obj, evt) {
        console.log(obj);
        var chps = "id=" + obj.attr("id");
        var chps = chps + "&value='"+obj.children("input").val()+"'";
        var chps = chps + "&type='"+obj.children("input").attr('type')+"'";
        if(evt.length > 0) {
            var dataEvt = 'data-'+ evt;
            var routine = obj.attr(dataEvt);
            if (routine.length > 0) {
                chps = chps + "&callback='" + routine + "'";
            }
        }
        console.log(obj);
        return chps;
    }
    
    /* méthode d'affection de valeur aux objetx */

/**
 * méthode ocbutton_setData
 * @param obj
 * @param data
 * 
 * méthode visant à affecter une valeur à un objet de type OCButton
 */
    function ocbutton_setData(obj, data) { obj.val(data); }

/**
 * méthode ocinput_setData
 * @param obj
 * @param data
 *
 * méthode visant à affecter une valeur à un objet de type OCInput
 */
    function ocinput_setData(obj, data) { obj.val(data); }
