<?php

class ProductCollectObj extends DatabaseObj {

    const _ID                       =   'id';
    const _HANDLE                   =   'handle';
    const _CATEGORY_ID              =   'category_id';
    const _PRODUCT_COLLECT_TYPE_ID  =   'product_collect_type_id';
    const _NAME                     =   'name';
    const _STATUS                   =   'status';
    const _SHORT_TEXT               =   'short_text';
    const _LONG_TEXT                =   'long_text';
    const _COMPARE_AT_PRICE         =   'compare_at_price';
    const _PRICE                    =   'price';
    const _WEIGHT                   =   'weight';
    const _SEO_TITLE                =   'seo_title';
    const _SEO_DESCRIPTION          =   'seo_description';
    const _DELETED                  =   'deleted';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_collect';
    protected $fields = [
        self::_ID,self::_HANDLE,self::_CATEGORY_ID,self::_PRODUCT_COLLECT_TYPE_ID,self::_NAME,
        self::_STATUS,self::_SHORT_TEXT,self::_LONG_TEXT,self::_COMPARE_AT_PRICE,self::_PRICE,
        self::_WEIGHT,self::_SEO_TITLE,self::_SEO_DESCRIPTION,self::_DELETED
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_ID => 0,
            self::_HANDLE => '',
            self::_CATEGORY_ID => 0,
            self::_PRODUCT_COLLECT_TYPE_ID => 0,
            self::_NAME => '',
            self::_STATUS => 0,
            self::_SHORT_TEXT => '',
            self::_LONG_TEXT => '',
            self::_COMPARE_AT_PRICE => 0,
            self::_PRICE => 0,
            self::_WEIGHT => 0,
            self::_SEO_TITLE => '',
            self::_SEO_DESCRIPTION => '',
            self::_DELETED => 0
        ]);
    }

    public function getId() { return $this->getValue(self::_ID); }
    public function getHandle() { return $this->getValue(self::_HANDLE); }
    public function getCategoryId() { return $this->getValue(self::_CATEGORY_ID); }
    public function getProductCollectTypeId() { return $this->getValue(self::_PRODUCT_COLLECT_TYPE_ID); }
    public function getName() { return $this->getValue(self::_NAME); }
    public function getStatus() { return $this->getValue(self::_STATUS); }
    public function getShortText() { return $this->getValue(self::_SHORT_TEXT); }
    public function getLongText() { return $this->getValue(self::_LONG_TEXT); }
    public function getCompareAtPrice() { return $this->getValue(self::_COMPARE_AT_PRICE); }
    public function getPrice() { return $this->getValue(self::_PRICE); }
    public function getWeight() { return $this->getValue(self::_WEIGHT); }
    public function getSeoTitle() { return $this->getValue(self::_SEO_TITLE); }
    public function getSeoDescription() { return $this->getValue(self::_SEO_DESCRIPTION); }
    public function getDeleted() { return $this->getValue(self::_DELETED); }


    public function setId($value) { $this->setValue(self::_ID, $value); }
    public function setHandle($value) { $this->setValue(self::_HANDLE, $value); }
    public function setCategoryId($value) { $this->setValue(self::_CATEGORY_ID, $value); }
    public function setProductCollectTypeId($value) { $this->setValue(self::_PRODUCT_COLLECT_TYPE_ID, $value); }
    public function setName($value) { $this->setValue(self::_NAME, $value); }
    public function setStatus($value) { $this->setValue(self::_STATUS, $value); }
    public function setShortText($value) { $this->setValue(self::_SHORT_TEXT, $value); }
    public function setLongText($value) { $this->setValue(self::_LONG_TEXT, $value); }
    public function setCompareAtPrice($value) { $this->setValue(self::_COMPARE_AT_PRICE, $value); }
    public function setPrice($value) { $this->setValue(self::_PRICE, $value); }
    public function setWeight($value) { $this->setValue(self::_WEIGHT, $value); }
    public function setSeoTitle($value) { $this->setValue(self::_SEO_TITLE, $value); }
    public function setSeoDescription($value) { $this->setValue(self::_SEO_DESCRIPTION, $value); }
    public function setDeleted($value) { $this->setValue(self::_DELETED, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['handle, category_id, product_collect_type_id, name, status, short_text, long_text, compare_at_price, price, weight, seo_title, seo_description, deleted', 'required', 'message' => 'Kötelezően megadandó!'],
            ['handle', 'length', 'max'=>128, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 128 karakter adható meg!'],
            ['category_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['product_collect_type_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['name', 'length', 'max'=>128, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 128 karakter adható meg!'],
            ['status', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['short_text', 'length', 'max'=>256, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 256 karakter adható meg!'],
            ['compare_at_price', 'numerical', 'message' => 'Hibás adat!'],
            ['price', 'numerical', 'message' => 'Hibás adat!'],
            ['weight', 'numerical', 'message' => 'Hibás adat!'],
            ['seo_title', 'length', 'max'=>128, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 128 karakter adható meg!'],
            ['seo_description', 'length', 'max'=>128, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 128 karakter adható meg!'],
            ['deleted', 'numerical', 'integerOnly', 'message' => 'Hibás adat!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['id', 'handle', 'category_id', 'product_collect_type_id', 'name', 'status', 'short_text', 'long_text', 'compare_at_price', 'price', 'weight', 'seo_title', 'seo_description', 'deleted'],
        ];
    }


    
    
    /********************************
     *********  ADDED   *************   
     ********************************/

    
    
    
    const STATUS_ACTIVE     =   1;
    const STATUS_INACTIVE   =   2;
    
    private $status_arr=array(
        
        self::STATUS_ACTIVE         =>  'product_status_active',
        self::STATUS_INACTIVE       =>  'product_status_inactive',
        
    );
    
    /**
     * Tulajdonság nevek objektum tömbje
     * @var type ProductCollectPropertyNameObj
     */
    protected $property_name_objs;
    
    
    /**
     * Típus objektum
     * @var type ProductCollectTypeObj
     */
    protected $type_obj=null;
    
    
    /**
     * A variánsok objektum tömbje
     * @var type ProductCollectVariantObj
     */
    protected $variant_objs;
    
    
    
    /**
     * A gyűjtőhöz tartozó product objektumok tömbje
     * @var type ProductObj
     */
    protected $product_objs;
    
    
    
    /**
     * A gyűjtőhöz tartozó minimum ár szerinti product objektumok
     * @var type ProductObj
     */
    protected $product_obj_min;
    
    /**
     * Van-e a gyűjtőnek variánsa
     * @var type 
     */
    protected $has_variant=0;
    
    
    /**
     * Tagok
     * @var type TagObj 
     */
    protected $tag_objs;
    
    /**
     * Tag idk
     * @var type 
     */
    protected $tag_ids = array();
    
    
    /**
     * Kép objektumok
     * @var type 
     */
    protected $image_objs = array();
    
    
    public function loadAll(){
        $this->loadTypeObj();
        $this->loadVariantObjs($this->getProductCollectTypeId());
        $this->loadPropertyNameObjs($this->getProductCollectTypeId());
        $this->loadProductObjs();
        $this->loadTagObjs();
        
    }
    public function loadAllForProduct(){
        $this->loadProductObjs();
        $this->loadVariantObjs($this->getProductCollectTypeId());
    }
    
    
    /*** PRICES ***/
    public function getCompareAtPriceWithCurrency() { return CurrenciesManager::formatPrice($this->getCompareAtPrice());}
    public function getPriceWithCurrency() { return CurrenciesManager::formatPrice($this->getPrice()); }
    
    public function getCompareAtPriceByProducts() {
        $this->setProductObjMin();
        
        if ($this->product_obj_min instanceof ProductObj){
            return $this->product_obj_min->getCompareAtPrice();
        }
        return 0;
    }
    public function getPriceByProducts() {
        $this->setProductObjMin();
        if ($this->product_obj_min instanceof ProductObj){
            return $this->product_obj_min->getPriceWithDiscount();
        }
        return 0;
    }
    
    public function getCompareAtPriceByProductsWithCurrency() { return CurrenciesManager::formatPrice($this->getCompareAtPriceByProducts());}
    public function getPriceByProductsWithCurrency() { return CurrenciesManager::formatPrice($this->getPriceByProducts()); }
    
    public function setProductObjMin(){
        if (empty($this->product_obj_min)){
            $product_obj_min=null;
            if (empty($this->product_objs)) {
                $this->loadProductObjs();
            }     
            foreach($this->product_objs as $product_obj){
                if ($product_obj instanceof ProductObj){
                    if (!$product_obj_min instanceof ProductObj){
                        $product_obj_min=$product_obj;
                    } elseif($product_obj_min->getPriceWithDiscount() > $product_obj->getPriceWithDiscount()){
                        $product_obj_min=$product_obj;
                    }
                }
            }
            $this->product_obj_min=$product_obj_min;
        }
    }
    
    
    
    
    
    /**** STATUS ****/

    public function isActive(){
        return ($this->getStatus() == self::STATUS_ACTIVE);
    }
    
    /**
     * Státusz tömb
     * @return type
     */
    public function getStatusArr(){
        $return=array();
        foreach($this->status_arr as $id=>$lang_name){
            $return[$id]=lang($lang_name);
        }
        return $return;
    }
    
    /**
     * Státusz option
     * @param type $selected_item
     * @return type
     */
    public function getStatusArrOptions($selected_item=0){
        $arr=$this->getStatusArr();
        return options_for_select($arr, $selected_item);
    }
    
    /**
     * Státusz név visszaadása
     * @param type $status
     * @return string
     */
    public function getStatusName($status){
        if (isset($this->status_arr[$status])){
            return lang($this->status_arr[$status]);
        }else {
            return "";
        }
    }
    
    /**** KÉPEK ****/
    
    public function loadImageObjs(){
        $this->setImageObjs(ImageManagerService::getImageObj($this->getId(), ImageManager::TYPE_PRODUCT_COLLECT));
    }
    
    public static function loadImageObjsMassive($product_collect_objs=array()){
        
        if (!is_array($product_collect_objs)){
            $product_collect_objs[$product_collect_objs->getId()]=$product_collect_objs;
        }
        $image_objs=ImageManagerService::getImageObjs(array_keys($product_collect_objs), ImageManager::TYPE_PRODUCT_COLLECT);
        foreach($product_collect_objs as $product_collect_obj){
            $product_collect_image_objs=array();
            foreach($image_objs as $image_obj){
                if ($image_obj instanceof ImageManagerImageObjInterface){
                    if ($image_obj->getRefid()==$product_collect_obj->getId()){
                        $product_collect_image_objs[$image_obj->getId()]=$image_obj;
                    }
                }
            }
            $product_collect_obj->setImageObjs($product_collect_image_objs);
        }
        return $product_collect_objs;
        
    }
    
    public function setImageObjs($val){
        $this->image_objs=$val;
    }
    /**
     * 
     * @return ImageManagerImageObjInterface
     */
    public function getImageObjs(){
        return $this->image_objs;
    }
    
    
    public function getFirstImage($size=ImageManager::ORIGINAL,$img=true,$link=false){
        
        if (!empty($this->image_objs)){
            foreach($this->image_objs as $image_obj){
                if ($image_obj->getFirst()!=0){
                    return $image_obj->getUrl($size,$img,$link);
                }
            }
        }
        return "";
    }

    public function getMoreImage($size=ImageManager::ORIGINAL,$img=true,$link=false){
        $imgs=array();
        if (!empty($this->image_objs)){
            foreach($this->image_objs as $image_obj){
                if ($image_obj->getFirst()==0){
                    $imgs[]=$image_obj->getUrl($size,$img,$link);
                }
            }
        }
        return $imgs;
    }
    
    
    public function getProductImages($size=ImageManager::FILTER_NAME_2){
        $imgs=array();
        foreach($this->product_objs as $product_obj){
            if ($product_obj instanceof ProductObj && $product_obj->hasImage() && $product_obj->getStatus()!=0){
                $first=$product_obj->getFirstImage(ImageManager::FILTER_NAME_2,true,true);
                $more=$product_obj->getMoreImage(ImageManager::FILTER_NAME_2,true,true);
                if (!empty($first)){
                    $imgs[]=$first;
                }
                if (!empty($more)){
                    $imgs[]=implode(" ",$more);
                }
            }
        }
        return $imgs;
    }
    
    
    /**** TÍPUS ****/

    /**
     * Typus obj betöltés
     */
    public function loadTypeObj(){
        try{
            $obj=ProductCollectTypeObj::getInstance()->load($this->getProductCollectTypeId());
        }
        catch(Exception $e){
            $obj=new ProductCollectTypeObj();
        } 
        $this->setTypeObj($obj);
    }
    
    /**
     * Typus obj tömeges beállítás
     * @param type $product_collect_objs
     * @param type $ids_in_keys
     */
    public static function loadTypeObjMassive($product_collect_objs=array()){
        if (!is_array($product_collect_objs)){
            $product_collect_objs[$product_collect_objs->getId()]=$product_collect_objs;
        }
        $product_collect_type_ids=array();
        foreach($product_collect_objs as $product_collect_obj){
            if ($product_collect_obj instanceof ProductCollectObj){
                $product_collect_type_ids[]=$product_collect_obj->getProductCollectTypeId();
            }
        }
        $type_objs=ProductCollectTypeObj::getInstance()->load($product_collect_type_ids);
        foreach($product_collect_objs as $product_collect_obj){
            if ($product_collect_obj instanceof ProductCollectObj && isset($type_objs[$product_collect_obj->getProductCollectTypeId()])){
                $product_collect_obj->setTypeObj($type_objs[$product_collect_obj->getProductCollectTypeId()]);
            }
        }
        return $product_collect_objs;
    }
    
    
    
    /**
     * Getter
     * @return type
     */
    public function getTypeObj(){
        return $this->type_obj;
    }
    
    /**
     * Setter
     * @param type $val
     */
    public function setTypeObj($val){
        $this->type_obj=$val;
    }
    /**
     * Típus név visszaadása
     * @return type
     */
    public function getTypeName(){
        if (!$this->type_obj instanceof ProductCollectTypeObj){
            $this->loadTypeObj();
        }
        return $this->type_obj->getName();
    }
    
    
    /**** VARIÁNSOK ****/
    
    /**
     * Variánsok betöltése, illetve a variánsokhoz tartozó értékek beállítása is
     * @param type $product_collect_type_id
     */
    public function loadVariantObjs($product_collect_type_id){
        $variant_ids=ProductCollectTypeVariantObj::getVarianstByTypeId($product_collect_type_id);
        if (!empty($variant_ids) && !in_array(0,$variant_ids)){
            $this->setHasVariant(1);
            $variant_objs=ProductCollectVariantObj::getInstance()->loadByQuery("WHERE id IN (".implode(",",$variant_ids).") ORDER BY id DESC");
            ProductCollectVariantObj::loadVariantObjsValues($variant_objs);
            $this->setVariantObjs($variant_objs);
        }else {
            $this->setHasVariant(0);
        }
        
    }
    /**
     * Variánsok tömeges betöltése, illetve a variánsokhoz tartozó értékek beállítása is
     * @param type $product_collect_type_id
     */
    public static function loadVariantObjsMassive($product_collect_objs=array()){
        if (!is_array($product_collect_objs)){
            $product_collect_objs[$product_collect_objs->getId()]=$product_collect_objs;
        }
        $product_collect_type_ids=array();
        foreach($product_collect_objs as $product_collect_obj){
            if ($product_collect_obj instanceof ProductCollectObj){
                $product_collect_type_ids[]=$product_collect_obj->getProductCollectTypeId();
            }
            
        }
        $product_collect_type_variant_ids=ProductCollectTypeVariantObj::getVarianstByTypeIdMassive($product_collect_type_ids,false);
        $variant_ids=array();
        $has_no_variant=array();
        foreach($product_collect_type_variant_ids as $product_collect_type_id=>$arr){
            foreach($arr as $variant_id=>$v){
                $variant_ids[]=$variant_id;
                if (empty($variant_id)){
                    $has_no_variant[$product_collect_type_id]=1;
                }
            }
        }
        
        
        if(!empty($variant_ids)){
            $variant_objs=ProductCollectVariantObj::getInstance()->loadByQuery("WHERE id IN (".implode(",",$variant_ids).") ORDER BY id DESC");
            ProductCollectVariantObj::loadVariantObjsValues($variant_objs);

            foreach($product_collect_objs as $product_collect_obj){
                if ($product_collect_obj instanceof ProductCollectObj){
                    $product_collect_variant_objs=array();
                    if (isset($product_collect_type_variant_ids[$product_collect_obj->getProductCollectTypeId()])){
                        foreach($product_collect_type_variant_ids[$product_collect_obj->getProductCollectTypeId()]  as $product_collect_variant_id=>$v){
                            if (isset($variant_objs[$product_collect_variant_id])){
                                $product_collect_variant_objs[$product_collect_variant_id]=$variant_objs[$product_collect_variant_id];
                            }
                        }  
                    }
                    if (!empty($product_collect_variant_objs)){
                        $product_collect_obj->setVariantObjs($product_collect_variant_objs);
                    }
                    if (!empty($has_no_variant[$product_collect_obj->getProductCollectTypeId()])){
                        $product_collect_obj->setHasVariant(0);
                    }else {
                        $product_collect_obj->setHasVariant(1);
                    }
                }
            }
        }
        return $product_collect_objs;
    }
    
    /**
     * Getter
     * @return ProductCollectVariantObj
     */
    public function getVariantObjs(){
        return $this->variant_objs;
    } 
    
    
    /**
     * Setter
     * @param type $val
     */
    public function setVariantObjs($val){
        $this->variant_objs=$val;
    }
        
    /**
     * Aktuális variáns objektum visszaadása, adott indexen
     * @return ProductCollectVariantObj
     */
    public function getVariantObj($key){
        if (isset($this->variant_objs[$key])){
            return $this->variant_objs[$key];
        }else {
            return new ProductCollectVariantObj();
        }
    }
    
    
    public function getFirstVariantObj(){
        $keys=  array_keys($this->variant_objs);
        return $this->getVariantObj($keys[0]);
    }
    
    /**
     * Variánsok száma
     * @return int
    */
    public function getVariantCount(){
       $db=count($this->variant_objs);
       if ($db==0 && !$this->has_variant()){
           return 0;
       }elseif ($db>=2){
           $db=2;
       }
       return $db;
    }
    
    /**
     * A gyűjtőnek van-e variánsa
     * @return boolean
     */
    public function hasVariant(){
        if ($this->has_variant==1){
            return true;
        }else return false;
    }
    
    
    public function getHasVariant(){
        return $this->has_variant;
    }
    
    /**
     * Setter
     * @param type $val
     */
    private function setHasVariant($val){
        $this->has_variant=$val;
    }
    
    /**
     * A gyűjtőhöz tartozó productok alapján összegyűjti a lehetséges, valid, vásárolható variánsokat, pl. kék S, piros XS,
     * @return type
     */
    private function getValidVariantValues(){
        $valid_variant_values=array();
        if (is_array($this->product_objs)){
            foreach($this->product_objs as $product_obj){
                if ($product_obj instanceof ProductObj && 
                    $product_obj->getStatus()==1 && 
                    $product_obj->getPiece()!=0){
                    $variant_value_objs=$product_obj->getVariantValueObjs();
                    foreach($variant_value_objs as $variant_value_obj){
                        if ($variant_value_obj instanceof ProductVariantValueObj){
                            $valid_variant_values[$variant_value_obj->getProductCollectVariantId()][$variant_value_obj->getProductCollectVariantValueId()]="";
                        }
                    }
                }
            }
            return $valid_variant_values;
        }
        
    }
    
    private function getValidVariantPair($first_variant_id,$first_variant_value_id){
        $product_objs=$this->getActiveProductObjs();
        $valid_variant_pair_values=array();
        foreach($product_objs as $product_obj){
            if($product_obj instanceof ProductObj){
                $variant_values_arr=$product_obj->getVariantValueObjsArr();
                if (isset($variant_values_arr[$first_variant_id]) && $variant_values_arr[$first_variant_id]==$first_variant_value_id){
                    unset($variant_values_arr[$first_variant_id]);
                    $keys=array_keys($variant_values_arr);
                    $valid_variant_pair_values[$keys[0]][$variant_values_arr[$keys[0]]]="";
                }
            }
        }
        return $valid_variant_pair_values;
        
    }
    /**
     * Visszaadja a variánsok szerinti selecteket, már ha van
     */
    public function getVariantsSelect($selected_variants=array()){
        $select=Template::loadTemplate("product_collect_variant_select.html","Product");
        switch ($this->getVariantCount()){
            case 1:
                    if (!empty($selected_variants)){
                        $keys=array_keys($selected_variants);
                        $selected=$selected_variants[$keys[0]];
                    }
                    $valid_variant_values=$this->getValidVariantValues();
                    foreach($this->variant_objs as $variant_obj){
                        $valid=array();
                        if ($variant_obj instanceof ProductCollectVariantObj){
                            $variant_values=$variant_obj->getVariantValuesArr ();
                            foreach($variant_values as $variant_value_id=>$variant_name){
                                if (isset($valid_variant_values[$variant_obj->getId()][$variant_value_id])){
                                    $valid[$variant_value_id]=$variant_name;
                                    if (empty($selected_variants)){
                                        $selected_variants[$variant_obj->getId()]=$variant_value_id;
                                    }
                                }
                            }
                            $data[]=array(
                                "name"=>$variant_obj->getName(),
                                "id"=>$variant_obj->getId(),
                                "select_name"=>"variant[".$variant_obj->getId()."]",
                                "options"=>options_for_select($valid,$selected),
                                "onchange"=>"ProductCollect.changeVariant()",
                            );
                        }    
                    }
                    $select->foreachSection("select", $data);
            break;
            case 2:
                    $valid_variant_values=$this->getValidVariantValues();
                    if (!empty($selected_variants) && count($selected_variants)==2){
                        $keys=array_keys($selected_variants);
                        $first_variant_id=$keys[0];
                        $first_variant_value_id=$selected_variants[$first_variant_id];
                        $second_variant_id=$keys[1];
                        $second_variant_value_id=$selected_variants[$second_variant_id];
                    }else {
                        $first_variant_obj=$this->getFirstVariantObj();
                        $first_variant_id=$first_variant_obj->getId();
                        
                        $variant_values=$first_variant_obj->getVariantValuesArr();
                        foreach($variant_values as $variant_value_id=>$variant_name){
                            if (isset($valid_variant_values[$first_variant_obj->getId()][$variant_value_id])){
                                $first_variant_value_id=$variant_value_id;
                                break;
                            }
                        }
                    }
                    $first=1;
                    foreach($this->variant_objs as $variant_obj){
                        $valid=array();
                        if ($variant_obj instanceof ProductCollectVariantObj){
                            $variant_values=$variant_obj->getVariantValuesArr();
                            if ($first){
                                foreach($variant_values as $variant_value_id=>$variant_name){
                                    if (isset($valid_variant_values[$variant_obj->getId()][$variant_value_id])){
                                        $valid[$variant_value_id]=$variant_name;
                                        if (empty($selected_variants)){
                                            $selected_variants[$variant_obj->getId()]=$variant_value_id;
                                        }
                                    }
                                }
                                $selected=$first_variant_value_id;
                            }else{
                                $valid_variant_pair_values=$this->getValidVariantPair($first_variant_id, $first_variant_value_id);
                                foreach($variant_values as $variant_value_id=>$variant_name){
                                    if (isset($valid_variant_pair_values[$variant_obj->getId()]) &&
                                        isset($valid_variant_pair_values[$variant_obj->getId()][$variant_value_id])){
                                        $valid[$variant_value_id]=$variant_name;
                                        if (count($selected_variants)==1){
                                            $selected_variants[$variant_obj->getId()]=$variant_value_id;
                                            $second_variant_value_id=$variant_value_id;
                                        }
                                    }
                                }
                                $selected=$second_variant_value_id;
                            }
                            $data[]=array(
                                "name"=>$variant_obj->getName(),
                                "id"=>$variant_obj->getId(),
                                "select_name"=>"variant[".$variant_obj->getId()."]",
                                "options"=>options_for_select($valid,$selected),
                                "onchange"=>"ProductCollect.changeVariant()",
                            );
                            $first=0;
                        }    
                    }
                    $select->foreachSection("select", $data);
            break;
            default:
                    $select=new Template("", false);
            break;
        }
        //A HOZZÁ TARTOZÓ PRODUCT, AZ AKTUÁLIS VARIÁNS SZERINT
        $prod_obj=$this->checkVariantAddToCart($selected_variants);
        if ($prod_obj instanceof ProductObj){
            $compare_at_price=$prod_obj->getCompareAtPriceWithCurrency();
            $price=$prod_obj->getPriceWithCurrency();
        //HA VALAMI GIGSZER LENNE    
        }else {
            $compare_at_price=0.00;
            $price=0.00;
        }
        return array("select"=>$select,"compare_at_price"=>$compare_at_price,"price"=>$price);
    }
    
    public function checkVariantAddToCart($prod_id){
        try{
            if ($this->hasVariant()){
                if (empty($prod_id)) throw new Exception();
                $product_objs = $this->getActiveProductObjs();
                foreach($product_objs as $product_obj){
                    if($product_obj instanceof ProductObj){
                        if($prod_id == $product_obj->getId()){
                            $ret_product_obj=$product_obj;
                        }
                    }
                }
                if (empty($ret_product_obj)) throw new Exception();
            }else {
                $ret_product_obj=$this->getFirstProductObj();
            }
        }catch(Exception $e){
            return null;
        }
        return $ret_product_obj;
    }
    
    /**** PROPERTY - TULAJDONSÁGOK ****/
    
    /**
     * Típus szerint property betöltése
     * @param type $product_collect_type_id
     */
    public function loadPropertyNameObjs($product_collect_type_id){
        $property_ids=ProductCollectTypePropertyObj::getPropertiesByTypeId($product_collect_type_id);
        $property_name_objs=ProductCollectPropertyNameObj::getInstance()->load($property_ids,false);
        ProductCollectPropertyNameObj::loadPropertyValues($property_name_objs);
        $product_collect_property_objs=ProductCollectPropertyObj::getInstance()->loadByQuery("WHERE product_collect_id=".$this->getId());
        ProductCollectPropertyObj::loadSelectvsCheckboxValues($product_collect_property_objs);
        $this->setPropertyNameObjs(ProductCollectPropertyNameObj::loadCollectPropertyValues($property_name_objs, $product_collect_property_objs));
    }
    
    /**
     * Tömeges típus szerint property betöltése
     * @param type $product_collect_type_id
     */
    public static function loadPropertyNameObjsMassive($product_collect_objs=array()){
        if (!is_array($product_collect_objs)){
            $product_collect_objs[$product_collect_objs->getId()]=$product_collect_objs;
        }
        $product_collect_type_ids=array();
        foreach($product_collect_objs as $product_collect_obj){
            if ($product_collect_obj instanceof ProductCollectObj){
                $product_collect_type_ids[]=$product_collect_obj->getProductCollectTypeId();
            }
        }
        $product_collect_type_property=ProductCollectTypePropertyObj::getPropertiesByTypeIdMassive($product_collect_type_ids,false);

        $property_ids=array();
        foreach($product_collect_type_property as $k=>$arr){
            foreach($arr as $variant_id=>$v){
                $property_ids[]=$variant_id;
            }
        }
        
        
        $property_name_objs=ProductCollectPropertyNameObj::getInstance()->load($property_ids,false);
        ProductCollectPropertyNameObj::loadPropertyValues($property_name_objs);
        $product_collect_property_objs=ProductCollectPropertyObj::getInstance()->loadByQuery("WHERE product_collect_id IN(".implode(",",array_keys($product_collect_objs)).")");
        ProductCollectPropertyObj::loadSelectvsCheckboxValues($product_collect_property_objs);
        $property_name_objs=ProductCollectPropertyNameObj::loadCollectPropertyValues($property_name_objs, $product_collect_property_objs);
        
        
        
        foreach($product_collect_objs as $product_collect_obj){
            if ($product_collect_obj instanceof ProductCollectObj){
                $product_collect_property_name_objs=array();
                if (isset($product_collect_type_property[$product_collect_obj->getProductCollectTypeId()])){
                    foreach($product_collect_type_property[$product_collect_obj->getProductCollectTypeId()]  as $product_collect_property_name_id=>$v){
                        if (isset($property_name_objs[$product_collect_property_name_id])){
                            $product_collect_property_name_objs[$product_collect_obj->getProductCollectTypeId()][$product_collect_property_name_id]=$property_name_objs[$product_collect_property_name_id];
                        }
                    }  
                }
                if (!empty($product_collect_property_name_objs)){
                    $product_collect_obj->setPropertyNameObjs($product_collect_property_name_objs[$product_collect_obj->getProductCollectTypeId()]);
                }
            }
            
        }
        return $product_collect_objs;
    }
    
    
    /**
     * Getter
     * @return type
     */
    public function getPropertyNameObjs(){
        return $this->property_name_objs;
    }
    
    
    /**
     * Setter
     * @param type $val
     */
    public function setPropertyNameObjs($val){
        $this->property_name_objs=$val;
    }
    
    /**
     * Aktuális property objektum visszaadása, adott indexen
     * @param type $key
     * @return \ProductCollectPropertyNameObj
     */
    public function getPropertyNameObj($key){
        if (isset($this->property_name_objs[$key])){
            return $this->property_name_objs[$key];
        }else {
            Log::dump("új");
            return new ProductCollectPropertyNameObj();
        }
    }
    
    /**
     * Property szerkesztő form készítő
     * @param type $product_collect_type_id
     * @return type
     */
    public function getPropertiesEdit($product_collect_type_id){
        $this->loadPropertyNameObjs($product_collect_type_id);
        $elements=array();
        foreach($this->property_name_objs as $property_name_id=>$property_name_obj){
            $property_obj=ProductCollectPropertyType::getInstanceByType($property_name_obj->getType());
            $property_obj->setId($property_name_id);
            $property_obj->setTitle($property_name_obj->getName());
            $property_obj->setName(ProductCollectPropertyType::getDefaultHTMLElementName($property_name_id));
            $property_obj->setOnclick(ProductCollectPropertyType::getDefaultHTMLElementOnClick($property_name_id));
            $property_obj->setOptions($property_name_obj->getPropertyValues());
            $property_obj->setValue($property_name_obj->getProductCollectPropertyValuesByType($property_name_obj->getType()));
            $elements[]=$property_obj->replaceEditTemplate();
        }
        return implode("<br><br>",$elements);
    }
    
    
    /**
     * Tulajdonságok
     * @param type $product_collect_type_id
     * @return type
     */
    public function getProperties(){
        $elements=array();
        if(!empty($this->property_name_objs)){
            foreach($this->property_name_objs as $property_name_id=>$property_name_obj){
                $property_obj=ProductCollectPropertyType::getInstanceByType($property_name_obj->getType());
                $property_obj->setId($property_name_id);
                $property_obj->setTitle($property_name_obj->getName());
                $property_obj->setName(ProductCollectPropertyType::getDefaultHTMLElementName($property_name_id));
                $property_obj->setOnclick(ProductCollectPropertyType::getDefaultHTMLElementOnClick($property_name_id));
                $property_obj->setOptions($property_name_obj->getPropertyValues());
                $property_obj->setValue($property_name_obj->getProductCollectPropertyValuesByType($property_name_obj->getType(),false));
                $elements[]=$property_obj->replaceTemplate();
            }
        }
        return implode("<br><br>",$elements);
    }
    
    public function getPropertiesCheckboxValues($product_collect_property_name_id){
        $this->loadPropertyNameObjs($this->getProductCollectTypeId());
        if (isset($this->property_name_objs[$product_collect_property_name_id])){
            $property_name_obj=$this->getPropertyNameObj($product_collect_property_name_id);
            $arr=$property_name_obj->getPropertyValues();
            $template=Template::loadTemplate("product_collect_property_checkbox_popup.html","Product");
            $template->replace("::product_collect_property_name_id::",$product_collect_property_name_id);
            //a beállított értékek
            $values=array();
            $product_collect_property_values=$property_name_obj->getProductCollectPropertyValues();
            foreach($product_collect_property_values as $product_collect_property){
                if ($product_collect_property instanceof ProductCollectPropertyObj){
                    $values[$product_collect_property->getProductCollectSelectValueId()]=1;
                }
            }
            
            foreach($arr as $id=>$name){
                $checked=(isset($values[$id]))?"checked":"";
                $data[$id]=array(
                    "id"=>$id,
                    "name"=>$name,
                    "checked"=>$checked,
                );
            }
            $template->foreachSection("checkbox_properties", $data);
        }    
        return $template;
    }
    
    
    
    /**
     * Property mentés
     * @param type $properties
     * @param type $product_collect_type_id
     * @throws Exception
     */
    public function saveProperties($properties,$product_collect_type_id){
        if (!empty($properties)){
            $this->loadPropertyNameObjs($product_collect_type_id);
            $property_objs=ProductCollectPropertyNameObj::getInstance()->load(array_keys($properties),false);
            foreach($properties as $product_collect_property_name_id=>$property_value){
                //HA LÉTEZŐ PROPERTY
                if (isset($property_objs[$product_collect_property_name_id])){
                    //HA VAN ILYEN TULAJDONSÁG
                    try{
                        //VALUES input vs checkbox
                        $setProductCollectPropertyValue=($property_objs[$product_collect_property_name_id]->isSelectVsCheckboxType())?0:$property_value;
                        $setProductCollectSelectValueId=($property_objs[$product_collect_property_name_id]->isSelectVsCheckboxType())?$property_value:0;
                        if (!isset($this->property_name_objs[$product_collect_property_name_id])) {
                            throw new Exception ('Új property'); 
                        }
                        $property_name_obj=$this->property_name_objs[$product_collect_property_name_id];
                        if ($property_name_obj instanceof ProductCollectPropertyNameObj){
                            $product_collect_property_obj=ProductCollectPropertyObj::getInstance()->loadByQuerySimple("WHERE product_collect_property_name_id=$product_collect_property_name_id AND product_collect_id=".$this->getId());
                            if (!$product_collect_property_obj instanceof ProductCollectPropertyObj){
                                throw new Exception ('Új property'); 
                            } 
                        }
                    }catch(Exception $e){
                        $product_collect_property_obj=new ProductCollectPropertyObj();
                        $product_collect_property_obj->setProductCollectPropertyNameId($product_collect_property_name_id);
                        $product_collect_property_obj->setProductCollectId($this->getId());
                    }
                    $product_collect_property_obj->setProductCollectPropertyValue($setProductCollectPropertyValue);
                    $product_collect_property_obj->setProductCollectSelectValueId($setProductCollectSelectValueId);
                    $product_collect_property_obj->save();
                }
            } 
        }
    }
    public function savePropertiesCheckbox($properties,$product_collect_property_name_id){
        //TULAJDONSÁGOK BETÖLTÉSE
        $this->loadPropertyNameObjs($this->getProductCollectTypeId());
        $names=array();
        //HA VAN ILYEN TULAJDONSÁG
        if (isset($this->property_name_objs[$product_collect_property_name_id])){
            $property_name_obj=$this->getPropertyNameObj($product_collect_property_name_id);
            $loaded=array();
            
            //A BEÁLLÍTOTT ÉRTÉKEK
            $values=array();
            $product_collect_property_values=$property_name_obj->getProductCollectPropertyValues();
            foreach($product_collect_property_values as $product_collect_property){
                if ($product_collect_property instanceof ProductCollectPropertyObj){
                    $values[$product_collect_property->getProductCollectSelectValueId()]=1;
                }
            }
            //AMIT TÖRÖLNI KELL, MERT MÁR NINCS
            foreach($values as $product_collect_select_value_id=>$y){
                if (!isset($properties[$product_collect_select_value_id])){
                    SQL::query("DELETE FROM product_collect_property WHERE product_collect_id=".$this->getId()." AND product_collect_property_name_id=$product_collect_property_name_id AND product_collect_select_value_id=$product_collect_select_value_id","webshop_product");
                }else {
                    unset($properties[$product_collect_select_value_id]);
                    $loaded[]=$product_collect_select_value_id;
                }
            }
            //AMI ÚJ
            foreach($properties as $product_collect_select_value_id=>$y){
                SQL::query("INSERT INTO product_collect_property SET product_collect_id=".$this->getId().", product_collect_property_name_id=$product_collect_property_name_id, product_collect_select_value_id=$product_collect_select_value_id","webshop_product"); 
                $loaded[]=$product_collect_select_value_id;
            }
            $loaded_values=ProductCollectPropertySelectValueObj::getInstance()->load($loaded);
            foreach($loaded_values as $product_collect_property_select_value_obj){
                $names[]=$product_collect_property_select_value_obj->getName();
            }
            
            return implode(", ",$names);
        }
        return "";
    }
    
    
    /**** PRODUCTOK ****/
    
    /**
     *  A gyűjtőhöz tartozó Product objektumok betöltése 
     */
     
    public function loadProductObjs(){
        $product_objs=ProductObj::getInstance()->loadByQuery("WHERE product_collect_id=".$this->getId()." ORDER BY id DESC");
        $product_objs=ProductObj::loadVariantValueObjs($product_objs);
        $product_objs=ProductObj::loadImageObjsMassive($product_objs);
        $product_objs=ProductObj::priceOverrides($product_objs);
        $this->setProductObjs($product_objs);
    }
    
    
    /**
     *  A gyűjtőhöz tartozó Product objektumok betöltése tömegesen
     */
     
    public static function loadProductObjsMassive($product_collect_objs=array()){
        if (!is_array($product_collect_objs)){
            $product_collect_objs[$product_collect_objs->getId()]=$product_collect_objs;
        }
        $product_objs=ProductObj::getInstance()->loadByQuery("WHERE product_collect_id IN(".implode(",",array_keys($product_collect_objs)).") ORDER BY id DESC");
        
        //variánsok & képek
        $product_objs=ProductObj::loadVariantValueObjs($product_objs);
        $product_objs=ProductObj::loadImageObjsMassive($product_objs);
        $product_objs=ProductObj::priceOverrides($product_objs);
        
        foreach($product_collect_objs as $product_collect_obj){
            $product_collect_product_objs=array();
            if ($product_collect_obj instanceof ProductCollectObj){
                foreach($product_objs as $product_obj){
                    if ($product_obj->getProductCollectId()==$product_collect_obj->getId()){
                        $product_collect_product_objs[$product_obj->getId()]=$product_obj;
                    }
                }
            }
            if (!empty($product_collect_product_objs)){
                $product_collect_obj->setProductObjs($product_collect_product_objs);
            }
        }
        return $product_collect_objs;
    }
    
    
    public static function loadProductObjImages(){
        $this->setProductObjs(ProductObj::loadImageObjsMassive($this->product_objs));
    }
    
    /**
     * Getter
     * @return ProductObj
     */
    public function getProductObjs(){
        return $this->product_objs;
        
    }
    
    public function getActiveProductObjs(){
        $product_objs=array();
        if (is_array($this->product_objs)){
            foreach($this->product_objs as $product_obj){
                if ($product_obj instanceof ProductObj && 
                    $product_obj->getStatus()){ 
//                    $product_obj->getStatus()==1 && 
//                    $product_obj->getPiece()!=0){
                    $product_objs[$product_obj->getId()]=$product_obj;
                }
            }
        }
        return $product_objs;
    }
    /**
     * Setter
     * @param type $val
     */
    public function setProductObjs($val){
        $this->product_objs=$val;
    }
    
    /**
     * Aktuális product objektum visszaadása, adott indexen
     * @param type $key
     * @return \ProductObj
     */
    public function getProductObj($key){
        if (isset($this->product_objs[$key])){
            return $this->product_objs[$key];
        }else {
            return new ProductObj();
        }
    }
    
    public function getFirstProductObj(){
        $keys=  array_keys($this->product_objs);
        return $this->getProductObj($keys[0]);
    }
    
    /**
     * 
     * @return \ProductObj
     */
    private function generateProductObjInstance(){
        
        $product_obj=new ProductObj();
        $product_obj->setProductCollectId($this->getId());
        $product_obj->setStatus(1);
        $product_obj->setPiece(1);
        $product_obj->setCompareAtPrice($this->getCompareAtPrice());
        $product_obj->setPrice($this->getPrice());
        $product_obj->setDiscountPrice($this->getPrice());
        $product_obj->setWeight($this->getWeight());
        return $product_obj;
    }
    
    /**
     * Gyűjtőhöz tartozó productok legenerálása
     */
    public function generateProducts(){
        
        $this->loadProductObjs();
        //ha mehet a generálás
        if (empty($this->product_objs)){
            $this->loadVariantObjs($this->getProductCollectTypeId());
            switch ($this->getVariantCount()){
                default:
                //nincs variáns
                case 0:
                {
                        $product_obj=$this->generateProductObjInstance();
                        $product_obj->setName($this->getName());
                        $product_obj->save();
                }
                break;
                //1 db variáns
                case 1:
                {   
                        $variant_obj=reset($this->variant_objs);
                        if ($variant_obj instanceof ProductCollectVariantObj){
                            $variant_value_objs=$variant_obj->getVariantValues();
                            foreach($variant_value_objs as $variant_value_id=>$variant_value_obj){
                                //product
                                $product_obj=$this->generateProductObjInstance();
                                $product_obj->setName($this->getName()."_".$variant_value_obj->getName());
                                $product_obj->save();
                                //variant
                                $product_variant_value=new ProductVariantValueObj();
                                $product_variant_value->setProductId($product_obj->getId());
                                $product_variant_value->setProductCollectVariantId($variant_obj->getId());
                                $product_variant_value->setProductCollectVariantValueId($variant_value_id);
                                $product_variant_value->save();
                            }
                        }
                }
                break;
                //2 db variáns
                case 2:
                {
                            $variant_obj_keys=array_keys($this->variant_objs);
                            $variant_obj_1=$this->getVariantObj($variant_obj_keys[0]);
                            $variant_obj_2=$this->getVariantObj($variant_obj_keys[1]);
                            $variant_value_objs_1=$variant_obj_1->getVariantValues();
                            $variant_value_objs_2=$variant_obj_2->getVariantValues();
                            foreach($variant_value_objs_1 as $variant_value_id_1=>$variant_value_obj_1){
                                foreach($variant_value_objs_2 as $variant_value_id_2=>$variant_value_obj_2){
                                    //product
                                    $product_obj=$this->generateProductObjInstance();
                                    $product_obj->setName($this->getName()."_".$variant_value_obj_1->getName()."_".$variant_value_obj_2->getName());
                                    $product_obj->save();
                                    //variant_1
                                    $product_variant_value=new ProductVariantValueObj();
                                    $product_variant_value->setProductId($product_obj->getId());
                                    $product_variant_value->setProductCollectVariantId($variant_obj_1->getId());
                                    $product_variant_value->setProductCollectVariantValueId($variant_value_id_1);
                                    $product_variant_value->save();
                                    //variant_2
                                    $product_variant_value=new ProductVariantValueObj();
                                    $product_variant_value->setProductId($product_obj->getId());
                                    $product_variant_value->setProductCollectVariantId($variant_obj_2->getId());
                                    $product_variant_value->setProductCollectVariantValueId($variant_value_id_2);
                                    $product_variant_value->save();
                                }
                            }
                }
                break;
            }
        }
    }
    
    /**
     * Gyűjtőhöz tartozó productok újragenerálása
     */
    public function reGenerateProducts(){
        $products=SQL::query("SELECT id FROM product WHERE product_collect_id=".$this->getId(),"webshop_product")->fetchListData();
        if (!empty($products)){
            SQL::query("DELETE FROM product_variant_value WHERE product_id IN(".implode(",",$products).")","webshop_product");
            SQL::query("DELETE FROM product WHERE product_collect_id=".$this->getId(),"webshop_product");
        }
        $this->generateProducts();
    }
    
    
    /**
     * Gyűjtőhöz tartozó productok táblázata
     * @return \Template
     */
    public function getProductsTable(){
        $table = new HTMLTable();
        $table  ->setWidth('100%')
                ->setAlign('center')
                ->setAjaxSource('/Product/getProductCollectProducts')
                ->setId('id_product_collect_products')
                ->setAjaxSourceParams([
                    'product_collect_id' => $this->getId()
                ])
                ->setSearchFields([
                    'id' => lang('table_head_id'),
                    'name' => lang('table_head_name'),
                ]);
        
        $th = new HTMLTableRowHead();
        $th->addCell(lang('table_head_id'))->setSortableBy('id');
        $th->addCell(lang('table_head_name'))->setSortableBy('name');
        $th->addCell(lang('table_head_status'))->setSortableBy('status');
        $th->addCell(lang('table_head_compare_at_price'))->setSortableBy('compare_at_price');
        $th->addCell(lang('table_head_price'))->setSortableBy('price');
        $th->addCell(lang('table_head_quantity'))->setSortableBy('piece');
        $th->addCell(lang('table_head_image'));
        $th->addCell(lang('table_head_variants'));

        $th->addCell(lang('table_head_operations'))->setColspan(2)->setAlign('center');
        $table->addHeadRow($th);
        
        return $table;
    }
    
    public function getProductsTableData(){

        $table = HTMLTable::create()->setSearchFields([
            'id' => lang('table_head_id'),
            'name' => lang('table_head_name'),
        ]);
        $table->addFilter('product_collect_id', $this->getId());
        
        $product_objs = ProductObj::getInstance()->setSQLCalcFoundRows(true)->loadByQuery($table->getSQLQueryString());
        $table->setPaginatorTotalItemCount(ProductObj::getInstance()->getSQLFoundRows());
        $product_objs=ProductObj::loadVariantValueObjs($product_objs);
        $product_objs=ProductObj::priceOverrides($product_objs);
        $this->setProductObjs($product_objs);
        
        
        
        if(!empty($this->product_objs)){
            $pieces=ProductPieceObj::getPiecesByProductIds(array_keys($this->product_objs));
            $store_objs=StoreObj::getInstance()->loadByQuery("WHERE 1");
            foreach($this->product_objs as $product_obj){
                if ($product_obj instanceof ProductObj){
                    $row = new HTMLTableRow();
                    $row->setRowId('product_'.$product_obj->getId());
                    $row->addCell($product_obj->getId());
                    $row->addCell($product_obj->getName());
                    $row->addCell($product_obj->getStatusName($product_obj->getStatus()));
                    $row->addCell($product_obj->getCompareAtPrice());
                    $row->addCell($product_obj->getPrice());
                    
                    $table_piece = new HTMLTable();
                    $table_piece->setWidth('100%')
                                ->setAlign('center');
                    $th = new HTMLTableRowHead();
                    $th->addCell("Available piece:"); 
                    $th->addCell($product_obj->getPiece()); 
                    $table_piece->addHeadRow($th);
                    if (isset($pieces[$product_obj->getId()])){
                        
                        foreach($pieces[$product_obj->getId()] as $store_id=>$piece_arr){
                            if (isset($store_objs[$store_id])){
                                $store_obj=$store_objs[$store_id];
                                $row_piece = new HTMLTableRow();
                                $row_piece->addCell($store_obj->getName());
                                $row_piece->addNumberInputCell($piece_arr["piece"], $name='', "Product.changePiece(".$product_obj->getId().",$store_id)","id_product_piece_".$product_obj->getId()."_"."$store_id");
                                $table_piece->addRow($row_piece);
                            }
                        }
                        
                    }
                    $row->addCell($table_piece);
                    
                    
                    $kep_db=count($product_obj->getImageObjs());
                    if (empty($kep_db)){
                        $txt=lang("product_default_image");
                    }else {
                        $txt=lang("product_image");
                        $txt->replace("#db#", $kep_db);
                    }
                    $row->addCell($txt);
                    if ($this->hasVariant()){
                        $variant_value_objs=$product_obj->getVariantValueObjs();
                        $arr=array();
                        foreach($variant_value_objs as $variant_value_obj){
                            if ($variant_value_obj instanceof ProductVariantValueObj){
                                $variant_obj=$this->getVariantObj($variant_value_obj->getProductCollectVariantId());
                                $arr[]=$variant_obj->getName().": ".$variant_obj->getVariantValueName($variant_value_obj->getProductCollectVariantValueId());
                            }
                        }
                        $row->addCell(implode("; ",$arr));
                    }else {
                        $row->addCell(lang('product_no_variants'));
                    }
                    $row->addEditCell('javascript:void(0)','Product.edit('.$product_obj->getId().')');
                    $row->addDeleteCell('Product.del('.$product_obj->getId().')');
                    $table->addRow($row);
                }
            }
        }
        return $table;
    }
    
    
    
    /**** TAG ****/
    
    public function loadTagObjs(){
        try{
            $this->setTagObjs(TagManager::searchTags(ReferenceManager::TYPE_PRODUCT, $this->getId()));
            $this->setTagIds(array_keys($this->getTagObjs()));
        } catch (Exception $e) {
            Log::dump($e->getMessage());
        }
        
    }
    /**
     *  A gyűjtőhöz tartozó Tag objektumok betöltése tömegesen
     */
     
    public static function loadTagObjsMassive($product_collect_objs=array()){
        if (!is_array($product_collect_objs)){
            $product_collect_objs[$product_collect_objs->getId()]=$product_collect_objs;
        }
        
        $refids_sql = implode(',',  array_keys($product_collect_objs));
        
        $tagids = SQL::query("SELECT tagid, refid_1 FROM tag_reference WHERE refid_1 IN ($refids_sql)", 'webshop')->fetchSimpleData();
        $tags = TagManagerService::getTagsByTagids(array_keys($tagids));
        
        $tags_to_refids = array();
        
        foreach ($tagids as $tag_id => $ref_id){
            if(!isset($tags_to_refids[$ref_id])) $tags_to_refids[$ref_id] = array();
            if(isset($tags[$tag_id])){
                $tags_to_refids[$ref_id][$tag_id] = $tags[$tag_id];
            }
        }
        
        foreach ($product_collect_objs as $ref_id => $prod_obj){
            if(($prod_obj instanceof self) &&  isset($tags_to_refids[$ref_id])){
                $prod_obj->setTagObjs($tags_to_refids[$ref_id]);
            }else{
                $prod_obj->setTagObjs(array());
            }
        }
        
        return $product_collect_objs;
    }
    
    public function getTagIdsJson(){
        return json_encode($this->tag_ids);
    }
    public function getTagNamesJson(){
        $arr=array();
        foreach($this->tag_objs as $obj){
            if($obj instanceof TagObj){
                $arr[]=$obj->getName();
            }
        }
        return json_encode($arr);
    }
    public function getTagNames(){
        $arr=array();
        foreach($this->tag_objs as $obj){
            if($obj instanceof TagObj){
                $arr[]=$obj->getName();
            }
        }
        return $arr;
    }
    
    
    public function setTagObjs($val){
        $this->tag_objs = $val;
    }
    
    public function setTagIds($val){
        $this->tag_ids = $val;
    }
    
    public function getTagObjs(){
        return $this->tag_objs;
    }
    
    public function getTagIds(){
        return $this->tag_ids;
    }
    
    public function saveTags(){
        if(!empty($this->tag_ids)){
            TagManager::deleteAll(ReferenceManager::TYPE_PRODUCT, $this->getId());
            $add_tags = array(
                'id' => array(),
                'name' => array(),
            );
            foreach($this->tag_ids as $tag){
                if(is_numeric($tag)){
                    $add_tags['id'][] = $tag;
                }else{
                    $add_tags['name'][] = trim($tag);
                }
            }
            TagManager::addTagids(ReferenceManager::TYPE_PRODUCT, $this->getId() , $add_tags['id']);
            TagManager::addTagNames(ReferenceManager::TYPE_PRODUCT, $this->getId() , $add_tags['name']);
        }
    }
    
    
    /**
     * Product szerkesztésekor, a lehetséges variáns selectek visszaadása
     * @param type $product_id
     * @return \Template
     */
    public function getProductVariants($product_id){
        $this->loadAllForProduct();
        switch ($this->getVariantCount()){
            default:
            case 0:
            {
                $template=new Template(lang('product_no_variants')->getContent(),false);
            }
            break;
            case 1:
            {
                $template=Template::loadTemplate("product_form_variant_select.html", "Product");
                
                $variant_obj=reset($this->variant_objs);
                
                if ($variant_obj instanceof ProductCollectVariantObj){
                    $template->replace("::name::", $variant_obj->getName());
                    $template->replace("::variant_id::", $variant_obj->getId());
                    
                    $product_obj=$this->getProductObj($product_id);
                    $variant_value_objs=$product_obj->getVariantValueObjs();
                    if (is_array($variant_value_objs) && !empty($variant_value_objs)){
                        $variant_value_obj=reset($variant_value_objs);
                    }else {
                        $variant_value_obj=null;
                    }
                    if ($variant_value_obj instanceof ProductVariantValueObj){
                        $selected=$variant_value_obj->getProductCollectVariantValueId();
                    }else {
                        $selected=0;
                    }
                    $template->replace("::option_variants::", $variant_obj->getVariantValuesArrOptions($selected));

                }
            }
            break;
            case 2:
            {   
                $template=new Template('',false);
                $template_def=Template::loadTemplate("product_form_variant_select.html", "Product");
                $variant_obj_keys=array_keys($this->getVariantObjs());
                $variant_obj_1=$this->getVariantObj($variant_obj_keys[0]);
                $variant_obj_2=$this->getVariantObj($variant_obj_keys[1]);

                $product_obj=$this->getProductObj($product_id);

                $variant_value_objs=$product_obj->getVariantValueObjs();
                if (is_array($variant_value_objs) && !empty($variant_value_objs)){
                    $variant_value_obj_keys=array_keys($variant_value_objs);
                    $variant_value_obj_1=$variant_value_objs[$variant_value_obj_keys[0]];
                    $variant_value_obj_2=$variant_value_objs[$variant_value_obj_keys[1]];
                }else {
                    $variant_value_obj_1=null;
                    $variant_value_obj_2=null;
                }
                if ($variant_obj_1 instanceof ProductCollectVariantObj){
                    $template_def->replace("::name::", $variant_obj_1->getName());
                    if ($variant_value_obj_1 instanceof ProductVariantValueObj &&
                        $variant_value_obj_2 instanceof ProductVariantValueObj){
                        $variant_value_obj=($variant_obj_1->getId()==$variant_value_obj_1->getProductCollectVariantId())?$variant_value_obj_1:$variant_value_obj_2;
                        $selected=$variant_value_obj->getProductCollectVariantValueId();
                    }else {
                        $selected=0;
                    }
                    $template_def->replace("::option_variants::", $variant_obj_1->getVariantValuesArrOptions($selected));
                    $template_def->replace("::variant_id::", $variant_obj_1->getId());

                }
                $template->concat($template_def);
                $template_def->reset();
                if ($variant_obj_2 instanceof ProductCollectVariantObj){
                    $template_def->replace("::name::", $variant_obj_2->getName());
                    if ($variant_value_obj_1 instanceof ProductVariantValueObj &&
                        $variant_value_obj_2 instanceof ProductVariantValueObj){
                        $variant_value_obj=($variant_obj_2->getId()==$variant_value_obj_1->getProductCollectVariantId())?$variant_value_obj_1:$variant_value_obj_2;
                        $selected=$variant_value_obj->getProductCollectVariantValueId();
                    }else {
                        $selected=0;
                    }
                    $template_def->replace("::option_variants::", $variant_obj_2->getVariantValuesArrOptions($selected));
                    $template_def->replace("::variant_id::", $variant_obj_2->getId());
                }
                $template->concat($template_def);
            }
            break;
        }

        return $template;    
    }
    
    /**
     * Kategória visszaadása, a gyökér lekérdezésével, ha nincs beállítva
     * @return type
     */
    public function _getCategoryId(){
        $category_id=$this->getCategoryId();
        if (empty($category_id)){
            return ProductCategoryObj::getTopId();
        }else {
            return $category_id;
        }
    }
}