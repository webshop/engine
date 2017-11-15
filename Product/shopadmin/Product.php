
<?php
/**
 * Description of Product
 *
 * @author lethanin
 */

class Product extends ProductService{
    
    
    const NO_IMAGE_PATH = "/images/design/photo-empty-thumbnail128.png";
    
    /*******************************************************/
    /***************** PRODUCT COLLECT *********************/
    /*******************************************************/
    
    public function process_productCollectList(){
                
        $content = $this->loadTemplate('product_collect_list.html');
        
        $table = new HTMLTable();
        $table  ->setWidth('100%')
                ->setAlign('center')
                ->setAjaxSource('/Product/productCollectListData')
                ->setId('id_product_collect_list')
                ->setSearchFields([
                    'id' => lang('table_head_id'),
                    'name' => lang('table_head_name'),
                ]);
        $th = new HTMLTableRowHead();
        $th->addCell(lang('table_head_id'))->setSortableBy('id');
        $th->addCell(lang('table_head_name'))->setSortableBy('name');
        $th->addCell(lang('table_head_operations'))->setColspan(2)->setAlign('center');
        $table->addHeadRow($th);
        
        $content->replaceHTML("::module_id::",  MainMenuService::MENU_ITEM_PRODUCT_COLLECT);
        $content->replaceHTML("::history_service_limit::", HistoryService::_PARAMS_LIMIT);
        
        $content->replaceHTML('::table::', $table);
        $this->response->setContent($content);
    }
    
    
    public function process_productCollectListData(){
        
        $table = HTMLTable::create()->setSearchFields([
            'id' => lang('table_head_id'),
            'name' => lang('table_head_name'),
        ]); 
        $table->addFilter("deleted", 0);
        $product_collect_objs = ProductCollectObj::getInstance()->setSQLCalcFoundRows(true)->loadByQuery($table->getSQLQueryString());
        $table->setPaginatorTotalItemCount(ProductCollectObj::getInstance()->getSQLFoundRows());
        
        if(!empty($product_collect_objs)){
            foreach($product_collect_objs as $obj){
                $row = new HTMLTableRow();
                $row->setRowId('product_collect_'.$obj->getId());
                $row->addCell($obj->getId());
                $row->addCell($obj->getName());
                $row->addEditCell(url_for('@product_collect_edit?collect_id='.$obj->getId()));
                $row->addDeleteCell('ProductCollect.del('.$obj->getId().')');
                $table->addRow($row);
            }
        }
        $this->response->setJSONContent($table->getJSON());
    }
    
    public function process_editProductCollect(){
        $template = $this->loadTemplate('product_collect_form.html');
        $collect_id = $this->request->any('collect_id', 0);
       
        try{
            $product_collect = ProductCollectObj::getInstance()->load($collect_id);
            $template->hideSection('new');
        }  catch (Exception $e){
            $product_collect = new ProductCollectObj();
            $template->hideSection('edit');
        }
        $product_collect->loadAll();
        $template->replace('::category_id::', $product_collect->_getCategoryId());
        $template->replace("::option_status::",$product_collect->getStatusArrOptions($product_collect->getStatus()));
        $template->replace("::product_collect_type_name::",$product_collect->getTypeName());
        $template->replaceArray(Lib::modifyArrToReplace($product_collect->getValues()));
        $text_area_short = textarea_tag('product_collect[short_text]', $product_collect->getShortText(), ['rich' => true, 'tinymce_options' => ['width' => 500, 'height' => 100,'id'=>'product_collect_short_text'], 'embed_js_type'=> 'jQuery']);
        $text_area_long = textarea_tag('product_collect[long_text]', $product_collect->getLongText(), ['rich' => true, 'tinymce_options' => ['width' => 500, 'height' => 100,'id'=>'product_collect_long_text'], 'embed_js_type'=> 'jQuery']);
        $template->replace('::textarea_short_text::', $text_area_short);
        $template->replace('::textarea_long_text::', $text_area_long);
        $template->replace('::option_product_collect_type::',ProductCollectTypeObj::getTypesOptions($product_collect->getProductCollectTypeId()));
        $template->replaceHTML('::table::', $product_collect->getProductsTable());
        $template->replaceHTML('::tags::', $product_collect->getTagNamesJson());
        if (!empty($collect_id)){
            $image_manager = ImageManager::getInstance(ImageManager::TYPE_PRODUCT_COLLECT, $collect_id)
                                                        ->setAction('/Image/imageUpload');
            $template->replaceHTML('::ref_id::', $collect_id);
            $template->replaceHTML('::type::', ImageManager::TYPE_PRODUCT_COLLECT);
            $template->replaceHTML('::image_upload_form::',  $image_manager->getUploadForm());
        }
        return $template;
    }
    
    
    
    
    public function process_getProductCollectTypeProperties(){
        
        $product_collect_type_id = $this->request->any('product_collect_type_id', 0);
        $product_collect_id = $this->request->any('product_collect_id', 0);
        try{
            $product_collect_obj=ProductCollectObj::getInstance()->load($product_collect_id);
        }catch(DatabaseObjException $e){
            $product_collect_obj=new ProductCollectObj();
        }
        $properties=$product_collect_obj->getPropertiesEdit($product_collect_type_id);
        AjaxResponse::setCode(1);
        AjaxResponse::addParam("properties", $properties);
        $this->response->setJSONContent(AjaxResponse::getResponse());  
    }
    public function process_getProductCollectCheckboxValues(){
        
        $product_collect_property_name_id = $this->request->any('product_collect_property_name_id', 0);
        $product_collect_id = $this->request->any('product_collect_id', 0);
        try{
            $product_collect_obj=ProductCollectObj::getInstance()->load($product_collect_id);
        }catch(DatabaseObjException $e){
            $product_collect_obj=new ProductCollectObj();
        }
        $content=$product_collect_obj->getPropertiesCheckboxValues($product_collect_property_name_id);
        AjaxResponse::setCode(1);
        AjaxResponse::addParam("content", $content);
        $this->response->setJSONContent(AjaxResponse::getResponse());  
    }
    
    
    public function process_saveProductCollectCheckboxValues(){
        
        $checkbox_properties = $this->request->any('checkbox_properties', array());
        $product_collect_property_name_id = $this->request->any('product_collect_property_name_id', 0);
        $product_collect_id = $this->request->any('product_collect_id', 0);
        try{
            $product_collect_obj=ProductCollectObj::getInstance()->load($product_collect_id);
        }catch(DatabaseObjException $e){
            $product_collect_obj=new ProductCollectObj();
        }
        $names=$product_collect_obj->savePropertiesCheckbox($checkbox_properties, $product_collect_property_name_id);
        
        /** HISTORY **/
        $params=new HistoryParams();
        $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT);
        $params->setRefId($product_collect_obj->getId());        
        $params->setType(HistoryObj::_OPERATION_MODIFY);
        $text=lang("product_collect_set_chekcbox_values_history").": ".$product_collect_obj->getName()."<br/>";
        $text.=lang("product_collect_set_checkbox_values_setted_values_history").": ";
        $text.(!empty($names)?$names:lang("product_collect_set_checkbox_values_no_values_setted_history"));
        $params->setText($text);
        HistoryObj::addHistory($params);
        
