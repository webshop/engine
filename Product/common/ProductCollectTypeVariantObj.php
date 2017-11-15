<?php

class ProductCollectTypeVariantObj extends DatabaseObj {

    const _PRODUCT_COLLECT_TYPE_ID  =   'product_collect_type_id';
    const _PRODUCT_COLLECT_VARIANT_ID=   'product_collect_variant_id';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_collect_type_variant';
    protected $fields = [
        self::_PRODUCT_COLLECT_TYPE_ID,self::_PRODUCT_COLLECT_VARIANT_ID
    ];
    protected $key_fields=[self::_PRODUCT_COLLECT_TYPE_ID => self::_PRODUCT_COLLECT_TYPE_ID, self::_PRODUCT_COLLECT_VARIANT_ID => self::_PRODUCT_COLLECT_VARIANT_ID];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_PRODUCT_COLLECT_TYPE_ID => 0,
            self::_PRODUCT_COLLECT_VARIANT_ID => 0
        ]);
    }

    public function getProductCollectTypeId() { return $this->getValue(self::_PRODUCT_COLLECT_TYPE_ID); }
    public function getProductCollectVariantId() { return $this->getValue(self::_PRODUCT_COLLECT_VARIANT_ID); }


    public function setProductCollectTypeId($value) { $this->setValue(self::_PRODUCT_COLLECT_TYPE_ID, $value); }
    public function setProductCollectVariantId($value) { $this->setValue(self::_PRODUCT_COLLECT_VARIANT_ID, $value); }



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
            'safe' => ['product_collect_type_id', 'product_collect_variant_id'],
        ];
    }
    
    
    /*******
     * ADDED
     ******/
    const NO_VARIANT_ID =0;
    
    /**
     * A variánsok visszaadása típus függvényében
     * @param type $product_collect_type_id
     * @param type $obj objektumként legyen-e a visszatérési érték
     * @return type
     */
    public static function getVarianstByTypeId($product_collect_type_id,$obj=false){
        if ($obj){
            return self::getInstance()->loadByQuery("WHERE product_collect_type_id=$product_collect_type_id");
        }else {
            return SQL::query("SELECT product_collect_variant_id FROM product_collect_type_variant WHERE product_collect_type_id=$product_collect_type_id","webshop_product")->fetchListData();
        }
    }
    
    
    /**
     * A variánsok tömeges visszaadása típus függvényében
     * @param type $product_collect_type_ids
     * @param type $ids_in_keys
     * @return type
     */
     
    public static function getVarianstByTypeIdMassive($product_collect_type_ids,$ids_in_keys=true){
        if (!is_array($product_collect_type_ids)){
            $product_collect_type_ids[]=$product_collect_type_ids;
        }
        if ($ids_in_keys){
            $product_collect_type_ids[]=array_keys($product_collect_type_ids);
        }
        return SQL::query("SELECT product_collect_type_id,product_collect_variant_id FROM product_collect_type_variant WHERE product_collect_type_id IN (".implode(",",$product_collect_type_ids).")","webshop_product")->fetchMultiData("product_collect_type_id","product_collect_variant_id");
    }
}