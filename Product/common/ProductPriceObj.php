<?php

class ProductPriceObj extends DatabaseObj {

    const _PRODUCT_ID               =   'product_id';
    const _PRODUCT_COLLECT_ID       =   'product_collect_id';
    const _COMPARE_AT_PRICE         =   'compare_at_price';
    const _PRICE                    =   'price';
    const _DISCOUNT_PRICE           =   'discount_price';
    const _DISCOUNT_ID              =   'discount_id';

    protected $sql_connection_id = 'webshop';
    

    protected $tablename = 'product_price';
    protected $fields = [
        self::_PRODUCT_ID,self::_PRODUCT_COLLECT_ID,self::_COMPARE_AT_PRICE,self::_PRICE,
        self::_DISCOUNT_PRICE,self::_DISCOUNT_ID
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_PRODUCT_ID => 0,
            self::_PRODUCT_COLLECT_ID => 0,
            self::_COMPARE_AT_PRICE => 0,
            self::_PRICE => 0,
            self::_DISCOUNT_PRICE => 0,
            self::_DISCOUNT_ID => 0
        ]);
    }

    public function getProductId() { return $this->getValue(self::_PRODUCT_ID); }
    public function getProductCollectId() { return $this->getValue(self::_PRODUCT_COLLECT_ID); }
    public function getCompareAtPrice() { return $this->getValue(self::_COMPARE_AT_PRICE); }
    public function getPrice() { return $this->getValue(self::_PRICE); }
    public function getDiscountPrice() { return $this->getValue(self::_DISCOUNT_PRICE); }
    public function getDiscountId() { return $this->getValue(self::_DISCOUNT_ID); }


    public function setProductId($value) { $this->setValue(self::_PRODUCT_ID, $value); }
    public function setProductCollectId($value) { $this->setValue(self::_PRODUCT_COLLECT_ID, $value); }
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
            ['product_collect_id, compare_at_price, price, discount_price, discount_id', 'required', 'message' => 'Kötelezően megadandó!'],
            ['product_collect_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['compare_at_price', 'numerical', 'message' => 'Hibás adat!'],
            ['price', 'numerical', 'message' => 'Hibás adat!'],
            ['discount_price', 'numerical', 'message' => 'Hibás adat!'],
            ['discount_id', 'numerical', 'message' => 'Hibás adat!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['product_id', 'product_collect_id', 'compare_at_price', 'price', 'discount_price', 'discount_id'],
        ];
    }

}