        AjaxResponse::setCode(1);
        AjaxResponse::addParam("content", $names);
        $this->response->setJSONContent(AjaxResponse::getResponse());  
    }
    
    
    
    public function process_saveProductCollect(){
        $params = $this->request->post('product_collect', []);
        $properties=$this->request->any('property',[]);
        $tags=json_decode(SQL::unescape($this->request->post('tags', array())), true);
        $new=false;
        try{
            $product_collect = ProductCollectObj::getInstance()->load($params['id']);
        }  catch (DatabaseObjException $e){
            $product_collect = new ProductCollectObj();
            $new=true;
        }
        
        $valid = $product_collect->validate($params);
        
        if($valid){
            $handle = $params['handle'];
            unset($params['handle']);
            if($new){
                if($handle == Lib::strToUrl($params['name'])){
                    $handle = LiquidStandardFilters::handleize($params['name']);
                }
            }
            
            
            $product_collect->setValues($params);
            $product_collect->setHandle($handle);
            $product_collect->save();
            $product_collect->setTagIds($tags);
            $product_collect->saveTags();
            $product_collect->saveProperties($properties, $params["product_collect_type_id"]);
            $product_collect->generateProducts();
            
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT);
            $params->setRefId($product_collect->getId());
            if ($new){
                $params->setType(HistoryObj::_OPERATION_NEW);
                $text=lang("product_collect_history_create").": ".$product_collect->getName();                
            }else {
                $params->setType(HistoryObj::_OPERATION_MODIFY);
                $text=lang("product_collect_history_modify").": ".$product_collect->getName();                                
            }
            $params->setText($text);
            HistoryObj::addHistory($params);
        }
        //TULAJDONSÁG MENTÉS
        $res = $product_collect->getErrors(ObjValidator::RESULT_TYPE_FORM_VALIDATOR_FIELD, url_for('@product_collect_edit?collect_id='.$product_collect->getId()))->getResult();
        $this->response->setJSONContent($res);
    }
    
    public function process_deleteProductCollect(){
        $product_collect_id = $this->request->post('product_collect_id', 0);
        try{
            $product_collect = ProductCollectObj::getInstance()->load($product_collect_id);
            $product_collect->setDeleted(1);
            $product_collect->save();
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT);
            $params->setRefId($product_collect_id);       
            $params->setType(HistoryObj::_OPERATION_DELETE);
            $params->setText(lang("product_collect_delete_history").": ".$product_collect->getName());        
            HistoryObj::addHistory($params);
            
            AjaxResponse::setCode(1);
        }  catch (DatabaseObjException $e){
            AjaxResponse::setCode(0);
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    
    public function process_getProductCollectProducts(){
        $product_collect_id = $this->request->post('product_collect_id', 0);
        try{
            $product_collect = ProductCollectObj::getInstance()->load($product_collect_id);
            $product_collect->loadAllForProduct();
            $table = $product_collect->getProductsTableData();
        }  catch (DatabaseObjException $e){
            $table = 'szotyás';
        }
        $this->response->setJSONContent($table->getJSON());
    }
    
    public function process_editProductCollectProduct(){
        $content=$this->loadTemplate("product_form.html");
        $product_id = $this->request->post('product_id', 0);
        $product_collect_id=$this->request->post("product_collect_id",0);
        try{
            $product = ProductObj::getInstance()->load($product_id);
            $product->priceOverride();
            $image_manager = ImageManager::getInstance(ImageManager::TYPE_PRODUCT, $product->getId())
                                                    ->setAction('/Image/imageUpload');
            $upload_form=$image_manager->getUploadForm();
            $content->replaceHTML("::image_form::",$upload_form);
        }  catch (DatabaseObjException $e){
            $product = new ProductObj();
            $product->setProductCollectId($product_collect_id);
            $content->hideSection("kepek");
        }
        $content->replaceArray(Lib::modifyArrToReplace($product->getValues()));
        $content->replace("::option_status::",$product->getStatusArrOptions($product->getStatus()));
        $product_collect=ProductCollectObj::getInstance()->load($product_collect_id,false);
        $content->replace("::variants::",$product_collect->getProductVariants($product->getId()));
        //PRICE OVERRIDE
        $content->replace("::checked_price_overrided::",($product->getPriceOverrided()==1)?"checked":"");
        //PIECES
        $content->foreachSection("product_piece", $product->getPieces());
        
        AjaxResponse::setCode(1);
        AjaxResponse::addParam("form", $content);
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    public function process_saveProductCollectProduct(){
        $params = $this->request->post('product', []);
        $variants=$this->request->post('product_variants',[]);
        $pieces=$this->request->post('product_piece',[]);
        $unique_price=$this->request->post('unique',0);
        $new=false;
        try{
            $product = ProductObj::getInstance()->load($params['id']);
            Log::dump($product->getPrice());
        }  catch (DatabaseObjException $e){
            $product = new ProductObj();
            $new=true;
        }
        try{
            $product_price=ProductPriceObj::getInstance()->load($params['id']);
        }catch (DatabaseObjException $e){
            $product_price = new ProductPriceObj();
        }
        $product_clone=clone $product;
        $valid = $product->validate($params);
        if($valid){
            $product->setValues($params);
            $product->setDiscountPrice($params['price']);
            //ha egyedi, minden ár marad a régiben, csak itt lesz más
            if ($unique_price){
                $product->setCompareAtPrice($product_clone->getCompareAtPrice());
                $product->setPrice($product_clone->getPrice());
                $product->setDiscountPrice($product_clone->getDiscountPrice());
                $product->setDiscountId($product_clone->getDiscountId());
            } 
            $product->save();
            //ha egyedi létre kell hozni az új árat vagy épp módosítani
            if ($unique_price){
                $product_price->setProductId($product->getId());
                $product_price->setCompareAtPrice($params['compare_at_price']);
                $product_price->setPrice($params['price']);
                $product_price->setDiscountPrice($params['price']);
                $product_price->save();
            }
            
            //variánsok, darabok mentése
            $product->saveVariants($variants);
            $product->savePieces($pieces);
            $product->updatePiece();
            
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT);
            $params->setRefId($product->getProductCollectId());       
            if($new){
                $params->setType(HistoryObj::_OPERATION_NEW);
                $params->setText(lang("product_collect_product_history_create").": ".$product->getName());        
            }
            else{
                $params->setType(HistoryObj::_OPERATION_MODIFY);
                $params->setText(lang("product_collect_product_history_modify").": ".$product->getName());        
            }            
            HistoryObj::addHistory($params);
            
        }
        $res = $product->getErrors(ObjValidator::RESULT_TYPE_FORM_VALIDATOR_FIELD, null)->getResult();
        $this->response->setJSONContent($res);
    }
    
    
    public function process_deleteProductCollectProduct(){
        $product_id = $this->request->post('product_id', 0);
        try{
            $product = ProductObj::getInstance()->load($product_id);
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT);
            $params->setRefId($product->getProductCollectId());       
            $params->setType(HistoryObj::_OPERATION_DELETE);
            $params->setText(lang("product_collect_product_history_delete").": ".$product->getName());        
            HistoryObj::addHistory($params);            
            
            $product->delete();
            AjaxResponse::setCode(1);
        }  catch (DatabaseObjException $e){
            AjaxResponse::setCode(0);
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    public function process_reGenerateCollectProduct(){
        $product_collect_id = $this->request->post('product_collect_id', 0);
        try{
            $product_collect = ProductCollectObj::getInstance()->load($product_collect_id);
            $product_collect->reGenerateProducts();
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT);
            $params->setRefId($product_collect->getId());       
            $params->setType(HistoryObj::_OPERATION_MODIFY);
            $params->setText(lang("product_collect_product_regenerate_history").": ".$product_collect->getName());        
            HistoryObj::addHistory($params);      
            
            AjaxResponse::setCode(1);
        }  catch (DatabaseObjException $e){
            AjaxResponse::setCode(0);
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    
    /*******************************************************/
    /***************** PRODUCT COLLECT TYPE ****************/
    /*******************************************************/
    
    public function process_productCollectTypeList(){

        $content = $this->loadTemplate('product_collect_type_list.html');
        
        $table = new HTMLTable();
        $table  ->setWidth('100%')
                ->setAlign('center')
                ->setAjaxSource('/Product/productCollectTypeListData')
                ->setId('id_product_collect_type_list')
                ->setSearchFields([
                    'id' => lang('table_head_id'),
                    'name' => lang('table_head_name'),
                ]);
        
        
        
        $th = new HTMLTableRowHead();
        $th->addCell(lang('table_head_id'))->setSortableBy('id')->setWidth(200);
        $th->addCell(lang('table_head_name'))->setSortableBy('name');
        $th->addCell(lang('table_head_operations'))->setColspan(2)->setAlign('center');
        $table->addHeadRow($th);
            
        $content->replaceHTML('::table::', $table);
        $this->response->setContent($content);
    }
    public function process_productCollectTypeListData(){
        $table = new HTMLTable();
        $table->setSearchFields([
            'id' => lang('table_head_id'),
            'name' => lang('table_head_name'),
        ]);
        
        $product_collect_type_objs = ProductCollectTypeObj::getInstance()->setSQLCalcFoundRows(true)->loadByQuery($table->getSQLQueryString());
        $total = ProductCollectTypeObj::getInstance()->getSQLFoundRows();
        $table  ->setPaginatorTotalItemCount($total);
        
        if(!empty($product_collect_type_objs)){
            foreach($product_collect_type_objs as $obj){
                $row = new HTMLTableRow();
                $row->setRowId('product_collect_type_'.$obj->getId());
                $row->addCell($obj->getId());
                $row->addCell($obj->getName());
                $row->addEditCell(url_for('@product_collect_type_edit?type_id='.$obj->getId()));
                $row->addDeleteCell('ProductCollectType.del('.$obj->getId().')');
                $table->addRow($row);
            }
        }
        $this->response->setJSONContent($table->getJSON());
    }
    
    public function process_editProductCollectType(){
        $template = $this->loadTemplate('product_collect_type_form.html');
        
        $type_id = $this->request->any('type_id', 0);
        try{
            $product_type = ProductCollectTypeObj::getInstance()->load($type_id);
            $template->hideSection('new');
        }  catch (Exception $e){
            $product_type = new ProductCollectTypeObj();
            $template->hideSection('edit');
        }
        
        $template->replace('::id::', $product_type->getId());
        $template->replace('::name::', $product_type->getName());
        //VARIANT
        $selected_variant=SQL::query("SELECT product_collect_variant_id FROM product_collect_type_variant WHERE product_collect_type_id=".$product_type->getId(),"webshop_product")->fetchData("product_collect_variant_id");
        if (empty($selected_variant)){
            $selected_variant=array(0=>0);
        }
        $variants=SQL::query("SELECT id,name FROM product_collect_variant","webshop_product")->fetchSimpleData();
        $template->replace('::options_variant::',options_for_select($variants));
        $template->replace('::selected_variant::', implode(",",array_keys($selected_variant)));
        //PROPERTY
        $properties=SQL::query("SELECT id,name FROM product_collect_property_name","webshop_product")->fetchSimpleData();
        $template->replace('::options_property::',options_for_select($properties));
        $selected_property=SQL::query("SELECT product_collect_property_name_id FROM product_collect_type_property WHERE product_collect_type_id=".$product_type->getId(),"webshop_product")->fetchData("product_collect_property_name_id");
        if (empty($selected_property)){
            $selected_property=array(0=>0);
        }
        $template->replace('::selected_property::',implode(",",array_keys($selected_property)));
        
        return $template;
    }
    
    public function process_saveProductCollectType(){
        
        $params = $this->request->post('product_collect_type', []);
        $new=false;
        $product_collect_type_name_before="";
        try{
            $product_collect_type = ProductCollectTypeObj::getInstance()->load($params['id']);
            $product_collect_type_name_before=$product_collect_type->getName();
        }  catch (DatabaseObjException $e){
            $product_collect_type = new ProductCollectTypeObj();
            $new=true;
        }
        
        $valid = $product_collect_type->validate($params);
        
        if($valid){
            $product_collect_type->setValues($params);
            $product_collect_type->save();
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_TYPE);
            $params->setRefId($product_collect_type->getId());
            if ($new){
                $params->setType(HistoryObj::_OPERATION_NEW);
                $params->setText(lang("product_collect_type_history_create").": ".$product_collect_type->getName());
            }else {
                $params->setType(HistoryObj::_OPERATION_MODIFY);
                $text=lang("product_collect_type_history_modify");
                $text.="<br/>";
                $text.=lang('product_collect_type_history_name_before').": ";
                $text.=$product_collect_type_name_before.", ";
                $text.=lang('product_collect_type_history_name_after').": ";
                $text.=$product_collect_type->getName();
                $params->setText($text);
            }
            HistoryObj::addHistory($params);
            
        }
        
        $res = $product_collect_type->getErrors(ObjValidator::RESULT_TYPE_FORM_VALIDATOR_FIELD, url_for('@product_collect_type_edit?type_id='.$product_collect_type->getId()))->getResult();
        $this->response->setJSONContent($res);
    }
    
    public function process_saveProductCollectTypeVariant(){
        $product_collect_type_id=$this->request->post("product_collect_type_id",0);
        $product_collect_variant_type = $this->request->post('product_collect_variant_type', []);
        $product_collect_variant_type_no=$this->request->post("no_variant",0);
        $empty=false;
        SQL::query("DELETE FROM product_collect_type_variant WHERE product_collect_type_id=$product_collect_type_id","webshop_product");
        if (count($product_collect_variant_type)<3){
            if (!empty($product_collect_variant_type)) {
                $product_collect_variant_type_no=0;
            }
            //no variant
            if ($product_collect_variant_type_no) {
                $empty=true;
                $product_collect_variant_type=array();
                $product_collect_variant_type[0]=ProductCollectTypeVariantObj::NO_VARIANT_ID;
            }
            foreach($product_collect_variant_type as $inc=>$id){
                SQL::query("INSERT INTO product_collect_type_variant SET product_collect_type_id=$product_collect_type_id,product_collect_variant_id=$id","webshop_product");
            }
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_TYPE);
            $params->setRefId($product_collect_type_id);
            if (!$empty){
                $params->setType(HistoryObj::_OPERATION_MODIFY);
                $params->setText(lang("product_collect_type_variant_change_history_name").implode(",",$product_collect_variant_type));
            }else {
                $params->setType(HistoryObj::_OPERATION_MODIFY);
                $text=lang("product_collect_type_variant_change_history_name").lang("product_collect_type_variant_no_variant_history_name");                
                $params->setText($text);
            }
            HistoryObj::addHistory($params);
            
            AjaxResponse::setCode(1);
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());  
    }
    
    public function process_saveProductCollectTypeProperty(){
        $product_collect_type_id=$this->request->post("product_collect_type_id",0);
        $product_collect_property_type = $this->request->post('product_collect_property_type', []);
        SQL::query("DELETE FROM product_collect_type_property WHERE product_collect_type_id=$product_collect_type_id","webshop_product");
        foreach($product_collect_property_type as $inc=>$id){
            SQL::query("INSERT INTO product_collect_type_property SET product_collect_type_id=$product_collect_type_id,product_collect_property_name_id=$id","webshop_product");
        }
        
        /** HISTORY **/
        $params=new HistoryParams();
        $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_TYPE);
        $params->setRefId($product_collect_type_id);       
        $params->setType(HistoryObj::_OPERATION_MODIFY);
        $params->setText(lang("product_collect_type_property_change_history").implode(',',$product_collect_property_type));        
        HistoryObj::addHistory($params);
        
        AjaxResponse::setCode(1);
        $this->response->setJSONContent(AjaxResponse::getResponse());  
    }
    
    
    public function process_isDeletableProductCollectType(){
        $product_collect_type_id=$this->request->post("product_collect_type_id",0);
        try{
            $product_collect_type=ProductCollectTypeObj::getInstance()->load($product_collect_type_id);
            $product_collect=  ProductCollectObj::getInstance()->loadByQuerySimple("WHERE deleted=0 AND product_collect_type_id=$product_collect_type_id LIMIT 0,1");
            if (empty($product_collect)){
                $ajax_response=$this->delProductCollectType($product_collect_type_id);
            }else {
                AjaxResponse::setCode(2);
                $ajax_response=AjaxResponse::getResponse();
            }
        }  catch (Exception $e){
           AjaxResponse::setCode(0);
           $ajax_response=AjaxResponse::getResponse();
        }
        $this->response->setJSONContent($ajax_response);
    }
    
    public function process_delProductCollectType(){
        $product_collect_type_id=$this->request->get("product_collect_type_id",0);
        $ajax_response=$this->delProductCollectType($product_collect_type_id);
        $this->response->setJSONContent($ajax_response);
    }
    /**
     * 
     * @param type $product_collect_type_id
     * @return type array
     */
    private function delProductCollectType($product_collect_type_id){
        try{
            $product_collect_type=ProductCollectTypeObj::getInstance()->load($product_collect_type_id);
                        
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_TYPE);
            $params->setRefId($product_collect_type_id);       
            $params->setType(HistoryObj::_OPERATION_DELETE);
            $params->setText(lang("product_collect_type_delete_history").$product_collect_type->getName());        
            HistoryObj::addHistory($params);
            
            $product_collect_type->delete();
            
            AjaxResponse::setCode(1);
        }  catch (Exception $e){
            AjaxResponse::setCode(0);
        }
        return AjaxResponse::getResponse();
    }
    
    
    /*******************************************************/
    /***************** PRODUCT COLLECT VARIANT**************/
    /*******************************************************/
    
    public function process_productCollectVariantList(){

        $content = $this->loadTemplate('product_collect_variant_list.html');
            
        $table = new HTMLTable();
        $table  ->setWidth('100%')
                ->setAlign('center')
                ->setAjaxSource('/Product/productCollectVariantListData')
                ->setId('id_product_collect_variant_list')
                ->setSearchFields([
                    'id' => lang('table_head_id'),
                    'name' => lang('table_head_name'),
                ]);
        
        $th = new HTMLTableRowHead();
        $th->addCell(lang('table_head_id'))->setSortableBy('id')->setWidth(100);
        $th->addCell(lang('table_head_name'))->setSortableBy('name');
        $th->addCell(lang('table_head_values'));
        $th->addCell(lang('table_head_operations'))->setColspan(2)->setAlign('center');
        $table->addHeadRow($th);
        
        $content->replaceHTML('::table::', $table);
        $this->response->setContent($content);
    }
    
    public function process_productCollectVariantListData(){
        $table = new HTMLTable();
        $table  ->setSearchFields([
            'id' => lang('table_head_id'),
            'name' => lang('table_head_name'),
        ]);
        
        $product_collect_variant_objs = ProductCollectVariantObj::getInstance()->setSQLCalcFoundRows(true)->loadByQuery($table->getSQLQueryString());
        $total = ProductCollectVariantObj::getInstance()->getSQLFoundRows();
        $table->setPaginatorTotalItemCount($total);
        
        if(!empty($product_collect_variant_objs)){
            ProductCollectVariantObj::loadVariantObjsValues($product_collect_variant_objs);
            foreach($product_collect_variant_objs as $obj){
                $row = new HTMLTableRow();
                $row->setRowId('product_collect_variant_'.$obj->getId());
                $row->addCell($obj->getId());
                $row->addCell($obj->getName());
                $row->addCell($obj->getVariantValuesImplode());
                $row->addEditCell(url_for('@product_collect_variant_edit?variant_id='.$obj->getId()));
                $row->addDeleteCell('ProductCollectVariant.del('.$obj->getId().')');
                $table->addRow($row);
            }
        }
        $this->response->setJSONContent($table->getJSON());
    }
    
    public function process_editProductCollectVariant(){
        $template = $this->loadTemplate('product_collect_variant_form.html');        
        $variant_id = $this->request->any('variant_id', 0);
        try{
            $product_variant = ProductCollectVariantObj::getInstance()->load($variant_id);
            $template->hideSection('new');
        }  catch (Exception $e){
            $product_variant = new ProductCollectVariantObj();
            $template->hideSection('edit');
        }
        
        $template->replace('::id::', $product_variant->getId());
        $template->replace('::name::', $product_variant->getName());
        
        $table = new HTMLTable();
        $table->setWidth('100%')->setAlign('center');
        $table->setId("id_sortable_Table");
        $table->setAjaxSource('/Product/getProductCollectVariantValue');
        $table->setAjaxSourceParams([
            'product_collect_variant_id' => $product_variant->getId()
        ]);
        $table->setSearchFields([
            'id' => lang('table_head_id'),
            'name' => lang('table_head_name'),
        ]);
        $th = new HTMLTableRowHead();
        $th->addCell(lang('table_head_id'))->setSortableBy('id');
        $th->addCell(lang('table_head_name'))->setSortableBy('name');
        $th->addCell(lang('table_head_operations'))->setColspan(2)->setAlign('center');
        $table->addHeadRow($th);
        
        $template->replaceHTML('::table::', $table);
        
        return $template;
    }
    
    public function process_saveProductCollectVariant(){
        
        $params = $this->request->post('product_collect_variant', []);
        $new=true;
        $product_collect_variant_name_before="";
        try{
            $product_collect_variant = ProductCollectVariantObj::getInstance()->load($params['id']);
            $product_collect_variant_name_before=$product_collect_variant->getName();
            $new=false;
        }  catch (DatabaseObjException $e){
            $product_collect_variant = new ProductCollectVariantObj();
        }
        
        $valid = $product_collect_variant->validate($params);
        
        if($valid){
            $product_collect_variant->setValues($params);
            $product_collect_variant->save();
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_VARIANT);
            $params->setRefId($product_collect_variant->getId());
            if ($new){
                $params->setType(HistoryObj::_OPERATION_NEW);
                $params->setText(lang("product_collect_variant_history_create").": ".$product_collect_variant->getName());
            }else {
                $params->setType(HistoryObj::_OPERATION_MODIFY);
                $text=lang("product_collect_variant_history_modify");
                $text.="<br/>";
                $text.=lang('product_collect_variant_history_name_before').": ";
                $text.=$product_collect_variant_name_before.", ";
                $text.=lang('product_collect_variant_history_name_after').": ";
                $text.=$product_collect_variant->getName();
                $params->setText($text);
            }
            HistoryObj::addHistory($params);
            
        }
        
        $res = $product_collect_variant->getErrors(ObjValidator::RESULT_TYPE_FORM_VALIDATOR_FIELD, url_for('@product_collect_variant_edit?variant_id='.$product_collect_variant->getId()))->getResult();
        $this->response->setJSONContent($res);
    }
    
    
    
    public function process_isDeletableProductCollectVariant(){
        $product_collect_variant_id=$this->request->post("product_collect_variant_id",0);
        try{
            $product_collect_variant=ProductCollectVariantObj::getInstance()->load($product_collect_variant_id);
            $product_collect_type_variant= ProductCollectTypeVariantObj::getInstance()->loadByQuerySimple("WHERE product_collect_variant_id=$product_collect_variant_id LIMIT 0,1");
            if (empty($product_collect)){
                $ajax_response=$this->delProductCollectVariant($product_collect_variant_id);
            }else {
                AjaxResponse::setCode(2);
                $ajax_response=AjaxResponse::getResponse();
            }
        }  catch (Exception $e){
           AjaxResponse::setCode(0);
           $ajax_response=AjaxResponse::getResponse();
        }
        $this->response->setJSONContent($ajax_response);
    }
    
    public function process_delProductCollectVariant(){
        $product_collect_variant_id=$this->request->get("product_collect_variant_id",0);
        $ajax_response=$this->delProductCollectVariant($product_collect_variant_id);
        
        $this->response->setJSONContent($ajax_response);
    }
    /**
     * 
     * @param type $product_collect_variant_id
     * @return type array
     */
    private function delProductCollectVariant($product_collect_variant_id){
        try{
            $product_collect_variant=ProductCollectVariantObj::getInstance()->load($product_collect_variant_id);
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_VARIANT);
            $params->setRefId($product_collect_variant_id);       
            $params->setType(HistoryObj::_OPERATION_DELETE);
            $params->setText(lang("product_collect_variant_delete_history").": ".$product_collect_variant->getName());        
            HistoryObj::addHistory($params);
            
            $product_collect_variant->delete();
            SQL::query("DELETE FROM product_variant_value WHERE product_collect_variant_value_id=$product_collect_variant_id", 'webshop_product');
            AjaxResponse::setCode(1);
        }  catch (Exception $e){
            AjaxResponse::setCode(0);
        }
        return AjaxResponse::getResponse();
    }
    /*******************************************************/
    /***************** PRODUCT COLLECT VARIANT VALUE********/
    /*******************************************************/
    
    public function process_getProductCollectVariantValue(){
        $table = HTMLTable::create()->setSearchFields([
            'id' => lang('table_head_id'),
            'name' => lang('table_head_name'),
        ]);
        $table->addFilter('product_collect_variant_id', $this->request->post("product_collect_variant_id",0));
        
        $product_collect_variant_values=SQL::query("SELECT SQL_CALC_FOUND_ROWS id,name FROM product_collect_variant_value {$table->getSQLQueryString()}","webshop_product")->fetchSimpleData();
        $table->setPaginatorTotalItemCount(SQL::query('SELECT FOUND_ROWS()', 'webshop_product')->fetchValue(0));
        
        foreach($product_collect_variant_values as $id=>$name){
            $row = new HTMLTableRow();
            $row->setClass("sortable_row");
            $row->setRowId('product_collect_variant_value_row_'.$id);
            $row->addCell($id);
            $row->addCell($name)->setId("product_collect_variant_value_name_".$id);
            $row->addEditCell("javascript:void(0)",'ProductCollectVariant.editValue('.$id.')');
            $row->addDeleteCell('ProductCollectVariant.delValue('.$id.')');
            $table->addRow($row);
        }
        $this->response->setJSONContent($table->getJSON());
    }
    
    public function process_saveProductCollectVariantValue(){
        $product_collect_variant_id=$this->request->post("product_collect_variant_id",0);
        $params = $this->request->post('product_collect_variant_value', []);
        $new = true;
        $product_variant_value_before_change="";
        try{
            $product_collect_variant_value = ProductCollectVariantValueObj::getInstance()->load($params['id']);
            $new=false;
            $product_variant_value_before_change=$product_collect_variant_value->getName();
        }  catch (DatabaseObjException $e){
            $product_collect_variant_value = new ProductCollectVariantValueObj();
        }
        $product_collect_variant_value->setProductCollectVariantId($product_collect_variant_id);
        $valid = $product_collect_variant_value->validate($params);
        
        if($valid){
            $product_collect_variant_value->setValues($params);
            $product_collect_variant_value->save();
            AjaxResponse::setCode(1);
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_VARIANT);
            $params->setRefId($product_collect_variant_id);
            if ($new){
                $params->setType(HistoryObj::_OPERATION_NEW);
                $params->setText(lang("product_collect_variant_value_history_create").": ".$product_collect_variant_value->getName());
            }else {
                $params->setType(HistoryObj::_OPERATION_MODIFY);
                $text=lang("product_collect_variant_value_history_modify");
                $text.="<br/>";
                $text.=lang('product_collect_variant_value_history_name_before').": ";
                $text.=$product_variant_value_before_change.", ";
                $text.=lang('product_collect_variant_value_history_name_after').": ";
                $text.=$product_collect_variant_value->getName();
                $params->setText($text);
            }
            HistoryObj::addHistory($params);
            
        }else {
            AjaxResponse::setCode(0);
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    
    public function process_setProductCollectVariantValueSorted(){
        $product_collect_variant_value_rows=$this->request->post("product_collect_variant_value_row",array());
        $product_collect_variant_id=$this->request->post("product_collect_variant_id",0);
        foreach($product_collect_variant_value_rows as $inc=>$val){
            $sortorder=$inc+1;
            SQL::query("UPDATE product_collect_variant_value SET sortorder=$sortorder WHERE id=$val AND product_collect_variant_id=$product_collect_variant_id","webshop_product");
        }
        AjaxResponse::setCode(1);
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    
    
    public function process_delProductCollectVariantValue(){
        $product_collect_variant_value_id=$this->request->post("product_collect_variant_value_id",0);
        try{
            $product_collect_variant_value = ProductCollectVariantValueObj::getInstance()->load($product_collect_variant_value_id);
                        
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_VARIANT);
            $params->setRefId($product_collect_variant_value_id);       
            $params->setType(HistoryObj::_OPERATION_DELETE);
            $params->setText(lang("product_collect_variant_value_delete_history").$product_collect_variant_value->getName());        
            HistoryObj::addHistory($params);
            
            $product_collect_variant_value->delete();            
            AjaxResponse::setCode(1);
        }catch(DatabaseObjException $e){
            AjaxResponse::setCode(0);
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    /*******************************************************/
    /********  PRODUCT COLLECT PROPERTY NAME   *************/
    /*******************************************************/    
    
    
    public function process_productCollectPropertyNameList(){

        $content = $this->loadTemplate('product_collect_property_name.html');
            
        $table = new HTMLTable();
        $table  ->setWidth('100%')
                ->setAlign('center')
                ->setAjaxSource('/Product/productCollectPropertyNameListData')
                ->setId('id_product_collect_property_name')
                ->setSearchFields([
                    'id' => lang('table_head_id'),
                    'name' => lang('table_head_name'),
                ]);
        
        $th = new HTMLTableRowHead();
        $th->addCell(lang('table_head_id'))->setSortableBy('id');
        $th->addCell(lang('table_head_name'))->setSortableBy('name');
        $th->addCell(lang('table_head_type'))->setSortableBy('type');
        $th->addCell(lang('table_head_operations'))->setColspan(2)->setAlign('center');
        $table->addHeadRow($th);
        
        $content->replaceHTML('::table::', $table);
        $this->response->setContent($content);
    }
    public function process_productCollectPropertyNameListData(){
        $table = HTMLTable::create()->setSearchFields([
            'id' => lang('table_head_id'),
            'name' => lang('table_head_name'),
        ]);
        
        $product_collect_property_objs = ProductCollectPropertyNameObj::getInstance()->setSQLCalcFoundRows(true)->loadByQuery($table->getSQLQueryString());
        $table->setPaginatorTotalItemCount(ProductCollectPropertyNameObj::getInstance()->getSQLFoundRows());
        
        if(!empty($product_collect_property_objs)){
            foreach($product_collect_property_objs as $obj){
                $row = new HTMLTableRow();
                $row->setRowId('product_collect_property_name_'.$obj->getId());
                $row->addCell($obj->getId());
                $row->addCell($obj->getName());
                $row->addCell($obj->getPropertyTypeName());
                $row->addEditCell(url_for('@product_collect_property_name_edit?property_name_id='.$obj->getId()));
                $row->addDeleteCell('ProductCollectPropertyName.del('.$obj->getId().')');
                $table->addRow($row);
            }
        }
        $this->response->setJSONContent($table->getJSON());
    }
    
    public function process_editProductCollectPropertyName(){
        $template = $this->loadTemplate('product_collect_property_name_form.html');
        $property_name_id = $this->request->any('property_name_id', 0);
        try{
            $product_property_name = ProductCollectPropertyNameObj::getInstance()->load($property_name_id);
            $template->hideSection('new');
        }  catch (Exception $e){
            $product_property_name= new ProductCollectPropertyNameObj();
            $template->hideSection('edit');
        }
        if ($product_property_name->getType()== ProductCollectPropertyNameObj::PROPERTY_TYPE_INPUT){
            $section="no_edit";
        }else {
            $section="edit";
        }
        $template->switchSection("values", $section);
        $template->replace('::id::', $product_property_name->getId());
        $template->replace('::name::', $product_property_name->getName());
        $template->replace('::option_property_type::',$product_property_name->getPropertyTypesOptions($product_property_name->getType()));
        
        $table = new HTMLTable();
        $table  ->setWidth('100%')
                ->setAlign('center')
                ->setId("id_product_collect_property_name")
                ->setAjaxSource('/Product/getProductCollectPropertyNameSelectValue')
                ->setAjaxSourceParams([
                    'product_collect_property_name_id' => $product_property_name->getId()
                ])
                ->setSearchFields([
                    'id' => lang('table_head_id'),
                    'name' => lang('table_head_name'),
                ]);
                
        $th = new HTMLTableRowHead();
        $th->addCell(lang('table_head_id'))->setSortableBy('id')->setWidth(100);
        $th->addCell(lang('table_head_name'))->setSortableBy('name');
        $th->addCell(lang('table_head_operations'))->setColspan(2)->setAlign('center');
        $table->addHeadRow($th);
        
        $template->replaceHTML('::table::',$table);
        return $template;
    }
    
    public function process_saveProductCollectPropertyName(){
        
        $params = $this->request->post('product_collect_property_name', []);
        $new=true;
        $product_property_name_before_change="";
        try{
            $product_collect_property_name = ProductCollectPropertyNameObj::getInstance()->load($params['id']);
            //input vs. Select & Checkbox
            $product_collect_property_name->delSelectCheckboxValues($params["type"]);
            $new=false;
            $product_property_name_before_change=$product_collect_property_name->getName();
        }  catch (DatabaseObjException $e){
            $product_collect_property_name = new ProductCollectPropertyNameObj();
        }   
        $valid = $product_collect_property_name->validate($params);
        if($valid){
            $product_collect_property_name->setValues($params);
            $product_collect_property_name->save();
            
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_PROPERTY);
            $params->setRefId($product_collect_property_name->getId());
            if ($new){
                $params->setType(HistoryObj::_OPERATION_NEW);
                $params->setText(lang("product_collect_property_history_name_new").": ".$product_collect_property_name->getName());
            }else {
                $params->setType(HistoryObj::_OPERATION_MODIFY);
                $text=lang("product_collect_property_history_name_modify");   
                $text.="<br/>";
                $text.=lang("product_collect_property_history_name_before_change").": ".$product_property_name_before_change.", ";
                $text.=lang("product_collect_property_history_name_after_change").":".$product_collect_property_name->getName();
                $params->setText($text);
            }
            HistoryObj::addHistory($params);
            
        }
        $res = $product_collect_property_name->getErrors(ObjValidator::RESULT_TYPE_FORM_VALIDATOR_FIELD, url_for("@product_collect_property_name_edit?property_name_id=".$product_collect_property_name->getId()))->getResult();
        $this->response->setJSONContent($res);
    }
    
    public function process_isDeletableProductCollectPropertyName(){
        $product_collect_property_name_id=$this->request->post("product_collect_property_name_id",0);
        try{
            $product_collect_property_name=ProductCollectPropertyNameObj::getInstance()->load($product_collect_property_name_id);
            $product_collect_property= ProductCollectPropertyObj::getInstance()->loadByQuerySimple("WHERE product_collect_property_name_id=$product_collect_property_name_id LIMIT 0,1");
            if (empty($product_collect_property)){
                $ajax_response=$this->delProductCollectPropertyName($product_collect_property_name_id);
            }else {
                AjaxResponse::setCode(2);
                $ajax_response=AjaxResponse::getResponse();
            }
        }  catch (Exception $e){
           AjaxResponse::setCode(0);
           $ajax_response=AjaxResponse::getResponse();
        }
        $this->response->setJSONContent($ajax_response);
    }
    
    public function process_delProductCollectPropertyName(){
        $product_collect_property_name_id=$this->request->get("product_collect_property_name_id",0);
        $ajax_response=$this->delProductCollectPropertyName($product_collect_property_name_id);
        $this->response->setJSONContent($ajax_response);
    }
    /**
     * 
     * @param type $product_collect_property_name_id
     * @return type array
     */
    private function delProductCollectPropertyName($product_collect_property_name_id){
        try{
            $product_collect_property_name=ProductCollectPropertyNameObj::getInstance()->load($product_collect_property_name_id);
            $product_collect_property_name->delete();
            AjaxResponse::setCode(1);
        }  catch (Exception $e){
            AjaxResponse::setCode(0);
        }
        return AjaxResponse::getResponse();
    }
    
    public function process_getProductCollectPropertyNameSelectValue(){
        $table = HTMLTable::create()->setSearchFields([
            'id' => lang('table_head_id'),
            'name' => lang('table_head_name'),
        ]);
        $table->addFilter('product_collect_property_name_id', $this->request->post("product_collect_property_name_id",0));
        
        $product_collect_variant_values=SQL::query("SELECT SQL_CALC_FOUND_ROWS id,name FROM product_collect_property_select_value ".$table->getSQLQueryString(),"webshop_product")->fetchSimpleData();
        $table->setPaginatorTotalItemCount(SQL::query('SELECT FOUND_ROWS()', 'webshop_product')->fetchValue(0));
        if(!empty($product_collect_variant_values)){
            foreach($product_collect_variant_values as $id=>$name){
                $row = new HTMLTableRow();
                $row->setClass("sortable_row");
                $row->setRowId('product_collect_property_name_select_value_row_'.$id);
                $row->addCell($id);
                $row->addCell($name)->setId("product_collect_property_name_select_value_name_".$id);
                $row->addEditCell("javascript:void(0)",'ProductCollectPropertyName.editValue('.$id.')');
                $row->addDeleteCell('ProductCollectPropertyName.delValue('.$id.')');
                $table->addRow($row);
            }
        }
        
        $this->response->setJSONContent($table->getJSON());
    }
    
    public function process_saveProductCollectPropertyNameValue(){
        $product_collect_property_name_id=$this->request->post("product_collect_property_name_id",0);
        $params = $this->request->post('product_collect_property_name_select_value', []);
        $product_property_name_before_change_value="";
        $new=false;
        try{
            $product_collect_property_name_value = ProductCollectPropertySelectValueObj::getInstance()->load($params['id']);
            $product_property_name_before_change_value=$product_collect_property_name_value->getName();
        }  catch (DatabaseObjException $e){
            $product_collect_property_name_value = new ProductCollectPropertySelectValueObj();
            $new=true;
        }
        $product_collect_property_name_value->setProductCollectPropertyNameId($product_collect_property_name_id);
        $valid = $product_collect_property_name_value->validate($params);
        
        if($valid){
            $product_collect_property_name_value->setValues($params);
            $product_collect_property_name_value->save();
            AjaxResponse::setCode(1);
            
            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_PROPERTY);
            $params->setRefId($product_collect_property_name_id);
            if ($new){
                $params->setType(HistoryObj::_OPERATION_NEW);
                $params->setText(lang("product_collect_property_name_value_history_new").": ".$product_collect_property_name_value->getName());
            }else {
                $params->setType(HistoryObj::_OPERATION_MODIFY);
                $text=lang("product_collect_property_name_value_history_modify");   
                $text.="<br/>";
                $text.=lang("product_collect_property_name_value_history_before_change").": ".$product_property_name_before_change_value.", ";
                $text.=lang("product_collect_property_name_value_history_after_change").":".$product_collect_property_name_value->getName();
                $params->setText($text);
            }
            HistoryObj::addHistory($params);
            
        }else {
            AjaxResponse::setCode(0);
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    public function process_delProductCollectPropertyNameValue(){
        $product_collect_property_name_id=$this->request->post("product_collect_property_name_value_id",0);
        try{
            $product_collect_property_name_value = ProductCollectPropertySelectValueObj::getInstance()->load($product_collect_property_name_id);
            $product_collect_property_name_value->delete();
            AjaxResponse::setCode(1);
        }catch(DatabaseObjException $e){
            AjaxResponse::setCode(0);
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }    
    
    
    
    /*********** CATEGORY *************/
    /*********** ******** *************/
    /*********** CATEGORY *************/
    
    
    
    public function process_categoryList($auth_user) {
        $category_id = $this->request->get('category_id', 0);
        if ($category_id>0){
            $category = ProductCategoryObj::getInstance()->load($category_id, false);
        }
        // Ha nem található kategória vagy nem volt megadva, akkor a főoldalt hozzuk be
        if (empty($category)){
            $category = ProductCategoryObj::getInstance()->loadByQuerySimple("WHERE `left`=1 LIMIT 1");
        }

        if ($category->getLevel()==ProductCategoryObj::LEVEL_MIDDLE){
            $category = $category->getParentObj();
        }
        if ($category->getLeft()==1){
            // főoldal, legnépszerűbb 5-6 kategória
            $content = ProductCategory::getInstance()->listMainPage();
            $content->replace('::category_id::', $category->getId());
        } else if ($category->hasChild()){
            // vannak al-kategóriák
            $content = ProductCategoryObj::getInstance()->listSubCategories($category);
        } else {

            $this->response->setTitle(lang('store_products_title'));
            $manager_instance=  StoreProductListManager::getInstance();
            $this->setSessionData(self::_SESSION_INDEX_PAGINATOR_PARAMS_KAT_ID, $category_id);
            $params=$this->getListParamsInstance(1);
            $params->setSearchText('');
            $manager_instance->setPaginator($params);
            $content=$manager_instance->listProducts();
        }
        return $content;
    }

    public function process_loadCategoryEditForm(){
        $return = [
            'form' => '',
            'message' => 'An error occured!',
        ];
        $category_id = $this->request->any('category_id', 0);
        $parent_id = $this->request->any('parent_id', 0);
        
        try {
            $category_obj = ProductCategoryObj::getInstance()->load($category_id);
        } catch (Exception $ex) {
            $category_obj = new ProductCategoryObj();
            $category_obj->setParentId($parent_id);
            $return['message'] = $ex->getMessage();
        }
        
        $form = $this->loadTemplate('category_edit_form.html');
        $form->replace('::category_id::', $category_obj->getId());
        $form->replace('::parent_id::', $category_obj->getParentId());
        $form->replace('::category_name::', $category_obj->getName());
        $form->replace('::sort_by_select_options::', options_for_select(ProductCategoryObj::getSortByArray(),$category_obj->getDefaultSortBy()));

        $return['form'] = $form;
        $return['message'] = 'Oksi';
            
        
        $this->response->setJSONContent($return);
    }

    public function process_categoryListToAdmin() {
        $selected = $this->request->get('selected', 0);
        return ProductCategory::getInstance()->listToAdmin($selected);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param AuthUser $auth_user
     */
    public function process_categorySave() {
        $category = $this->request->any('category',[]);
        $ret = array("success" => 0);
        $error = "";
        
        if (is_array($category)){
            try{
                $saved_category = ProductCategory::getInstance()->save($category);
                $ret['category_id'] = $saved_category->getId();
                $ret['tree'] = ProductCategory::getInstance()->displayTreeAdmin(false);
            } catch (Exception $ex){
                $error = $ex->getMessage();
            }
        } else {
            $error = "Bad parameters";
        }

        if (empty($error)) {
            $ret['success'] = 1;
        } else {
            $ret['error'] = $error;
        }

        $this->response->setJSONContent($ret);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param AuthUser $auth_user
     */
    public function process_categoryDelete() {
        $store_category_id = $this->request->any("category_id", 0);
        $del_result = ProductCategory::getInstance()->delete($store_category_id);
        $del_result['tree'] = ProductCategory::getInstance()->displayTreeAdmin(false);
        $this->response->setJSONContent($del_result);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param AuthUser $auth_user
     */
    public function process_updateOrder() {
        if(self::getStoreAdminInstance()->hasRight(ACLBaseStoreAdmin::MODULE_ADMIN_PANEL_CATEGORY,ACLBaseStoreAdmin::OPERATION_EDIT)){
            $ret = array("success" => 0);
            $error = "";

            $moved_id = $this->request->any('moved_id', 0);
            $prev_id = $this->request->any('prev_id', 0);
            $next_id = $this->request->any('next_id', 0);
            $direction = $this->request->any('direction', '');

            if (!empty($moved_id) && isset($prev_id) && isset($next_id)) {
                if ($direction == ProductCategory::DIRECTION_UP){
                    $error = ProductCategory::getInstance()->moveBackward($moved_id, $next_id);
                } elseif ($direction == ProductCategory::DIRECTION_DOWN){
                    $error = ProductCategory::getInstance()->moveForward($moved_id, $prev_id);
                } else {
                    $error = "Wrong direction constant!";
                }
            } else {
                $error = "Bad parameters";
            }

            if (empty($error)) {
                $ret['success'] = 1;
            } else {
                $ret['error'] = $error;
            }
        }else {
            $ret=array("result"=>0,"result_msg"=>lang("dealmanager_operation_has_no_right"));
        }
        $this->response->setJSONContent($ret);
    }
    
    /**
     *
     * @param Request $request
     * @param Response $response
     * @param AuthUser $auth_user
     */
    public function process_categoryImageUpload() {
        $error = "";
        $result = array(
            'success' => false,
            'data' =>
            array(
                'thumbnail' => '',
                'photoId' => 0,
            ),
        );

        $filename=Lib::ayhamTempnam(sys_get_temp_dir(),"image_upload");
        Lib::setLayout();
        if (empty ($_FILES['qqfile']['tmp_name'])) {
            // Ha üres a $_FILES tömb, akkor így lehet kinyerni a feltöltött file-t
            file_put_contents($filename, file_get_contents("php://input"));
            $image_result = Image::uploadImage($filename, Application::BRANCH_WWW, Image::_TYPE_STORE_CATEGORY,$this->auth_user->getUserId());
        } else {
            $image_result = Image::uploadImage($_FILES['qqfile']['tmp_name'], Application::BRANCH_WWW, Image::_TYPE_STORE_CATEGORY,$this->auth_user->getUserId());
        }

        $category_id = $this->request->get('category_id', 0);
        $category = StoreCategoryObj::getInstance()->load($category_id, false);
        if (!empty($category)){
            if ($image_result['status'] == 2) {
                $result['success'] = true;
                $result['data']['thumbnail'] = Image::getImage($image_result['image']->getImageId(), Image::_TYPE_STORE_CATEGORY, Image::_IMAGE_TYPE_STORE_CATEGORY_THUMB, false);
                $result['data']['photoId'] = $image_result['image']->getImageId();

                // Előző kép törlése, ha létezik
                if ($category->getImageId()>0){
                    Image::deleteImage($category->getImageId());
                }
                // Kép ID és útvonal mentése a store_category táblába
                $category->setImageId($image_result['image']->getImageId());
                $category->setImageURL($result['data']['thumbnail']);
                $category->save();
            } else {
                $error = $image_result['error'];
            }
        } else {
            $error = "Bad category";
        }

        if (!empty($error)){
            $result['data']['message'] = $error;
            $result['category_id'] = $category_id;
        }
        @unlink($filename);
        $this->response->setJSONContent($result);
    }
    public function process_getCategorySelector(){
        $category_id = $this->request->get('category_id', 0);
        $expand = ($this->request->get('expand','') === 'true') ? TRUE : FALSE;
        $res = ProductCategory::getInstance()->getCategorySelectorData($category_id, $expand);
        $this->response->setJSONContent($res);
    }
    
    public function process_changeProductPiece(){
        $store_id=$this->request->any("store_id",0);
        $product_id=$this->request->any("product_id",0);
        $piece=$this->request->any("piece",0);
        try{
            $product_obj=  ProductObj::getInstance()->load($product_id);
            $product_obj->savePieces(array($store_id=>$piece));
            $product_obj->updatePiece();
            AjaxResponse::setCode(1);
        }
        catch(Exception $e){
            AjaxResponse::setCode(0);
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
}