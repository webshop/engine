<?php
class Product extends ProductService{
    
    
    
    const SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_NUM                              =   "product_collect_list_param_num";
    const SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_RECORD                           =   "product_collect_list_param_record";
    const SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_ORDER_TYPE                       =   "product_collect_list_param_order_type";
    const SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_LIST_TYPE                        =   "product_collect_list_param_list_type";
    const SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_CATEGORY_ID                      =   "product_collect_list_param_category_id";
    const SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM                      =   "product_collect_list_param_search_term";
    const SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_FAVOURITE                        =   "product_collect_list_param_favourite";
    const SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_DISCOUNT_PRODUCT_COLLECT_IDS     =   "product_collect_list_param_discount_product_collect_ids";
    
    
   
    public function process_listProductCollect(){
        $content=$this->loadTemplate("product_collect_list.html");
        $list_manager=$this->getListManagerInstance(true);
        $content->replace("::container::", $list_manager->getItems());
        return $content;
    }
    public function process_listProductCollectAjax(){

        $list_manager=$this->getListManagerInstance();
        $content=$list_manager->getItems();
        AjaxResponse::setCode(1);
        AjaxResponse::addParam("content", $content);
        $this->response->setJSONContent(AjaxResponse::getResponse()); 
    }
    
    /**
     * Lapozóhoz szükséges paraméterek
     * @param type $from_session
     * @return \ProductCollectObjListManager
     */
    private function getListManagerInstance($from_session=false){
        
        $list_manager=new ProductCollectObjListManager();
        $num=$this->request->any("num", ProductCollectObjListManager::DEFAULT_PAGE_NUM);
        $record=$this->request->any("record", 0);
        $order_type=$this->request->any("order_type",ProductCollectObjListManager::ORDER_TYPE_PRICE_ASC);
        $list_type=$this->request->any("list_type", ProductCollectObjListRender::OPTIONS_LIST_TYPE_LIST);

        //SESSION-ből
        if ($from_session){
            if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_NUM)){
                $num=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_NUM, ProductCollectObjListManager::DEFAULT_PAGE_NUM);
            }
            if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_RECORD)){
                $record=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_RECORD,0);
            }
            if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_ORDER_TYPE)){
                $order_type=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_ORDER_TYPE,ProductCollectObjListManager::ORDER_TYPE_PRICE_ASC);
            }
            if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_LIST_TYPE)){
                $list_type=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_LIST_TYPE,ProductCollectObjListRender::OPTIONS_LIST_TYPE_LIST);
            }
            
        }else {
            //OFFSET VIZSGÁLAT
            if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_NUM)){
                $num_session=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_NUM, ProductCollectObjListManager::DEFAULT_PAGE_NUM);
                if ($num_session!=$num){
                    $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_RECORD, 0);
                    $record=0;
                }else {
                    $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_RECORD, $record);
                }
            }
            $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_NUM, $num);
            $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_ORDER_TYPE, $order_type);
            $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_LIST_TYPE, $list_type);
        }
        $list_manager->setLimit($num);
        $list_manager->setOffset($record);
        $list_manager->setOrderType($order_type);
        $list_manager->addRenderOption(ProductCollectObjListRender::OPTIONS_KEY_LIST_TYPE,  $list_type);
        $list_manager->addSQLCondition("status","1");
        $list_manager->addSQLCondition("deleted","0");
        
        //CATEGORY
        if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_CATEGORY_ID)){
            $category_ids=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_CATEGORY_ID,array());
            if (!empty($category_ids)){
                $list_manager->addSQLCondition("category_id",$category_ids);
            }
        }
        //SEARCH
        if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM)){
            $search_term=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM,'');
            if (!empty($search_term)){
                $list_manager->addSQLSearchCondition("name",$search_term);
            }
        }
        //FAVOURITES
        if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_FAVOURITE)){
            $product_collect_ids=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_FAVOURITE,array());
            if (!empty($product_collect_ids)){
                $list_manager->addSQLCondition("id",$product_collect_ids);
                $list_manager->addRenderOption(ProductCollectObjListRender::OPTIONS_KEY_PARAMS, ProductCollectObjListRender::OPTIONS_FAVOURITE);
            }
            
        }
        //DISCOUNT
        if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_DISCOUNT_PRODUCT_COLLECT_IDS)){
            $product_collect_ids=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_DISCOUNT_PRODUCT_COLLECT_IDS,array());
            if (!empty($product_collect_ids)){
                $list_manager->addSQLCondition("id",$product_collect_ids);
            }
        }
        return $list_manager;
    }
    
    
    /**
     * Kategória szerinti listázás
     * @return type
     */
    public function process_listProductCollectInCategory(){
        
        $content = LiquidTemplate::loadTemplate(Application::getTheme()->getTemplateName('collection'));
        $handle = $this->request->url('handle', 'all');
        $tags = $this->request->url('tags', '');
        if(!empty($tags)){
            $tags = explode(' ', $tags);
            Application::getResponse()->setObjectToGlobalAssign('current_tags', new LiquidCurrentTagsObj($tags));
        }
        
        $collection = new LiquidCollectionObj($handle);       
        $content->assign($collection, 'collection');
        
        return $content;
    }
    
    /**
     * Akció szerinti listázás
     * @return type
     */
    public function process_listProductCollectInDiscount(){
        
        
        $content=$this->loadTemplate("product_collect_list_in_discount.html");
        $discount_id = $this->request->url('discount_id', 0);
        $discount_obj=  DiscountObj::getInstance()->load($discount_id,false);
        if ($discount_obj instanceof DiscountObj){
            $discount_name=$discount_obj->getName();
            $content->replace("::discount::", $discount_name);
            $product_collect_ids=SQL::query("SELECT DISTINCT(product_collect_id) FROM product WHERE discount_id=$discount_id AND status=".ProductObj::STATUS_ACTIVE,"webshop_product")->fetchListData();
            if (empty($product_collect_ids)){
                $product_collect_ids[]=0;
            }
            $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_DISCOUNT_PRODUCT_COLLECT_IDS, $product_collect_ids);
            $this->resetSessionListParameters(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_DISCOUNT_PRODUCT_COLLECT_IDS);
            $list_manager=$this->getListManagerInstance(true);
            $content->replace("::container::", $list_manager->getItems());
            return $content;
        }
        return Application::setRedirect("@home");
    }
    
    
    /**
     * Ha új kategória listázás van, resetelni kell a num és record értékeket
     * @param type $category_ids
     */
    private function checkCategory($category_ids){
        if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_CATEGORY_ID)){
            $category_ids_in_session=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_CATEGORY_ID,array());
            if ($category_ids_in_session!=$category_ids){
                $this->resetListManagerInstance();
            }
        }
    }
    
    
    
    /**
     * Keresés szerinti listázás
     * @return type
     */
    public function process_listProductCollectSearch(){
        $content=$this->loadTemplate("product_collect_list_search.html");
        $search_term=$this->request->url("search_term", "");
        $this->checkSearch($search_term);
        $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM, $search_term);
        $this->resetSessionListParameters(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM);
        $list_manager=$this->getListManagerInstance(true);
        $content->replace("::container::", $list_manager->getItems());
        return $content;
    }
    
    
    /**
     * Ha új keresés listázás van, resetelni kell a num és record értékeket
     * @param type $search_term
     */
    private function checkSearch($search_term){
        if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM)){
            $search_term_in_session=$this->getSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM,'');
            if ($search_term_in_session!=$search_term){
                $this->resetListManagerInstance();
            }
        }
    }
    
    
    /**
     * Kedvencek szerinti listázás
     * @return type
     */
    public function process_listProductCollectFavourites(){
        try{
            $content=$this->loadTemplate("product_collect_list_favourites.html");
            $favourite=new UserFavouriteProductCollect();
            $ids=$favourite->getProductCollectIds();
            if (empty($ids)){
                $ids[]=0;
            }
            $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_FAVOURITE, $ids);
            $this->resetSessionListParameters(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_FAVOURITE);
            $list_manager=$this->getListManagerInstance(true);
            $content->replace("::container::", $list_manager->getItems());
            
        }  catch (Exception $e){
           $content=new Template(lang('user_favourite_not_logged'),false);
           return $content;
        }
        return $content;
    }
    
    
    private function resetSessionListParameters($type){
        switch ($type){
            case self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_FAVOURITE:
            {
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_CATEGORY_ID,array());
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM,'');
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_DISCOUNT_PRODUCT_COLLECT_IDS,array());
            }
            break;
            case self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_CATEGORY_ID:
            {
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_FAVOURITE,array());
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM,'');
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_DISCOUNT_PRODUCT_COLLECT_IDS,array());
            }
            break;
            case self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM:
            {
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_FAVOURITE,array());
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_CATEGORY_ID,array());
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_DISCOUNT_PRODUCT_COLLECT_IDS,array());
                
            }
            break;
            case self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_DISCOUNT_PRODUCT_COLLECT_IDS:
            {
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_FAVOURITE,array());
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_CATEGORY_ID,array());
                $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_SEARCH_TERM,'');
            }
            break;
        
            default:
            break;
        }
    }
    
    
    /**
     * Reset "num" és "record" érték
     */
    private function resetListManagerInstance($num=ProductCollectObjListManager::DEFAULT_PAGE_NUM){
        if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_NUM)){
            $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_NUM, $num);
        }
        if ($this->issetSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_RECORD)){
            $this->setSessionData(self::SESSION_INDEX_PRODUCT_COLLECT_LIST_PARAM_RECORD, 0);
        }
    }
    
    /**
     * Termék megtekintés
     * @return type
     */
    public function process_viewProductCollect(){
        
        $product_handle = $this->request->any('product_handle', '');
        
        $content = LiquidTemplate::loadTemplate('product.liquid');
        $content->assign(new LiquidProductObj($product_handle), 'product');
        
//        $content=$this->loadTemplate("product_collect_view.html");
//        $product_collect_id=$this->request->any("collect_id",0);
//        $product_collect_obj=ProductCollectObjLoader::getInstance()->setProductCollectIds($product_collect_id)->getObjs();
//        if ($product_collect_obj instanceof ProductCollectObj){
//            $content->replace("::name::", $product_collect_obj->getName());
//            $content->replace("::id::", $product_collect_obj->getId());
//            $content->replaceHTML("::first_img::", $product_collect_obj->getFirstImage(ImageManager::FILTER_NAME_1,true,true));
//            //TOVÁBBI KÉPEK
//            $more_imgs=implode(" ",$product_collect_obj->getMoreImage(ImageManager::FILTER_NAME_2,true,true));
//            $more_imgs.=implode(" ",$product_collect_obj->getProductImages());
//            $content->replaceHTML("::more_imgs::", $more_imgs);
//            $content->replaceHTML("::properties::", $product_collect_obj->getProperties());
//            $variants=$product_collect_obj->getVariantsSelect($this->request->any("variant",array()));
//            $content->replaceHTML("::variants_select::", $variants["select"]);
//            $content->replaceHTML("::price::", $variants["price"]);
//            $content->replaceHTML("::compare_at_price::",  $variants["compare_at_price"]);
//            for($i=1;$i<=10;$i++){
//                $quantity_arr[$i]=$i;
//            }
//            $content->replaceHTML("::quantity_option::",  options_for_select($quantity_arr,1));
//            ProductCollectObjLastView::add($product_collect_obj);
//        }
//        //FAVOURITE MIATT
//        $this->addJavascript("user.js", "User");
        return $content;
    }
    
    /**
     * Variáns módosítás
     */
    public function process_changeVariant(){
        $product_collect_id=$this->request->any("product_collect_id",0);
        $product_collect_obj=ProductCollectObjLoader::getInstance()->setProductCollectIds($product_collect_id)->getObjs();
        if ($product_collect_obj instanceof ProductCollectObj){
            $variants=$product_collect_obj->getVariantsSelect($this->request->any("variant",array()));
            AjaxResponse::setCode(1);
            AjaxResponse::addParam("select", $variants["select"]);
            AjaxResponse::addParam("compare_at_price", $variants["compare_at_price"]);
            AjaxResponse::addParam("price", $variants["price"]);
        }else {
            AjaxResponse::setCode(0);
        }
        
        $this->response->setJSONContent(AjaxResponse::getResponse()); 
    }
    
    public function slot_categoryTree(){
        $active_category_id = $this->request->any('category_id', 0);
        $category = ProductCategory::getInstance()->displayTreeFrontEnd($active_category_id);
        return new Template($category, false);
    }
    
    
    public function slot_quickSearchFrom(){
        $content = $this->loadTemplate('slot_quick_search_form.html');
        $content->replace("::search_text::", '');
        return $content;
    }
    
    public function process_quickSearch(){
        $search_string = $this->request->any('search_text', '');
        $result = $this->doSearch($search_string);
        
        if(!empty($result['found_cnt'])){
            $data = [];
            $ovjectums = ProductCollectObjLoader::getInstance()->setProductCollectIds($result['result'])->getObjs();

            foreach ($ovjectums as $ovj){
                if($ovj instanceof ProductCollectObj){
                    $data[] = [
                        'product_href' => url_for('@product_collect_view?collect_id='.$ovj->getId()),
                        'first_img' => $ovj->getFirstImage(ImageManager::FILTER_NAME_2,false),
                        'title' => $ovj->getName(),
                        'price' => $ovj->getPrice(),
                    ];
                }
            }
            $found_cnt = count($result['found_cnt']);
            $message = "OK";
        }else{
            $data = [];
            $found_cnt = 0;
            $message = "ERROR";
        }
        $output = array(
            'message' => $message,
            'data' => $data,
            'found_cnt' => $found_cnt,
        );
        $this->response->setJSONContent($output);
    }
    
    
    protected function doSearch($search_string, $offset = 0, $limit = 10){
        $result = SQL::query("SELECT SQL_CALC_FOUND_ROWS id FROM product_collect WHERE name LIKE '%".SQL::escape($search_string)."%' AND status=1 AND deleted=0 LIMIT $offset, $limit", 'webshop_product')->fetchListData();
        $found_cnt = SQL::query("SELECT FOUND_ROWS()", 'webshop')->fetchValue(0);
        $return = [
            'result' => $result,
            'found_cnt' => $found_cnt,
        ];
        return $return;
    }
    
    /**
     * * C O M P A R E *
     * Fix sáv megjelenítő
     * @return type
     */
    public function slot_compare(){
        
        $compare=new ProductCollectObjCompare();
        $content=$this->loadTemplate("product_collect_compare_container.html");
        $content->replace("::content::", $compare->getContent());
        $content->replace("::display_class::",$compare->getContainerClass());
        return $content;
    }
    
    /**
     * * C O M P A R E *
     * Egy elem hozzáadása a listához
     * @throws Exception
     */
    public function process_addToCompare(){
        try{
            $product_collect_id=$this->request->any("product_collect_id",0);
            $with_remove_all=$this->request->any("with_remove_all",0);
            $product_collect_obj=ProductCollectObjLoader::getInstance()->setProductCollectIds($product_collect_id)->getObjs();
            if (!$product_collect_obj instanceof ProductCollectObj) throw new Exception (lang('compare_bad_parameter'),3);
            $compare=new ProductCollectObjCompare();
            if ($with_remove_all){
                $compare->removeAll();
            }
            $compare->add($product_collect_obj);
            AjaxResponse::setResult(true);
            AjaxResponse::addParam("content", $compare->getContent());
            AjaxResponse::addParam("count", $compare->getCount());
            AjaxResponse::setMessage('success');
            AjaxResponse::setCode(1);
        }catch(Exception $e){
            AjaxResponse::setResult(false);
            AjaxResponse::setMessage($e->getMessage());
            AjaxResponse::setCode($e->getCode());
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    
    /**
     * * C O M P A R E *
     * Egy elem eltávolítása a listáról
     * @throws Exception
     */
    public function process_removeFromCompare(){
        try{
            $product_collect_id=$this->request->any("product_collect_id",0);
            $product_collect_obj=ProductCollectObjLoader::getInstance()->setProductCollectIds($product_collect_id)->getObjs();
            if (!$product_collect_obj instanceof ProductCollectObj) throw new Exception (lang('compare_bad_parameter'),3);
            $compare=new ProductCollectObjCompare();
            $compare->remove($product_collect_obj->getId());
            AjaxResponse::setResult(true);
            AjaxResponse::addParam("content", $compare->getContent());
            AjaxResponse::addParam("count", $compare->getCount());
            AjaxResponse::setMessage('success');
            AjaxResponse::setCode(1);
        }catch(Exception $e){
            AjaxResponse::setResult(false);
            AjaxResponse::setMessage($e->getMessage());
            AjaxResponse::setCode($e->getCode());
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    /**
     * * C O M P A R E *
     * Összes eltávolítása a listáról
     */
    public function process_removeAllFromCompare(){
        try{
            $compare=new ProductCollectObjCompare();
            $compare->removeAll();
            AjaxResponse::setResult(true);
            AjaxResponse::setMessage('success');
            AjaxResponse::setCode(1);
        }catch(Exception $e){
            AjaxResponse::setResult(false);
            AjaxResponse::setMessage($e->getMessage());
            AjaxResponse::setCode($e->getCode());
        }
        $this->response->setJSONContent(AjaxResponse::getResponse());
    }
    
    /**
     * * C O M P A R E *
     * Megjelenítés
     * @return type
     */
    public function process_renderCompare(){
         $compare=new ProductCollectObjCompare();
         return $compare->render();
    }
    
    /**
     * Utoljára megtekintett termékek
     * @return type
     */
    public function slot_lastView(){
        return ProductCollectObjLastView::render();
    }
    
    public function process_searchProductCollect(){
        $search_string=trim($this->request->get("q",""));
        $result = $this->doSearch($search_string);
        $content=LiquidTemplate::loadTemplate("search.liquid");

        $content->assign(new LiquidSearchObj($result["result"],$search_string),"search");
        return $content;
    }
}