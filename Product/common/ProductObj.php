<?php

class ProductObj extends DatabaseObj {

    const _ID                       =   'id';
    const _PRODUCT_COLLECT_ID       =   'product_collect_id';
    const _NAME                     =   'name';
    const _STATUS                   =   'status';
    const _PIECE                    =   'piece';
    const _WEIGHT                   =   'weight';
    const _COMPARE_AT_PRICE         =   'compare_at_price';
    const _PRICE                    =   'price';
    const _DISCOUNT_PRICE           =   'discount_price';
    const _DISCOUNT_ID              =   'discount_id';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product';
    protected $fields = [
        self::_ID,self::_PRODUCT_COLLECT_ID,self::_NAME,self::_STATUS,self::_PIECE,self::_WEIGHT,
        self::_COMPARE_AT_PRICE,self::_PRICE,self::_DISCOUNT_PRICE,self::_DISCOUNT_ID,
       
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_ID => 0,
            self::_PRODUCT_COLLECT_ID => 0,
            self::_NAME => '',
            self::_STATUS => 0,
            self::_PIECE => 0,
            self::_WEIGHT => 0,
            self::_COMPARE_AT_PRICE => 0,
            self::_PRICE => 0,
            self::_DISCOUNT_PRICE => 0,
            self::_DISCOUNT_ID => 0
        ]);
    }

    public function getId() { return $this->getValue(self::_ID); }
    public function getProductCollectId() { return $this->getValue(self::_PRODUCT_COLLECT_ID); }
    public function getName() { return $this->getValue(self::_NAME); }
    public function getStatus() { return $this->getValue(self::_STATUS); }
    public function getPiece() { return $this->getValue(self::_PIECE); }
    public function getWeight() { return $this->getValue(self::_WEIGHT); }
    public function getCompareAtPrice() { return $this->getValue(self::_COMPARE_AT_PRICE); }
    
    /** MODIFIED **/
    public function getPrice() { return $this->getValue(self::_PRICE); }
    public function getDiscountPrice() { return $this->getValue(self::_DISCOUNT_PRICE); }
    public function getDiscountId() { return $this->getValue(self::_DISCOUNT_ID); }


    public function setId($value) { $this->setValue(self::_ID, $value); }
    public function setProductCollectId($value) { $this->setValue(self::_PRODUCT_COLLECT_ID, $value); }
    public function setName($value) { $this->setValue(self::_NAME, $value); }
    public function setStatus($value) { $this->setValue(self::_STATUS, $value); }
    public function setPiece($value) { $this->setValue(self::_PIECE, $value); }
    public function setWeight($value) { $this->setValue(self::_WEIGHT, $value); }
    public function setCompareAtPrice($value) { $this->setValue(self::_COMPARE_AT_PRICE, $value); }
    public function setPrice($value) { $this->setValue(self::_PRICE, $value); }
    public function setDiscountPrice($value) { $this->setValue(self::_DISCOUNT_PRICE, $value); }
    public function setDiscountId($value) { $this->setValue(self::_DISCOUNT_ID, $value); }


    
    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['product_collect_id, name, status, piece, weight, compare_at_price, price, discount_price, discount_id', 'required', 'message' => 'Kötelezően megadandó!'],
            ['product_collect_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['name', 'length', 'max'=>128, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 128 karakter adható meg!'],
            ['name', 'length', 'min'=>2, 'tooShort' => 'Hiba, túl rövid adat! Minimum 2 karaktert kell megadni!'],
            ['status', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['piece', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['weight', 'numerical', 'message' => 'Hibás adat!'],
            ['compare_at_price', 'numerical', 'message' => 'Hibás adat!'],
            ['price', 'numerical', 'message' => 'Hibás adat!'],
            ['discount_price', 'numerical', 'message' => 'Hibás adat!'],
            ['discount_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['id', 'product_collect_id', 'name', 'status', 'piece', 'weight', 'compare_at_price', 'price', 'discount_price', 'discount_id'],
        ];
    }


    
    
    
    /***
     * ADDED
     */
    public function getPriceWithDiscount(){
        if (!empty($this->getDiscountId())){
            return $this->getDiscountPrice();
        }
        return $this->getPrice();
    }
    public function getCompareAtPriceWithCurrency() { return CurrenciesManager::formatPrice($this->getCompareAtPrice()); }
    public function getPriceWithCurrency() { return CurrenciesManager::formatPrice($this->getPriceWithDiscount()); }
    public function getDiscountPriceWithCurrency() { return CurrenciesManager::formatPrice($this->getValue(self::_DISCOUNT_PRICE)); }
    
    const STATUS_ACTIVE     =   1;
    const STATUS_INACTIVE   =   2;
    
    private $status_arr=array(
        
        self::STATUS_ACTIVE         =>  'product_status_active',
        self::STATUS_INACTIVE       =>  'product_status_inactive',
        
    );
    
    /**
     * Kép objektumok
     * @var type 
     */
    protected $image_objs = array();
    
    
    
    /**
     *
     * @var type ProductVariantValueObj
     */
    protected $variant_value_objs;
    
    
    
    
    protected $piece_objs;
    
    
    
    /**
     * Az ára felül lett-e írva
     * @var type 
     */
    protected $price_overrided=0;
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
    
    public function isAvailable(){
        return ($this->getStatus() == self::STATUS_ACTIVE) ? true : false ;
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
    
    public function getDiscountPercent(){
        $price = 0;
        if($this->getDiscountId() > 0){
            $price = $this->getDiscountPrice();
        }else{
            $price = $this->getPrice();
        }
        return round((1 - ($price / $this->getCompareAtPrice())) * 100, 1);
    }
    
    /**
     * Producthoz tartozó variáns értékek betöltése
     */
    public function loadVariantValueObj(){
        $this->variant_value_objs=ProductVariantValueObj::getInstance()->loadByQuerySimple("WHERE product_id=".$this->getId());
    }
    
    
    /**
     * Setter
     * @param type $val
     */
    public function setVariantValueObj($val){
        $this->variant_value_objs=$val;
    }
    
    /**
     * 
     * @return type ProductVariantValueObj
     */
    public function getVariantValueObjs($reset=0){
        if (count($this->variant_value_objs)==1 && $reset){
            return reset($this->variant_value_objs);
        }
        return $this->variant_value_objs;
    }
    
    public function getVariantValueObjsArr(){
        $arr=array();
        foreach($this->variant_value_objs as $variant_value_obj){
            if ($variant_value_obj instanceof ProductVariantValueObj){
                $arr[$variant_value_obj->getProductCollectVariantId()]=$variant_value_obj->getProductCollectVariantValueId();
            }
        }
        return $arr;
    }
    
    /**
     * Tömeges variáns érték beállítás, product objektum array-ok esetén
     * @param type $product_objs
     */
    public static function loadVariantValueObjs($product_objs){
        if (!empty($product_objs)){
            $variant_value_objs=ProductVariantValueObj::getInstance()->loadByQuery("WHERE product_id IN(".  implode(",",array_keys($product_objs)).")");
            foreach($product_objs as $product_obj){
                $variant_obj_values=array();
                foreach($variant_value_objs as $variant_value_obj){
                    if ($variant_value_obj->getProductId()==$product_obj->getId()){
                        $variant_obj_values[]=$variant_value_obj;
                    }
                }
                $product_obj->setVariantValueObj($variant_obj_values);
            }
        }
        return $product_objs;
    } 
    
    /**
     * Variánsok mentése
     * @param type $variants
     */
    public function saveVariants($variants){
        SQL::query("DELETE FROM product_variant_value WHERE product_id=".$this->getId(),"webshop_product");
        if (!empty($variants)){
            foreach($variants as $variant_id=>$variant_value_id){
                $variant_value=new ProductVariantValueObj();
                $variant_value->setProductId($this->getId());
                $variant_value->setProductCollectVariantId($variant_id);
                $variant_value->setProductCollectVariantValueId($variant_value_id);
                $variant_value->save();
            }
        }
    }
    
    //PIECES
    public function getPieces(){
        return ProductPieceObj::getPiecesByProductId($this->getId());
    }
    
    public function savePieces($pieces){
        foreach($pieces as $store_id=>$piece){
            SQL::query("REPLACE INTO product_piece SET product_id=".$this->getId().",piece=$piece,store_id=$store_id","webshop_product");
        }
    }
    /**
     * Az aktuális darabot beupdate-li a beállításoknak megfelelően
     */
    public function updatePiece(){
        Log::dump("update piece");
        $pieces_settings=Application::getCurrentWebshopObj()->getProductPieceSettings();
        $pieces=$this->getPieces();
        $piece=0;
        if ($pieces_settings==WebshopObj::_STORE_SETTINGS_PRODUCT_PIECE_ALL){
            foreach($pieces as $store_id=>$store_arr){
                $piece+=$store_arr["store_piece"];
            }
        }elseif ($pieces_settings==WebshopObj::_STORE_SETTINGS_PRODUCT_PIECE_JUST_WEBSHOP){
            $online_id=StoreObj::getOnlineId();
            if (isset($pieces[$online_id])){
                $piece=$pieces[$online_id]["store_piece"];
            }
        }
        $this->setPiece($piece);
        $this->save();
    }
    
    public function delete(){
        SQL::query("DELETE FROM product_variant_value WHERE product_id IN(".$this->getId().")","webshop_product");
        parent::delete();
    }
    
    /**** KÉPEK ****/
    public function loadImageObjs(){
        $this->setImageObjs(ImageManagerService::getImageObj($this->getId(), ImageManager::TYPE_PRODUCT));
    }
    /**
     * Tömeges képbeállítás
     * @param type $product_objs
     * @return type
     */
    public static function loadImageObjsMassive($product_objs=array(),$with_product_collect=false){
        if (!empty($product_objs)){
            if (!is_array($product_objs)){
                $product_objs[$product_objs->getId()]=$product_objs;
            }
            $product_collect_ids=array();
            foreach($product_objs as $product_obj){
                if ($product_obj instanceof ProductObj){
                    $product_collect_ids[$product_obj->getProductCollectId()]="";
                }
            }
            $image_objs=ImageManagerService::getImageObjs(array_keys($product_objs), ImageManager::TYPE_PRODUCT);
            $product_collect_image_objs=ImageManagerService::getImageObjs(array_keys($product_collect_ids), ImageManager::TYPE_PRODUCT_COLLECT);
            foreach($product_objs as $product_obj){
                $product_image_objs=array();
                foreach($image_objs as $image_obj){
                    if ($image_obj instanceof ImageManagerImageObjInterface){
                        if ($image_obj->getRefid()==$product_obj->getId()){
                            $product_image_objs[$image_obj->getId()]=$image_obj;
                        }
                    }
                }
                //COLLECT KÉPEI
                if ($with_product_collect && empty($product_image_objs)){
                    foreach($product_collect_image_objs as $image_obj){
                        if ($image_obj instanceof ImageManagerImageObjInterface){
                            if ($image_obj->getRefid()==$product_obj->getProductCollectId()){
                                $product_image_objs[$image_obj->getId()]=$image_obj;
                            }
                        }
                    }
                }
                $product_obj->setImageObjs($product_image_objs);
            }
        }
        return $product_objs;
    }
    
    public function hasImage(){
        if (!empty($this->image_objs)){
            return true;
        }
        return false;
    }
    
    /**
     * Image
     * @param type $val
     */
    public function setImageObjs($val){
        $this->image_objs=$val;
    }
    /**
     * Product collect alapján állítja be a product képét, ha nincs
     */  
    public function setFirstImageByProductCollect(){
        if (empty($this->image_objs)){
            $product_collect_obj=  ProductCollectObj::getInstance()->load($this->getProductCollectId(),false);
            if ($product_collect_obj instanceof ProductCollectObj){
                $product_collect_obj->loadImageObjs();
                $this->setImageObjs($product_collect_obj->getImageObjs());
            }
        }
    }
    
    
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
    
    public function getFirstImageObj(){
        if (!empty($this->image_objs)){
            foreach($this->image_objs as $image_obj){
                if ($image_obj->getFirst()!=0){
                    return $image_obj;
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
            return $imgs;
        }
        return "";
    }
    
    public function getProductCollectLink($handle = ''){
        return url_for('@product_collect_view_by_handle?product_handle='.$handle);
    }
    
    
    
    private $category_id = 0;
    
    public function setCategryId($val){
        $this->category_id = $val;
    }
    /**
     * Csak akkor működik, ha előtte besettelted. Autómatikusan nem tölti be a category_id-t
     * @return type
     */
    public function getCategoryId(){
        return $this->category_id;
    }
    
    
    public function priceOverride(){
        $product_price=self::getPrices(array($this->getId()=>$this->getId()));
        if (!empty($product_price)){
            $this->setCompareAtPrice($product_price[$this->getId()]["compare_at_price"]);
            $this->setPrice($product_price[$this->getId()]["price"]);
            $this->setDiscountPrice($product_price[$this->getId()]["discount_price"]);
            $this->setDiscountId($product_price[$this->getId()]["discount_id"]);
            $this->price_overrided=1;
        }
    }
    
    public function setPriceOverrided($val){
        $this->price_overrided=$val;
    }
    
    public function getPriceOverrided(){
        return $this->price_overrided;
    }
    
    

    
    public static function priceOverrides($product_objs){
        if (is_array($product_objs) && !empty($product_objs)){
            $product_prices=self::getPrices($product_objs);
            foreach($product_prices as $product_id=>$product_price){
                if (isset($product_objs[$product_id])){
                    $product_obj=$product_objs[$product_id];
                    if ($product_obj instanceof ProductObj){
                        $product_obj->setCompareAtPrice($product_price["compare_at_price"]);
                        $product_obj->setPrice($product_price["price"]);
                        $product_obj->setDiscountPrice($product_price["discount_price"]);
                        $product_obj->setDiscountId($product_price["discount_id"]);
                        $product_obj->setPriceOverrided(1);
                        $product_objs[$product_id]=$product_obj;
                    }
                }
            }
        }
        return $product_objs;
    }
    private static function getPrices($product_objs){
        return SQL::query("SELECT product_id,product_collect_id,compare_at_price,price,discount_price,discount_id FROM product_price WHERE product_id IN(".implode(",",array_keys($product_objs)).")","webshop")->fetchData("product_id");
            
    }
}