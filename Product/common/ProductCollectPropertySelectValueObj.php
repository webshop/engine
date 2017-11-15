<?php

class ProductCollectPropertySelectValueObj extends DatabaseObj {

    const _ID                       =   'id';
    const _PRODUCT_COLLECT_PROPERTY_NAME_ID=   'product_collect_property_name_id';
    const _NAME                     =   'name';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_collect_property_select_value';
    protected $fields = [
        self::_ID,self::_PRODUCT_COLLECT_PROPERTY_NAME_ID,self::_NAME
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_ID => 0,
            self::_PRODUCT_COLLECT_PROPERTY_NAME_ID => 0,
            self::_NAME => ''
        ]);
    }

    public function getId() { return $this->getValue(self::_ID); }
    public function getProductCollectPropertyNameId() { return $this->getValue(self::_PRODUCT_COLLECT_PROPERTY_NAME_ID); }
    public function getName() { return $this->getValue(self::_NAME); }


    public function setId($value) { $this->setValue(self::_ID, $value); }
    public function setProductCollectPropertyNameId($value) { $this->setValue(self::_PRODUCT_COLLECT_PROPERTY_NAME_ID, $value); }
    public function setName($value) { $this->setValue(self::_NAME, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['product_collect_property_name_id, name', 'required', 'message' => 'Kötelezően megadandó!'],
            ['product_collect_property_name_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['name', 'length', 'max'=>500, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 500 karakter adható meg!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['id', 'product_collect_property_name_id', 'name'],
        ];
    }

}