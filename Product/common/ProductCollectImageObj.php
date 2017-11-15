<?php

class ProductCollectImageObj extends DatabaseObj {

    const _ID                       =   'id';
    const _PRODUCT_COLLECT_ID       =   'product_collect_id';
    const _FIRST                    =   'first';

    protected $sql_connection_id = 'webshop';
    

    protected $tablename = 'product_collect_image';
    protected $fields = [
        self::_ID,self::_PRODUCT_COLLECT_ID,self::_FIRST
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_ID => 0,
            self::_PRODUCT_COLLECT_ID => 0,
            self::_FIRST => 0
        ]);
    }

    public function getId() { return $this->getValue(self::_ID); }
    public function getProductCollectId() { return $this->getValue(self::_PRODUCT_COLLECT_ID); }
    public function getFirst() { return $this->getValue(self::_FIRST); }


    public function setId($value) { $this->setValue(self::_ID, $value); }
    public function setProductCollectId($value) { $this->setValue(self::_PRODUCT_COLLECT_ID, $value); }
    public function setFirst($value) { $this->setValue(self::_FIRST, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['product_collect_id, first', 'required', 'message' => 'Kötelezően megadandó!'],
            ['product_collect_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['first', 'numerical', 'integerOnly', 'message' => 'Hibás adat!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['id', 'product_collect_id', 'first'],
        ];
    }

}