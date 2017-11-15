<?php

class ProductCollectVariantObj extends DatabaseObj {

    const _ID                       =   'id';
    const _NAME                     =   'name';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_collect_variant';
    protected $fields = [
        self::_ID,self::_NAME
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_ID => 0,
            self::_NAME => ''
        ]);
    }

    public function getId() { return $this->getValue(self::_ID); }
    public function getName() { return $this->getValue(self::_NAME); }


    public function setId($value) { $this->setValue(self::_ID, $value); }
    public function setName($value) { $this->setValue(self::_NAME, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['name', 'required', 'message' => 'Kötelezően megadandó!'],
            ['name', 'length', 'max'=>68, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 68 karakter adható meg!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['id', 'name'],
        ];
    }
    
    
    /**
     * A variáns értékei, objektum tömb
     * @var type 
     */
    private $variantValues=array();
    
    
    
    /**
     * Setter
     * @param type $val
     */   
    public function setVariantValues($val){
        $this->variantValues=$val;
    }
    
    /**
     * 
     * @return type ProductCollectVariantObj
     */
    public function getVariantValues(){
        return $this->variantValues;
    }
    
    /**
     * Tömbként adja vissza az értékeket
     * @return type
     */
    public function getVariantValuesArr(){
        $arr=array();
        foreach($this->variantValues as $variant_value_obj){
            if ($variant_value_obj instanceof ProductCollectVariantValueObj){
                $arr[$variant_value_obj->getId()]=$variant_value_obj->getName();
            }
        }
        return $arr;
    }
    
    /**
     * Options-ként adja vissza a variáns értékeket
     * @param type $selected_item
     * @return type
     */
    public function getVariantValuesArrOptions($selected_item){
        $arr=$this->getVariantValuesArr();
        return options_for_select($arr, $selected_item);
    }
    
    /**
     * Visszaadja a variáns nevét, adott kulcs alapján
     * @param type $variant_value_id
     * @return string
     */
    public function getVariantValueName($variant_value_id){
        if (isset($this->variantValues[$variant_value_id]) && $this->variantValues[$variant_value_id] instanceof ProductCollectVariantValueObj){
            return $this->variantValues[$variant_value_id]->getName();
        }else {
            return "";
        }
    }
    /**
     * Implode-olva adja vissza az értékeket, ","-vel elválasztva
     * @return type
     */
    public function getVariantValuesImplode(){
        $names = [];
        foreach($this->variantValues as $obj){
            $names[]=$obj->getName();
        }
        return implode(", ",$names);
    }
    
    /**
     * Tömeges variáns érték beállítás adott variánshoz, 
     * @param type $variant_objs_arr Variáns Objektum tömb
     */
    public static function loadVariantObjsValues(&$variant_objs_arr){
        if (!empty($variant_objs_arr)){
            $variantValues=ProductCollectVariantValueObj::getInstance()->loadByQuery("WHERE product_collect_variant_id IN(".  implode(",",array_keys($variant_objs_arr)).") ORDER BY sortorder ASC");
            if(!empty($variantValues)){
                foreach($variant_objs_arr as $obj_id=>$variant_obj){
                    $variant_obj_values=array();
                    foreach($variantValues as $id=>$variantValue){
                        if ($variantValue->getProductCollectVariantId()==$obj_id){
                            $variant_obj_values[$variantValue->getId()]=$variantValue;
                        }
                    }
                    $variant_obj->setVariantValues($variant_obj_values);
                }
            }
        }
    }
    
    
    
    
    
    
    
    

}