function odradio(obj) {
    this.id = obj.attr('id');
    this.data = obj.data();
    $this.options = [];
    var checked = obj.find("select option:selected");
    $.each(checked, function(){
        $this.options.push($(this).val());
    });
}

odradio.prototype = {
    getDate: function (evt) {
        var chps = "id=" + this.id;
        chps = chps + "&value='" + this.options.join("$") + "'";
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
        if (data === "") { // raz des options sélectionnées
            $("#"+ this.id +" option").removeAttr("selected");
            this.options = [];
        } else  {
            $.each(data.split("$"), function(ind,val){
                if ($("#"+this.id+" select").hasClass("select2")) {
                    $("#"+this.id+" select").select2();
                    $("#"+this.id+" select").val(val).trigger("change");
                } else {
                    var selecteur = "#"+this.id+" option[value=" + val + "]";
                    $(selecteur).attr('selected', 'selected');
                }
                $this.options.push(val);
            });
        }
    }
};