<?php

class ProductCollectVariantValueObj extends DatabaseObj {

    const _ID                       =   'id';
    const _PRODUCT_COLLECT_VARIANT_ID=   'product_collect_variant_id';
    const _NAME                     =   'name';
    const _SORTORDER                =   'sortorder';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_collect_variant_value';
    protected $fields = [
        self::_ID,self::_PRODUCT_COLLECT_VARIANT_ID,self::_NAME,self::_SORTORDER
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_ID => 0,
            self::_PRODUCT_COLLECT_VARIANT_ID => 0,
            self::_NAME => '',
            self::_SORTORDER => 0
        ]);
    }

    public function getId() { return $this->getValue(self::_ID); }
    public function getProductCollectVariantId() { return $this->getValue(self::_PRODUCT_COLLECT_VARIANT_ID); }
    public function getName() { return $this->getValue(self::_NAME); }
    public function getSortorder() { return $this->getValue(self::_SORTORDER); }


    public function setId($value) { $this->setValue(self::_ID, $value); }
    public function setProductCollectVariantId($value) { $this->setValue(self::_PRODUCT_COLLECT_VARIANT_ID, $value); }
    public function setName($value) { $this->setValue(self::_NAME, $value); }
    public function setSortorder($value) { $this->setValue(self::_SORTORDER, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['product_collect_variant_id, name, sortorder', 'required', 'message' => 'Kötelezően megadandó!'],
            ['product_collect_variant_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['name', 'length', 'max'=>128, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 128 karakter adható meg!'],
            ['sortorder', 'numerical', 'integerOnly', 'message' => 'Hibás adat!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['id', 'product_collect_variant_id', 'name', 'sortorder'],
        ];
    }
    
    
    
    
    

}