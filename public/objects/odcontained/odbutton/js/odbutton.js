function odbutton(obj) {
    this.id = obj.attr('id');
    this.type = obj.data("type");
    this.data = obj.data();
    this.value = obj.val();
}

odbutton.prototype = {
    getData: function (evt) {
        var chps = "id=" + this.id + "&value='" + this.value + "'";
        if (evt = this.data.evt) {
            var classe  = data['evt-'+evt+'-class'];
            var methode = data['evt-'+evt+'-method'];
            if ((classe.length > 0) && (methode.length > 0)) {
                chps = chps + "&callback='" + classe + "+" + methode +"'";
            }
        }
        return chps;

        switch (this.type) {
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

    },
    setData: function (data) {
        this.value = data;
        $("#"+this.id).val(data);
    }
};