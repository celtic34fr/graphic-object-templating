function odbadge(obj) {
    this.id = obj.attr('id');
    this.contenu = obj.attr('content');
    this.data = obj.data();
}

oedinput.prototype = {
    getData: function (evt) {
        var chps = "id=" + this.id + "&value='" + this.contenu + "'";
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
        this.contenu = data;
        $("#"+this.id).innerHTML(data);
    }
};