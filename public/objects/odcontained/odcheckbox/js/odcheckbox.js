function odcheckbox(obj) {
    this.id = obj.attr('id');
    this.options = [];
    var type = obj.find("input");
    if (type.length > 0) {
        var options = [];
        $.each(type, function(i, input){
            if (input.getAttribute('checked') !== null) {
                options.push(input.getAttribute('value'));
            }
        });
        this.options = options;
    }
    this.data = obj.data();
}

odcheckbox.prototype = {
    getData: function (evt) {
        var chps = "id=" + this.id;
        chps = chps + "&value='" + this.options.join("$") + "'";
        chps = chps + "&evt='" + evt + "'";
        chps = chps + "&obj='OUI'";
        if (evt = this.data.evt) {
            var classe  = this.data['evt-'+evt+'-class'];
            var methode = this.data['evt-'+evt+'-method'];
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