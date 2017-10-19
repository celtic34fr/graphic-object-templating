function odcheckbox(obj) {
    this.id = obj.attr('id');
    $this.options = [];
    var checked = obj.find("input:checkbox:checked");
    $.each(checked, function(){
        $this.options.push($(this).val());
    });
    this.data = obj.data();
}

odcheckbox.prototype = {
    getData: function (evt) {
        var chps = "id=" + this.id;
        chps = chps + "&value='" + this.options.join("$") + "'";
        chps = chps + "&evt='" + evt + "'";
        chps = chps + "&obj='OUI'";
        if (evt = this.data.evt) {
            var classe  = data['evt-'+evt+'-class'];
            var methode = data['evt-'+evt+'-method'];
            if ((classe.length > 0) && (methode.length > 0)) {
                chps = chps + "&callback='" + classe + "+" + methode +"'";
            }
        }
        return chps;
    },
    setData: function (data) {
        this.value = data;
        $("#"+this.id).val(data);

        if (data === "") { // raz des options sélectionnées
            $("#"+this.id+" option").removeAttr("selected");
            this.options = [];
        } else  {
            if ($.isArray(data)) {
                data.each(function(idx, val){
                    $("#"+this.id+" option:nth_child("+val+")").attr("selected","selected");
                    $this.options.push(val);
                })
            }
        }
    }
};