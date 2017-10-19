function odradio(obj) {
    this.id = obj.attr('id');
    this.data = obj.data();
    $this.options = [];
    var checked = obj.find("input:checked");
    $.each(checked, function(){
        $this.options.push($(this).val());
    });
}

odradio.prototype = {
    getDate: function (evt) {
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