<?php

class ProductCollectTypePropertyObj extends DatabaseObj {

    const _PRODUCT_COLLECT_TYPE_ID  =   'product_collect_type_id';
    const _PRODUCT_COLLECT_PROPERTY_NAME_ID=   'product_collect_property_name_id';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_collect_type_property';
    protected $fields = [
        self::_PRODUCT_COLLECT_TYPE_ID,self::_PRODUCT_COLLECT_PROPERTY_NAME_ID
    ];
    protected $key_fields=[self::_PRODUCT_COLLECT_TYPE_ID => self::_PRODUCT_COLLECT_TYPE_ID, self::_PRODUCT_COLLECT_PROPERTY_NAME_ID => self::_PRODUCT_COLLECT_PROPERTY_NAME_ID];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_PRODUCT_COLLECT_TYPE_ID => 0,
            self::_PRODUCT_COLLECT_PROPERTY_NAME_ID => 0
        ]);
    }

    public function getProductCollectTypeId() { return $this->getValue(self::_PRODUCT_COLLECT_TYPE_ID); }
    public function getProductCollectPropertyNameId() { return $this->getValue(self::_PRODUCT_COLLECT_PROPERTY_NAME_ID); }


    public function setProductCollectTypeId($value) { $this->setValue(self::_PRODUCT_COLLECT_TYPE_ID, $value); }
    public function setProductCollectPropertyNameId($value) { $this->setValue(self::_PRODUCT_COLLECT_PROPERTY_NAME_ID, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['', 'required', 'message' => 'Kötelezően megadandó!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['product_collect_type_id', 'product_collect_property_name_id'],
        ];
    }
    
    /**
     * A tulajdonságok visszaadása típus függvényében
     * @param type $product_collect_type_id
     * @param type $obj objektumként legyen-e a visszatérési érték
     * @return type
     */
    public static function getPropertiesByTypeId($product_collect_type_id,$obj=false){
        if ($obj){
            return self::getInstance()->loadByQuery("WHERE product_collect_type_id=$product_collect_type_id");
        }else {
            return SQL::query("SELECT product_collect_property_name_id FROM product_collect_type_property WHERE product_collect_type_id=$product_collect_type_id","webshop_product")->fetchListData();
        }
    }
    
    /**
     * Tömeges tulajdonságok visszaadása típusok függvényében
     * @param type $product_collect_type_ids
     * @param type $ids_in_keys
     * @return type
     */
     
    public static function getPropertiesByTypeIdMassive($product_collect_type_ids,$ids_in_keys=true){
        if (!is_array($product_collect_type_ids)){
            $product_collect_type_ids[]=$product_collect_type_ids;
        }
        if ($ids_in_keys){
            $product_collect_type_ids[]=array_keys($product_collect_type_ids);
        }
        return SQL::query("SELECT product_collect_type_id,product_collect_property_name_id FROM product_collect_type_property WHERE product_collect_type_id IN (".implode(",",$product_collect_type_ids).")","webshop_product")->fetchMultiData("product_collect_type_id","product_collect_property_name_id");
        
    }
    

}