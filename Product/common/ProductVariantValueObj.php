<?php

class ProductVariantValueObj extends DatabaseObj {

    const _PRODUCT_ID               =   'product_id';
    const _PRODUCT_COLLECT_VARIANT_ID=   'product_collect_variant_id';
    const _PRODUCT_COLLECT_VARIANT_VALUE_ID=   'product_collect_variant_value_id';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_variant_value';
    protected $fields = [
        self::_PRODUCT_ID,self::_PRODUCT_COLLECT_VARIANT_ID,self::_PRODUCT_COLLECT_VARIANT_VALUE_ID,
       
    ];
    
    protected $key_fields=[self::_PRODUCT_ID => self::_PRODUCT_ID, self::_PRODUCT_COLLECT_VARIANT_ID => self::_PRODUCT_COLLECT_VARIANT_ID];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_PRODUCT_ID => 0,
            self::_PRODUCT_COLLECT_VARIANT_ID => 0,
            self::_PRODUCT_COLLECT_VARIANT_VALUE_ID => 0
        ]);
    }

    public function getProductId() { return $this->getValue(self::_PRODUCT_ID); }
    public function getProductCollectVariantId() { return $this->getValue(self::_PRODUCT_COLLECT_VARIANT_ID); }
    public function getProductCollectVariantValueId() { return $this->getValue(self::_PRODUCT_COLLECT_VARIANT_VALUE_ID); }


    public function setProductId($value) { $this->setValue(self::_PRODUCT_ID, $value); }
    public function setProductCollectVariantId($value) { $this->setValue(self::_PRODUCT_COLLECT_VARIANT_ID, $value); }
    public function setProductCollectVariantValueId($value) { $this->setValue(self::_PRODUCT_COLLECT_VARIANT_VALUE_ID, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['product_collect_variant_value_id', 'required', 'message' => 'Kötelezően megadandó!'],
            ['product_collect_variant_value_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['product_id', 'product_collect_variant_id', 'product_collect_variant_value_id'],
        ];
    }

}