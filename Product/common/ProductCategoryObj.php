<?php

class ProductCategoryObj extends DatabaseObj {

    const _ID                       =   'id';
    const _NAME                     =   'name';
    const _HANDLE                   =   'handle';
    const _LEFT                     =   'left';
    const _RIGHT                    =   'right';
    const _LEVEL                    =   'level';
    const _PARENT_ID                =   'parent_id';
    const _PARENT_IDS               =   'parent_ids';
    const _POPULARITY               =   'popularity';
    const _IMAGE_ID                 =   'image_id';
    const _IMAGE_URL                =   'image_url';
    const _DEFAULT_SORT_BY          =   'default_sort_by';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'category';
    protected $fields = [
        self::_ID,self::_NAME,self::_HANDLE,self::_LEFT,self::_RIGHT,self::_LEVEL,self::_PARENT_ID,
        self::_PARENT_IDS,self::_POPULARITY,self::_IMAGE_ID,self::_IMAGE_URL,self::_DEFAULT_SORT_BY,
       
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_ID => 0,
            self::_NAME => '',
            self::_HANDLE => '',
            self::_LEFT => 0,
            self::_RIGHT => 0,
            self::_LEVEL => 0,
            self::_PARENT_ID => 0,
            self::_PARENT_IDS => '',
            self::_POPULARITY => 0,
            self::_IMAGE_ID => 0,
            self::_IMAGE_URL => '',
            self::_DEFAULT_SORT_BY => ''
        ]);
    }

    public function getId() { return $this->getValue(self::_ID); }
    public function getName() { return $this->getValue(self::_NAME); }
    public function getHandle() { return $this->getValue(self::_HANDLE); }
    public function getLeft() { return $this->getValue(self::_LEFT); }
    public function getRight() { return $this->getValue(self::_RIGHT); }
    public function getLevel() { return $this->getValue(self::_LEVEL); }
    public function getParentId() { return $this->getValue(self::_PARENT_ID); }
    public function getParentIds() { return $this->getValue(self::_PARENT_IDS); }
    public function getPopularity() { return $this->getValue(self::_POPULARITY); }
    public function getImageId() { return $this->getValue(self::_IMAGE_ID); }
    public function getImageUrl() { return $this->getValue(self::_IMAGE_URL); }
    public function getDefaultSortBy() { return $this->getValue(self::_DEFAULT_SORT_BY); }


    public function setId($value) { $this->setValue(self::_ID, $value); }
    public function setName($value) { $this->setValue(self::_NAME, $value); }
    public function setHandle($value) { $this->setValue(self::_HANDLE, $value); }
    public function setLeft($value) { $this->setValue(self::_LEFT, $value); }
    public function setRight($value) { $this->setValue(self::_RIGHT, $value); }
    public function setLevel($value) { $this->setValue(self::_LEVEL, $value); }
    public function setParentId($value) { $this->setValue(self::_PARENT_ID, $value); }
    public function setParentIds($value) { $this->setValue(self::_PARENT_IDS, $value); }
    public function setPopularity($value) { $this->setValue(self::_POPULARITY, $value); }
    public function setImageId($value) { $this->setValue(self::_IMAGE_ID, $value); }
    public function setImageUrl($value) { $this->setValue(self::_IMAGE_URL, $value); }
    public function setDefaultSortBy($value) { $this->setValue(self::_DEFAULT_SORT_BY, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['name, handle, left, right, level, parent_id, parent_ids, popularity, image_id, image_url, default_sort_by', 'required', 'message' => 'Kötelezően megadandó!'],
            ['name', 'length', 'max'=>255, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 255 karakter adható meg!'],
            ['handle', 'length', 'max'=>200, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 200 karakter adható meg!'],
            ['left', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['right', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['level', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['parent_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['parent_ids', 'length', 'max'=>255, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 255 karakter adható meg!'],
            ['popularity', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['image_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['image_url', 'length', 'max'=>255, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 255 karakter adható meg!'],
            ['default_sort_by', 'length', 'max'=>100, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 100 karakter adható meg!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['id', 'name', 'handle', 'left', 'right', 'level', 'parent_id', 'parent_ids', 'popularity', 'image_id', 'image_url', 'default_sort_by'],
        ];
    }


    const LEVEL_MAIN = 1;
    const LEVEL_TOP = 2;
    const LEVEL_MIDDLE = 3;
    const LEVEL_PRODUCT = 4;

    const SORT_BY_MANUAL = 'manual';
    const SORT_BY_BEST_SELLING = 'best-selling';
    const SORT_BY_TITLE_ASCENDING = 'title-ascending';
    const SORT_BY_TITLE_DESCENDING = 'title-descending';
    const SORT_BY_PRICE_ASCENDING = 'price-ascending';
    const SORT_BY_PRICE_DESCENDING = 'price-descending';
    const SORT_BY_CREATED_ASCENDING = 'created-ascending';
    const SORT_BY_CREATED_DESCENDING = 'created-descending';
    
    protected static $sort_by_array  = array(
        self::SORT_BY_MANUAL => 'product_category_sort_by_manual',
        self::SORT_BY_BEST_SELLING => 'product_category_sort_by_best_selling',
        self::SORT_BY_TITLE_ASCENDING => 'product_category_sort_by_title_ascending',
        self::SORT_BY_TITLE_DESCENDING => 'product_category_sort_by_title_descending',
        self::SORT_BY_PRICE_ASCENDING => 'product_category_sort_by_price_ascending',
        self::SORT_BY_PRICE_DESCENDING => 'product_category_sort_by_price_descending',
        self::SORT_BY_CREATED_ASCENDING => 'product_category_sort_by_created_ascending',
        self::SORT_BY_CREATED_DESCENDING => 'product_category_sort_by_created_descending',
    );


    public static function getSortByArray(){
        return Language::translateArray(self::$sort_by_array);
    }

    /**
     * 
     * @param int $parent_id új kategória szülőjének Id-ja, ennek a végére kerül majd az új
     * @param string $name új kategória megnevezése
     * @return StoreCategoryObj az új objektum
     * @throws Exception ha nincs meg a $parent_id-ben meghatározott szülő
     */
    public static function insert($parent_id, $name){
        $store_category_parent = ProductCategoryObj::getInstance()->load($parent_id, false, false);
        if (!empty($store_category_parent)){
            $store_category = new ProductCategoryObj();
            $store_category->setParentId($parent_id);
            $store_category->setParentIds($store_category_parent->getParentIds());
            $store_category->setName($name);
            $store_category->setHandle(LiquidStandardFilters::handleize($name));
            $store_category->setLevel($store_category_parent->getLevel()+1);
            $store_category->setLeft($store_category_parent->getRight());
            $store_category->setRight($store_category_parent->getRight()+1);  // az előbb beállított +1, de lehetne parent_right+1 is
            $store_category->save();
            
            // left beállítás: ahol left > parent_right, ott left növelés 2-vel
            $sql = "UPDATE `category` SET `left`=`left`+2 WHERE `left`>".$store_category_parent->getRight();
            SQL::query($sql,"webshop_product");
            
            // right beállítás: ahol left >= parent_right, ott right növelés 2-vel (itt már a parent right-ot is növeli)
            $sql = "UPDATE `category` SET `right`=`right`+2 WHERE `right`>=".$store_category_parent->getRight();
            $sql.=" AND id<>".$store_category->getId();
            SQL::query($sql,"webshop_product");
            
            // parent_ids-ben benne van a saját ID is, ezért duplán kell menteni
            $store_category->setParentIds($store_category_parent->getParentIds().",".$store_category->getId());
            $store_category->save();
            return $store_category;
        }else{
            throw new Exception('Parent category not found!');
        }            
    }

    /**
     * Törli a kiválasztott kategória elemet<br>
     * Törli az összes gyermek elemet, ha volt neki (left > this->left && right < this->right)<br>
     * Az utána következőknek beállítja a left és right mezőjét (gyermekek számától függően csökkenti)
     */
    public function delete() {
        
        $difference = $this->getRight() - $this->getLeft() + 1;
        
        // Gyermekek törlése
        $sql = "DELETE FROM `category` WHERE `left`>".$this->getLeft() ." AND `right`<".$this->getRight();
        SQL::query($sql,"webshop_product");
        
        // Utána következő elemek left értékének csökkentése a gyermekek számának fügvényében
        $sql = "UPDATE `category` SET `left`=`left`-$difference WHERE `left`>".$this->getRight();
        SQL::query($sql,"webshop_product");

        // Utána következő elemek és a szülők right értékének csökkentése a gyermekek számának fügvényében
        $sql = "UPDATE `category` SET `right`=`right`-$difference WHERE `right`>".$this->getRight();
        SQL::query($sql,"webshop_product");

        parent::delete();
    }
    
    /**
     * Van-e gyermek eleme? Ha nincs, akkor lehet terméket rendelni hozzá
     * @return boolean
     */
    public function hasChild() {
        return $this->getRight()!=($this->getLeft()+1);
    }
    
    /**
     * A kategória szülő elemét adja vissza
     * @return StoreCategoryObj
     */
    public function getParentObj(){
        if (is_null( $this->parent_obj)){
            $this->parent_obj = self::getInstance()->load($this->getParentId(), false);
        }
        return $this->parent_obj;
    }
    
    /**
     * Az adott kategória összer leszármnazottjának ID-ját visszaadja (minden szinten)
     * @return array leszármazott kategóriák ID-ját tartalmezó tömb
     */
    public function getAllChildIds(){
        $ret = array();
        if ($this->hasChild()){
            $sql = "SELECT id FROM `category` WHERE `left`>".$this->getLeft()." AND `right`<".$this->getRight();
            $ret = SQL::query($sql,"webshop_product")->fetchListData();
        }
        return $ret;
    }
    
    /**
     * Népszrű kategóriák objektumait adja vissza szülőn belül
     * @param int $parent_id szülő kategória
     * @param int $item_per_level max ennyit
     * @return StoreCategoryObj
     */
    public static function getPopularCategories($left, $right, $level, $item_per_level=5){
        $query = "WHERE `left`>$left AND `right`<$right AND `level`=$level ORDER BY ".self::_POULARITY.", `left` LIMIT $item_per_level";
        return self::getInstance()->loadByQuery($query);
    }
    
    /**
     * foreachSection-hoz adja vissza a népszerű kategóriák adatait (név és URL)
     * @param int $level
     * @param int $item_per_level
     * @return array
     */
    public function getPopularData($level, $item_per_level=5){
        $data = array();
        $popular = self::getPopularCategories($this->getLeft(), $this->getRight(), $level, $item_per_level);
        foreach ($popular as $category){
            $data[] = array(
                "category_name" => $category->getNameLang(),
                "category_url" => $category->getURL(),
            );
        }
        $data[] = array(
            "category_name" => lang('store_category_all_sub_category')->getContent()."...",
            "category_url" => $this->getURL(),
        );
        return $data;
    }
    
    /**
     * Nyelvesített kategória nevet adja vissza
     * @return string
     */
    public function getNameLang(){
        $local_name = lang($this->getLangCode());
        if ($local_name == ''){
            $local_name = $this->getName();
        }
        return $local_name;
    }
    /**
     * kategória URL nyelvesítve, ha sikerül a fordítást kiolvasni.
     * @return string
     */
    public function getURL(){
//        if ($this->getLevel()==self::LEVEL_MAIN){
//            return "/product/";
//        }
        return self::_getUrl($this->getId(), $this->getName());
    }
    
    /**
     * Nyelvesített nevet ad vissza ah sikerül a fordítás, egyénként a $name-t
     * @param int $id
     * @param string $name
     * @return string
     */
    public static function _getNameLang($id, $name){
        $lang_name = lang(md5($id.$name))->getContent();
        return !empty($lang_name) ? $lang_name : $name;
    }
    
    /**
     * kategória URL nyelvesítve,
     * @param int $id
     * @param string $name
     * @return string
     */
    public static function _getUrl($id, $name){
        return url_for("@product_collect_list_by_category?category_id=$id&category_name=".Lib::string2URL(self::_getNameLang($id, $name)));
    }
    
    /**
     * Első gyermeket adja vissza
     * @return StoreCategoryObj
     */
    public function getFirstChild(){
        return self::getInstance()->loadByQuerySimple("WHERE parent_id=".$this->getId()." ORDER BY `left` LIMIT 1");
    }
    
    public static function getTopId(){
        return SQL::query("SELECT id FROM category WHERE level=1 AND parent_id=0","webshop_product")->fetchValue(0);
    }
    
    
    public function getParentIdsArray(){
        return explode(',', $this->getParentIds());
    }
}