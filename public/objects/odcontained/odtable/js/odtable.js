function odtable(obj) {
    this.id = obj.attr('id');
    $this.select = obj.find("input").val();
}

odtable.prototype = {
    getDate: function (evt) {
        var chps = "id=" + this.id;
        chps = chps + "&value='" + this.select + "'";
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
        return '';
    }
};