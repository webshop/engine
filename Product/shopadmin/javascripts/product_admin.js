var ProductCollect ={
    validator: function(){
        validator = new FormValidator("#product_collect_form", {
            "onDisplayMessage": function (field, message) {
                lib.showErrorMessage(field, message);
            },
            "onClearMessage": function () {
                jQuery('.error').remove();
            },
            "onComplete": function (validator) {

            }
        });
    },
    
    changeType:function(){
        var product_collect_type_id=$("#id_product_collect_type_select").val();
        var product_collect_id=$("#id_product_collect").val();
        if (product_collect_id>0 && product_collect_type_id>0){
            var params = "product_collect_type_id="+product_collect_type_id+'&product_collect_id='+product_collect_id;
            $.ajax({
                type: 'POST',
                url: '/Product/getProductCollectTypeProperties',
                data: params,
                dataType: 'JSON',
                success: function (data) {
                    if (data.code=="1"){
                        $("#id_product_collect_variant").html(data.properties);
                    }else {
                        Popup.alert(lang('Hiba!'),lang("Hiba a lekérés során"));
                    }
                }
            });
        }
    },
    changePropertiesCheckbox:function(product_collect_property_name_id){
        var product_collect_id=$("#id_product_collect").val();
        if (product_collect_id>0 && product_collect_property_name_id>0){
            var params = "product_collect_id="+product_collect_id+'&product_collect_property_name_id='+product_collect_property_name_id;
            $.ajax({
                type: 'POST',
                url: '/Product/getProductCollectCheckboxValues',
                data: params,
                dataType: 'JSON',
                success: function (data) {
                    if (data.code=="1"){
                        Popup.popup(lang("Tulajdonság"),data.content);
                    }else {
                        Popup.alert(lang('Hiba!'),lang("Hiba a lekérés során"));
                    }
                }
            });
        }
    },
    savePropertiesCheckbox:function(){
        var product_collect_id=$("#id_product_collect").val();
        var product_collect_property_name_id=$("#id_product_collect_property_name_id").val();
        if (product_collect_id>0 && product_collect_property_name_id>0){
            var params = "product_collect_id="+product_collect_id+'&'+$("#id_product_collect_checkbox_properties_form").serialize();
            $.ajax({
                type: 'POST',
                url: '/Product/saveProductCollectCheckboxValues',
                data: params,
                dataType: 'JSON',
                success: function (data) {
                    if (data.code=="1"){
                        $("#id_checkbox_values_"+product_collect_property_name_id).html(data.content);
                        $( ".close-reveal-modal").trigger( "click" );
                    }else {
                        Popup.alert(lang('Hiba!'),lang("Hiba a lekérés során"));
                    }
                }
            });
        }
    },
    
    del:function(product_collect_id){
        if (product_collect_id>0){
            var params = "product_collect_id="+product_collect_id;
            $.ajax({
                type: 'POST',
                url: '/Product/deleteProductCollect',
                data: params,
                dataType: 'JSON',
                success: function (data) {
                    if (data.code=="1"){
                        Popup.success(lang("Sikeres művelet!"), lang('Sikeres törlés!'));
                        $('#product_collect_'+product_collect_id).remove();
                        $('#id_product_collect_list').HTMLTable('loadContent');
                    }else if(data.code=="0") {
                        Popup.alert(lang('Hiba!'),lang("Hiba a törlés során"));
                    }
                }
            });
        }
        
    }
}
var Product = {
    validator: function(){
        validator = new FormValidator("#product_form", {
            "onDisplayMessage": function (field, message) {
                lib.showErrorMessage(field, message);
            },
            "onClearMessage": function () {
                jQuery('.error').remove();
            },
            "onComplete": function (validator) {
                if (validator.data.status=="1"){
                    $("#product_form input:text").val('');
                    $("#id_product_id").val(0);
                    $("#id_product_collect_products").HTMLTable("loadContent");
                }
            }
        });
    },
    edit:function(product_id){
        var params = "product_id="+product_id+'&product_collect_id='+$("#id_product_collect").val();
        $.ajax({
            type: 'POST',
            url: '/Product/editProductCollectProduct',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                if (data.code=="1"){
                    $("#id_product_collect_product_edit").html(data.form);
                    Product.validator();
                }else if(data.code=="0") {
                    Popup.alert(lang('Hiba!'),lang("Hiba a művelet során"));
                }
            }
        });    
    },
    reGenerate:function(){
        Popup.confirm(lang('Figyelem!'), lang('Biztosan újra szeretnéd generálni a gyűjtőhöz tartozó termékeket?'),function(){
            var product_collect_id=$("#id_product_collect").val();
            var params = "product_collect_id="+product_collect_id;
            $.ajax({
                type: 'POST',
                url: '/Product/reGenerateCollectProduct',
                data: params,
                dataType: 'JSON',
                success: function (data) {
                    if (data.code=="1"){
                        Popup.success(lang("Sikeres művelet!"), lang('Sikeres újragenerálás!'));
                        $("#id_product_collect_products").HTMLTable("loadContent");
                    }else if(data.code=="0") {
                        Popup.alert(lang('Hiba!'),lang("Hiba a művelet során"));
                    }
                }
            });
        }); 
    },
    del:function(product_id){
        if (product_id>0){
            var params = "product_id="+product_id;
            $.ajax({
                type: 'POST',
                url: '/Product/deleteProductCollectProduct',
                data: params,
                dataType: 'JSON',
                success: function (data) {
                    if (data.code=="1"){
                        Popup.success(lang("Sikeres művelet!"), lang('Sikeres törlés!'));
                        $('#id_product_collect_products').HTMLTable('loadContent');
                    }else if(data.code=="0") {
                        alert(lang("Hiba a törlés során"));
                        Popup.alert(lang('Hiba!'),lang("Hiba a törlés során"));
                    }
                }
            });
        }
    },
    changePiece:function(product_id,store_id){
        if (product_id>0 && store_id>0){
            var params = "product_id="+product_id+"&store_id="+store_id+'&piece='+$("#id_product_piece_"+product_id+"_"+store_id).val();
            $.ajax({
                type: 'POST',
                url: '/Product/changeProductPiece',
                data: params,
                dataType: 'JSON',
                success: function (data) {
                    if (data.code=="1"){
                        Popup.success(lang("Sikeres művelet!"), lang('Sikeres módosítás!'));
                        $("#id_product_collect_products").HTMLTable('loadContent');
                    }else if(data.code=="0") {
                        Popup.alert(lang('Hiba!'),lang("Hiba a művelet során"));
                    }
                }
            });
        }
    }
}

