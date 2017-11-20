function odcontent(obj) {
    this.id = obj.attr('id');
    this.contenu = obj.html();
    this.data = obj.data();
}

odcontent.prototype = {
    getData: function (evt) {
        var chps = "id=" + this.id;
        chps = chps + "&value='" + this.content + "'";
        chps = chps + "&type='" + obj.attr('data-objet') + "'";
        var dataEvent   = this.data.evt;
        if (dataEvent === evt) {
            var classe  = this.data['evt-'+evt+'-class'];
            var methode = this.data['evt-'+evt+'-method'];
            if ((classe.length > 0) && (methode.length > 0)) {
                chps = chps + "&callback='" + classe + "+" + methode +"'";
            }
        }
        return chps;
    },
    setData: function (data) {
        this.contenu = data;
        $("#"+this.id).html(data);
    }
}

