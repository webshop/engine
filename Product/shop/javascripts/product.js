var ProductCollect={
    changeVariant:function(){
        var params = "product_collect_id="+$("#id_product_collect_id").val()+'&'+$("#id_variant_select_form").serialize();
        $.ajax({
            type: 'POST',
            url: '/Product/changeVariant',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                if (data.code=="1"){
                    $("#id_variant_select").html(data.select);
                    $("#id_variant_compare_at_price").html(data.compare_at_price);
                    $("#id_variant_price").html(data.price);
                    
                }else {
                    Popup.alert(lang('Hiba!'),lang("Hiba a lekérés során"));
                }
            }
        });
    },
    changeListType:function(list_type){
        $("#id_product_collect_list_type").val(list_type);
    },
    changeNumRecord:function(num,record){
        $("#id_params_num").val(parseInt(num));
        $("#id_params_record").val(parseInt(record));
        
    },
    listReload:function(num,record){
        num=num || 0;
        record=record || 0;
        if ((num!=0 && record!=0) || (record==0 && num!=0)){
            ProductCollect.changeNumRecord(num,record);
        }
        var params = $("#id_product_collect_params_form").serialize();
        $.ajax({
            type: 'POST',
            url: '/Product/listProductCollectAjax',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                if (data.code=="1"){
                    $("#id_product_collect_list").html(data.content);
                }else {
                    Popup.alert(lang('Hiba!'),lang("Hiba a lekérés során"));
                }
            }
        });
    }
}