var ProductCollectType = {
    validator: function(){
        validator = new FormValidator("#product_collect_type_form", {
            "onDisplayMessage": function (field, message) {
                lib.showErrorMessage(field, message);
            },
            "onClearMessage": function () {
                jQuery('.error').remove();
            },
            "onComplete": function (validator) {

            }
        });
    },
    del:function(product_collect_type_id){
        var params = "product_collect_type_id="+product_collect_type_id;
        $.ajax({
            type: 'POST',
            url: '/Product/isDeletableProductCollectType',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                if (data.code=="1"){
                    Popup.success(lang("Sikeres művelet!"), lang('Sikeres törlés!'));
                    $('#id_product_collect_type_list').HTMLTable('loadContent');
                }else if(data.code=="0") {
                    Popup.alert(lang('Hiba!'),lang("Hiba a törlés során"));
                }else if (data.code=="2"){
                    Popup.confirm(lang('Figyelem!'), lang('Már rögzítettek ilyen típusú terméket, biztosan törölni akarod'), function(){
                        $.ajax({
                            type: 'POST',
                            url: '/Product/delProductCollectType',
                            data: params,
                            dataType: 'JSON',
                            success: function (data) {
                                if (data.code=="1"){
                                    Popup.success(lang("Sikeres művelet!"), lang('Sikeres törlés!'));
                                    $('#id_product_collect_type_list').HTMLTable('loadContent');
                                }else if(data.code=="0") {
                                    Popup.alert(lang('Hiba!'),lang("Hiba a törlés során"));
                                }
                            }
                        });
                    });
                }
            }
        });
    },
    saveVariantType:function(){
        var product_collect_type_id=$("#id_product_collect_type").val();
        var params = "product_collect_type_id="+product_collect_type_id+"&"+$("#product_collect_type_variant_form").serialize();
        $.ajax({
            type: 'POST',
            url: '/Product/saveProductCollectTypeVariant',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                if (data.code=="1"){
                    Popup.success(lang("Sikeres művelet!"), lang('Sikeres mentés!'));
                }else {
                    Popup.alert(lang('Hiba!'),lang("Hiba a metnés során"));
                }
            }
        });
    },
    saveVariantNoType:function(){
        $("#id_multiselect_variant option").removeAttr("selected");
        $("#id_product_collect_type_variant_no").val(1);
        ProductCollectType.saveVariantType();
    },
    savePropertyType:function(){
        var product_collect_type_id=$("#id_product_collect_type").val();
        var params = "product_collect_type_id="+product_collect_type_id+"&"+$("#product_collect_type_property_form").serialize();
        $.ajax({
            type: 'POST',
            url: '/Product/saveProductCollectTypeProperty',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                if (data.code=="1"){
                    Popup.success(lang("Sikeres művelet!"), lang('Sikeres mentés!'));
                }else {
                    Popup.alert(lang('Hiba!'),lang("Hiba a metnés során"));
                }
            }
        });
    }
}
var ProductCollectVariant = {
    validator: function(){
        validator = new FormValidator("#product_collect_variant_form", {
            "onDisplayMessage": function (field, message) {
                lib.showErrorMessage(field, message);
            },
            "onClearMessage": function () {
                jQuery('.error').remove();
            },
            "onComplete": function (validator) {

            }
        });
    },
    del:function(product_collect_variant_id){
        var params = "product_collect_variant_id="+product_collect_variant_id;
        $.ajax({
            type: 'POST',
            url: '/Product/isDeletableProductCollectVariant',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                if (data.code=="1"){
                    Popup.success(lang("Sikeres művelet!"), lang('Sikeres törlés!'));
                    $('#id_product_collect_variant_list').HTMLTable('loadContent');
                }else if(data.code=="0") {
                    Popup.alert(lang('Hiba!'),lang("Hiba a törlés során"));
                }else if (data.code=="2"){
                    Popup.confirm(lang('Figyelem!'), lang('Már rögzítettek ilyen típusú terméket, biztosan törölni akarod'), function(){
                        $.ajax({
                            type: 'POST',
                            url: '/Product/delProductCollectVariant',
                            data: params,
                            dataType: 'JSON',
                            success: function (data) {
                                if (data.code=="1"){
                                    Popup.success(lang("Sikeres művelet!"), lang('Sikeres törlés!'));
                                    $('#id_product_collect_variant_list').HTMLTable('loadContent');
                                }else if(data.code=="0") {
                                    Popup.alert(lang('Hiba!'),lang("Hiba a törlés során"));
                                }
                            }
                        });
                    });
                }
            }
        });
    },
    saveValue:function(){
        var product_collect_variant_id=ProductCollectVariant.getVariantId();
        var params = "product_collect_variant_id="+product_collect_variant_id+"&"+$("#product_collect_variant_value_form").serialize();
        $.ajax({
            type: 'POST',
            url: '/Product/saveProductCollectVariantValue',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                if (data.code=="1"){
                    $("#id_product_collect_variant_value_id").val(0);
                    $("#id_product_collect_variant_value_name").val('');
                    $('#id_sortable_Table').HTMLTable('loadContent');
                }
            }
        });
    },
    getValues:function(){
        var product_collect_variant_id=ProductCollectVariant.getVariantId();
        if (product_collect_variant_id>0){
            var params="product_collect_variant_id="+product_collect_variant_id;
            $.ajax({
                type: 'POST',
                url: '/Product/getProductCollectVariantValue',
                data: params,
                dataType: 'JSON',
                success: function (data) {
                    $("#id_product_collect_variant_values").html(data.table);
                    ProductCollectVariant.sortable();
                }
            });
        }
            
    },
    editValue:function(product_collect_variant_value_id){
        $("#id_product_collect_variant_value_id").val(product_collect_variant_value_id);
        $("#id_product_collect_variant_value_name").val($("#product_collect_variant_value_name_"+product_collect_variant_value_id).text());
    },
    delValue:function(product_collect_variant_value_id){
        var params="product_collect_variant_value_id="+product_collect_variant_value_id;
        $.ajax({
            type: 'POST',
            url: '/Product/delProductCollectVariantValue',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                $("#id_product_collect_variant_value_id").val(0);
                $("#id_product_collect_variant_value_name").val('');
                $('#id_sortable_Table').HTMLTable('loadContent');
            }
        });
    },
    getVariantId:function(){
        return $("#id_product_collect_variant_id").val();
    },
    sortable:function(){
        $('#id_sortable_Table').sortable({
            axis:'y',
            items:'tr.sortable_row',
            update:function(){
                ProductCollectVariant.sendSorted();
            }
        });
    },
    sendSorted:function(){
        var product_collect_variant_id=ProductCollectVariant.getVariantId();
        var params="product_collect_variant_id="+product_collect_variant_id+'&'+$('#id_sortable_Table').sortable('serialize');
        $.ajax({
            type: 'POST',
            url: '/Product/setProductCollectVariantValueSorted',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                notifyUpdate(lang("Sikeres sorrend módosítás"),"green");
            }
        });
    }
}
var ProductCollectPropertyName = {
    validator: function(){
        validator = new FormValidator("#product_collect_property_name_form", {
            "onDisplayMessage": function (field, message) {
                lib.showErrorMessage(field, message);
            },
            "onClearMessage": function () {
                jQuery('.error').remove();
            },
            "onComplete": function (validator) {

            }
        });
    },
    del:function(product_collect_property_name_id){
        var params = "product_collect_property_name_id="+product_collect_property_name_id;
        $.ajax({
            type: 'POST',
            url: '/Product/isDeletableProductCollectPropertyName',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                if (data.code=="1"){
                    Popup.success(lang("Sikeres művelet!"), lang('Sikeres törlés!'));
                    $('#id_product_collect_property_name').HTMLTable('loadContent');
                }else if(data.code=="0") {
                    Popup.alert(lang('Hiba!'),lang("Hiba a törlés során"));
                }else if (data.code=="2"){
                    Popup.confirm(lang('Figyelem!'), lang('Már rögzítettek ilyen tulajdonságú terméket, biztosan törölni akarod'), function(){
                        $.ajax({
                            type: 'POST',
                            url: '/Product/delProductCollectPropertyName',
                            data: params,
                            dataType: 'JSON',
                            success: function (data) {
                                if (data.code=="1"){
                                    Popup.success(lang("Sikeres művelet!"), lang('Sikeres törlés!'));
                                    $('#id_product_collect_property_name').HTMLTable('loadContent');
                                }else if(data.code=="0") {
                                    Popup.alert(lang('Hiba!'),lang("Hiba a törlés során"));
                                }
                            }
                        });
                    });
                }
            }
        });
    },
    getValues:function(){
        var product_collect_property_name_id=ProductCollectPropertyName.getPropertyNameId();
        if (product_collect_property_name_id>0){
            var params="product_collect_property_name_id="+product_collect_property_name_id;
            $.ajax({
                type: 'POST',
                url: '/Product/getProductCollectPropertyNameSelectValue',
                data: params,
                dataType: 'JSON',
                success: function (data) {
                    $("#id_product_collect_property_name_select_values").html(data.table);
                }
            });
        }
            
    },
    editValue:function(product_collect_property_name_id){
        $("#id_product_collect_property_name_select_value_id").val(product_collect_property_name_id);
        $("#id_product_collect_property_name_select_value").val($("#product_collect_property_name_select_value_name_"+product_collect_property_name_id).text());
    },
    saveValue:function(){
        var product_collect_property_name_id=ProductCollectPropertyName.getPropertyNameId();
        var params = "product_collect_property_name_id="+product_collect_property_name_id+"&"+$("#product_collect_property_select_value_form").serialize();
        $.ajax({
            type: 'POST',
            url: '/Product/saveProductCollectPropertyNameValue',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                if (data.code=="1"){
                    $("#id_product_collect_property_name_select_value_id").val(0);
                    $("#id_product_collect_property_name_select_value").val('');
                    $('#id_product_collect_property_name').HTMLTable('loadContent');
                    
                }
            }
        });
    },
    delValue:function(product_collect_property_name_id){
        var params="product_collect_property_name_value_id="+product_collect_property_name_id;
        $.ajax({
            type: 'POST',
            url: '/Product/delProductCollectPropertyNameValue',
            data: params,
            dataType: 'JSON',
            success: function (data) {
                $("#id_product_collect_property_name_select_value_id").val(0);
                $("#id_product_collect_property_name_select_value").val('');
                $('#id_product_collect_property_name').HTMLTable('loadContent');
            }
        });
    },
    getPropertyNameId:function(){
        return $("#id_product_collect_property_name_id").val();
    },
}

