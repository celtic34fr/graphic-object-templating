function odinput(obj) {
    this.id = obj.attr('id');
    this.value = obj.find("input").val();
    this.type = obj.find("input").attr("type");
    this.data = obj.data();
}

odinput.prototype = {
    getData: function (evt) {
        var chps = "id=" + this.id + "&value='" + this.value + "'";
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
        this.value = data;
        $("#"+this.id).val(data);
    }
};