var Category = {
    category_id : 0,
    image_uploader: null,
    image_type: 3,
    Init: function() {
        this.closeCategoryTree();
        // Expand selected
        if ($(".category-tree .selected").length > 0) {
            $(".category-tree .selected").each(function() {
                $(this).parent().parent().show();
            });
        } else {
            $(".category-tree").find("a:first").addClass("selected");
        }

        this.showSelectedName();

        this.onNewTree();

        // Ez azért kell, mert egyébként ENTER-re elküldte a formot.
        jQuery("#category_name").keypress(function(e) {
            if (e.keyCode == 13) {
                Category.saveCategory();
                return false;
            }
        });
        this.setupSortable();
    },
    createImageUploader:function(catid){
        this.destroyImageUploader();
        $('#category_edit_image_form_container').html('<div class="dropper_wrapper image_uploader_wrapper_' + catid + '_' + Category.image_type + '"></div>');
        
        Category.image_uploader = new ImageManager({
            type: Category.image_type,
            ref_id: catid,
            action: '/Image/imageUpload?ref_id=' + catid + '&type=' + Category.image_type,
        });
    },
    destroyImageUploader: function(){
        if(Category.image_uploader !== null){
            delete Category.image_uploader;
            $('#category_edit_image_form_container').html('');
        }
    },
    showSelectedName: function() {
        // A kiválasztott kategória nevének megjelenítése a gomb fölött
        var darab = $(".category-tree").find("a.selected").length;
        var elotag = [];
        var utolso = "";
        var i = 0;
        $(".category-tree").find("a.selected").each(function() {
            i += 1;
            if (i == darab) {
                utolso = $(this).text();
                if ($(this).data('img_url')==""){
                    $("#category_img_prev").hide();
                } else {
                    $("#category_img_prev").attr("src", $(this).data('img_url'));
                    $("#category_img_prev").show();
                }
            } else {
                elotag.push("<a href='javascript:Category.showCategory(" + $(this).data('id') + ");'>" + $(this).text() + "</a>");
            }
        });

        elotag.push(utolso);
        $("#bread_crumbs").html(elotag.join(" &raquo; "));
        
    },
    // Frissítés után vagy új fa megjelenítéskor ennek le kell futni
    onNewTree: function() {
        // Expand/collapse on click
        $(".category-tree li A").click(Category.onClick);

        $(".category-tree li A").each(function() {
            $(this).prop("title", $(this).text());
        });
        this.setupSortable();
    },
    showCategory: function(id) {
        $("#sore_category_" + id).trigger("click");
//        Category.setSelected($("#sore_category_" + id));

        // Show all selected
        $(".category-tree .selected").each(function() {
            $(this).parent().parent().show();
        });
        $("#sore_category_" + id).focus();
    },
    // A kategória fában az elemre kattintáskor fut le
    onClick: function() {
        Category.category_id=$(this).data("id");
        Category.hideEditCategory();
        var toggle = true;
        // ha nem látható a gyerek, akkor kell nyitni.
        // ha látható a gyerek és ki voltak választva, akkor be kell csukni
        // ha látható a gyerek de nem voltak kiválasztva, akkor nem kell becsukni
        if ($(this).parent().find("UL:first").length > 0) {
            if ($(this).parent().find("UL:first").css('display') == 'block') {
                var last_selected_id = $(".category-tree").find("a.selected:last").data("id");
                if ($(this).data("id") != last_selected_id) {
                    toggle = false;
                }
            }
        }

        if (toggle) {
            $(this).parent().find("UL:first").stop(true, true).slideToggle("medium");
        }
        Category.setSelected(this);
    },
    setSelected: function(tag){
        $(".category-tree").find("a").removeClass('selected');
        $(tag).addClass('selected');

        // kiválasztott elem szülőinek kijelölése
        var i = 0;
        while (true) {
            // Ha elértünk a fa tetejére lépjünk ki.
            if ($(tag).parent().parent().hasClass("category-tree")) {
                break;
            }
            tag = $(tag).parent().parent().parent().find("a:first");
            $(tag).addClass('selected');

            i += 1;
            if (i > 40)
                break;  // végtelen ciklus elkerülése
        }
        Category.showSelectedName();
    },
    newChildCategory: function() {
        var category_id = $(".category-tree").find("a.selected:last").data("id");
        this.showCategoryEditor(0, category_id, '');
    },
    editCategory: function() {
        var category_id = $(".category-tree").find("a.selected:last").data("id");
        this.showCategoryEditor(category_id, 0, '');
    },
    loadCategoryEditForm:function (category_id, parent_id){
        $.ajax({
            type: 'POST',
            url: '/Product/loadCategoryEditForm',
            data: {
                category_id : category_id,
                parent_id : parent_id
            },
            dataType: 'JSON',
            async: false,
            success: function (data) {
                $('#category_editor').html(data.form);
            }
        });
    },
    hideEditCategory: function() {
        $("#category_editor").empty();
    },
    showCategoryEditor: function(id, parent_id, category_name) {
        this.loadCategoryEditForm(id, parent_id);
        $("#category_editor").show();
        $("#category_name").focus();
        if (id>0){
            $("#add-categoryphoto").show();
            Category.createImageUploader(id);
            
            var img_src = $("#sore_category_"+id).data('img_url');
            if (img_src==""){
                img_src="/images/design/photo-empty-thumbnail128.png";
            }
            $(".uploadsinglePhoto img").attr("src", img_src);
            $("#category_img_prev").attr("src", img_src);
        }else{
            $("#add-categoryphoto").hide();
        }
    },
    saveCategory: function() {
        $("#category_editor").hide();
        $("#category_loader").show();
        var category_name = $("#category_name").val();
        category_name = jQuery.trim(category_name);
        if (category_name == "") {
            notifyUpdate(lang('The name field can not be empty!'), 'red');
            return;
        }

        jQuery.post("/Product/categorySave", $("#category_edit_form").serialize(), function(data) {
            if (data.success == 1) {
                jQuery(".category-tree").html(data.tree);
                Category.onNewTree();
                Category.showCategory(data.category_id);
            } else if (typeof(data.error) != "undefined") {
                notifyUpdate(data.error, 'red');
            } else {
                notifyUpdate("An error occurred during save progress!", 'red');
            }
            $("#category_loader").hide();
        });
    },
    expandCategoryTree: function() {
        $(".category-tree").find("UL").show();
    },
    closeCategoryTree: function() {
        $(".category-tree").find("UL").hide();
    },
    deleteCategory: function() {
        var category_id = $(".category-tree").find("a.selected:last").data("id");
        var category_name = $(".category-tree").find("a.selected:last").text();

        if (confirm(lang("Do you really want to delete the category and sub-category of all this?\n\n" + category_name))) {
            $("#category_loader").show();
            jQuery.post("/Product/categoryDelete", {category_id: category_id}, function(data) {
                if (data.success == 1) {
                    jQuery(".category-tree").html(data.tree);
                    Category.onNewTree();
                    Category.showCategory(data.parent_id);
                } else if (typeof(data.error) != "undefined") {
                    notifyUpdate(data.error, 'red');
                } else {
                    notifyUpdate("An error occurred during delete progress!", 'red');
                }
                $("#category_loader").hide();
            });
        }
    },

    setupSortable: function(){
        $(".category-tree li").each(function(){
            if ($(this).find('ul').length>0){
                $(this).find('ul').sortable({
                    axis: "y",
                    distance: 15,
                    cursor: "move",
                    forcePlaceholderSize: true ,
                    opacity: 0.5,
                    tolerance: "intersect",
                    placeholder: "ui-state-highlight",
                    update: function( event, ui ){
                        var moved_category_id = ui.item.find("a:first").data("id");
                        Category.setSelected(ui.item.find("a:first"));

                        var prev_category_id = ui.item.prev().find("a:first").data("id");
                        var next_category_id = ui.item.next().find("a:first").data("id");
                        if (typeof(prev_category_id)=="undefined"){
                            prev_category_id = 0;
                        }
                        if (typeof(next_category_id)=="undefined"){
                            next_category_id = 0;
                        }
                        var direction = 'up';
                        if (ui.position.top>ui.originalPosition.top){
                            direction = 'down';
                        }
                        Category.updateOrder(direction, moved_category_id, prev_category_id, next_category_id);
                    }
                });
            }
        });
    },

    updateOrder: function (direction, moved_id, prev_id, next_id){
        Category.hideEditCategory();
        $("#category_loader").show();
        jQuery.post("/Product/updateOrder", {direction:direction, moved_id:moved_id, prev_id:prev_id, next_id:next_id}, function(data) {
            if (data.success == 1) {
                notifyUpdate(lang('Order saved successfully.'), 'green');
            } else if (typeof(data.error) != "undefined") {
                notifyUpdate(data.error, 'red');
            } else {
                notifyUpdate("An error occurred during save progress!", 'red');
            }
            $("#category_loader").hide();
        });

    }
}

CategorySelector = {
    id : 'category',
    hidden_name:'category_id',
    expand : false,   // kell-e a legalsó szintig megjeleníteni a kategóriákat, vagy csak a következő szintig
    callback : null,
    Init : function(id, expand, hidden_name) {
        this.id=id;
        if (expand){
            this.expand=expand;
        }
        if( typeof hidden_name !== 'undefined' ) {
             this.hidden_name=hidden_name;
        }
    },
    show : function(id){
        jQuery("#"+CategorySelector.id).before("<div id='category_selector_placer' class='loader-small' style='display:block;'></div>");
        jQuery("#"+CategorySelector.id).remove();
        var append = "<span id='"+CategorySelector.id+"'>";
        jQuery.get("/Product/getCategorySelector", {category_id:id, expand:CategorySelector.expand}, function(data) {
            if (data.success == 1) {
                var count = jQuery(data.categories).length;
                if (count>0){
                    var select_iterator = 1;
                    jQuery.each(data.categories,function(level){        
                        append += "<select class='select' id='"+CategorySelector.id+"_"+level+"' onchange='CategorySelector.selectChange("+level+")' data-level='"+level+"'>";
                        var level_items = this;
                        var option_iterator = 1;
                        jQuery.each(level_items, function(){
                            if (!CategorySelector.expand && (select_iterator>=1) && (option_iterator===1)){
                                append += "<option value='choose' data-real='false'>"+lang('--- Choose ---')+"</option>";
                            }
                            var item = this;
                            append += "<option value='"+item.id+"' data-real='"+item.real+"'";
                            if (item.selected){
                                append += " selected";
                            }
                            append += ">"+item.name+"</option>";
                            option_iterator += 1;
                        });
                        append += "</select>";
                        select_iterator += 1;
                    });
                }
                append += "<input type='hidden' id='selected_category_id' name='"+CategorySelector.hidden_name+"' value='"+data.selected+"' />";
            } else if (typeof(data.error) != "undefined") {
                notifyUpdate(data.error, 'red');
            } else {
                notifyUpdate("An error occurred while update!", 'red');
            }
            append += "</span>";
            $("#category_selector_placer").after(append);
            $("#category_selector_placer").remove();
            
            if (data.success == 1) {

                CategorySelector.setSelectedAndCallCallback();
            }
        });
    },
    selectChange : function(level){
        var selected_option = jQuery("#"+CategorySelector.id+"_"+level+" option:selected");
        var id = jQuery(selected_option).val();
        // Ha valós kategóri van vagy a ---Válassz--- akkor a további al-kategóriákat el kell tüntetni.
        if ((id==='choose') || jQuery(selected_option).data('real')){
            // összes al-kategória eltüntetése
            jQuery("#category select").each(function(){
                if (jQuery(this).data('level')>level){
                    jQuery(this).remove();
                }
            });
        }
        
        if (!jQuery(selected_option).data('data-real') && (id!=='choose')){  // Valós kategóriánál (legalsó szint) és a Válassz opcióknál nem kell frissítés
            CategorySelector.show(id);
        } else {
            // Valós kategóriánál is le kell futni a callback-nak
            this.setSelectedAndCallCallback();
        }
    },
    setSelectedAndCallCallback : function(){
        var selected = jQuery("#"+CategorySelector.id+" select:last option:selected").val();
        if (selected === 'choose'){
            selected = jQuery("#"+CategorySelector.id+" select:last").prev().find("option:selected").val();
        }
        jQuery("#selected_category_id").val(selected);
        if (this.callback){
            this.callback();
        }
    }
}

function alertSelected(){
    alert("Selected Category ID: "+jQuery("#selected_category_id").val());
}





$(document).ready(function(){
    ProductCollect.validator();
    ProductCollect.changeType();
    ProductCollectType.validator();
    ProductCollectVariant.validator();
    ProductCollectPropertyName.validator();
    $("select.limited_multiple").change(function () {
      if($("select.limited_multiple option:selected").length > 2) {
          Popup.alert(lang('Hiba!'),lang("Maximálisan két érték választható"));
      }
    });
    if( typeof selectedVariant !== 'undefined' ) {
        var dataarray=selectedVariant.split(",");
        $("#id_multiselect_variant").val(dataarray);
    }
    if( typeof selectedProperty !== 'undefined' ) {
        var dataarray=selectedProperty.split(",");
        $("#id_multiselect_property").val(dataarray);
    }
});

