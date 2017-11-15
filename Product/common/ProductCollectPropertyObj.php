<?php

class ProductCollectPropertyObj extends DatabaseObj {

    const _ID                       =   'id';
    const _PRODUCT_COLLECT_ID       =   'product_collect_id';
    const _PRODUCT_COLLECT_PROPERTY_NAME_ID=   'product_collect_property_name_id';
    const _PRODUCT_COLLECT_SELECT_VALUE_ID=   'product_collect_select_value_id';
    const _PRODUCT_COLLECT_PROPERTY_VALUE=   'product_collect_property_value';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_collect_property';
    protected $fields = [
        self::_ID,self::_PRODUCT_COLLECT_ID,self::_PRODUCT_COLLECT_PROPERTY_NAME_ID,
        self::_PRODUCT_COLLECT_SELECT_VALUE_ID,self::_PRODUCT_COLLECT_PROPERTY_VALUE,
       
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_ID => 0,
            self::_PRODUCT_COLLECT_ID => 0,
            self::_PRODUCT_COLLECT_PROPERTY_NAME_ID => 0,
            self::_PRODUCT_COLLECT_SELECT_VALUE_ID => 0,
            self::_PRODUCT_COLLECT_PROPERTY_VALUE => ''
        ]);
    }

    public function getId() { return $this->getValue(self::_ID); }
    public function getProductCollectId() { return $this->getValue(self::_PRODUCT_COLLECT_ID); }
    public function getProductCollectPropertyNameId() { return $this->getValue(self::_PRODUCT_COLLECT_PROPERTY_NAME_ID); }
    public function getProductCollectSelectValueId() { return $this->getValue(self::_PRODUCT_COLLECT_SELECT_VALUE_ID); }
    public function getProductCollectPropertyValue() { return $this->getValue(self::_PRODUCT_COLLECT_PROPERTY_VALUE); }


    public function setId($value) { $this->setValue(self::_ID, $value); }
    public function setProductCollectId($value) { $this->setValue(self::_PRODUCT_COLLECT_ID, $value); }
    public function setProductCollectPropertyNameId($value) { $this->setValue(self::_PRODUCT_COLLECT_PROPERTY_NAME_ID, $value); }
    public function setProductCollectSelectValueId($value) { $this->setValue(self::_PRODUCT_COLLECT_SELECT_VALUE_ID, $value); }
    public function setProductCollectPropertyValue($value) { $this->setValue(self::_PRODUCT_COLLECT_PROPERTY_VALUE, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['product_collect_id, product_collect_property_name_id, product_collect_select_value_id, product_collect_property_value', 'required', 'message' => 'Kötelezően megadandó!'],
            ['product_collect_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['product_collect_property_name_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['product_collect_select_value_id', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['product_collect_property_value', 'length', 'max'=>500, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 500 karakter adható meg!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['id', 'product_collect_id', 'product_collect_property_name_id', 'product_collect_select_value_id', 'product_collect_property_value'],
        ];
    }
    
    /**
     * ADDED
     */
    
    
    
    /**
     * Az aktuális select vs. checkbox tényleges értéke,ami ProductCollectPropertySelectValueObj objektum
     * @var type ProductCollectPropertySelectValueObj
     */
    private $select_value="";
    
    
    /**
     * Betölti a select vagy checkbox aktuális értékét, obj-ként ProductCollectPropertySelectValueObj
     */
    public function loadSelectvsCheckboxValue(){
        if ($this->isSelectVsCheckboxValue()){
            $this->setSelectValue(ProductCollectPropertySelectValueObj::getInstance()->loadByQuerySimple("WHERE product_collect_property_name_id=".$this->getProductCollectPropertyValue()));
        }
    }
    
    /**
     * Setter
     * @param ProductCollectPropertySelectValueObj $val
     */
    public function setSelectValue(ProductCollectPropertySelectValueObj $val){
        $this->select_value=$val;
    }
    
    /**
     * 
     * @return type ProductCollectPropertySelectValueObj
     */
    public function getSelectValue(){
        return $this->select_value;
    }
    
    /**
     * Az érték visszaadása a tulajdonság típusa alapján
     * @return type
     */
    public function getProductCollectPropertyValueOrSelectvsCheckboxValue(){
        if ($this->isSelectVsCheckboxValue()){
            return $this->select_value->getName();
        }else {
            return $this->getProductCollectPropertyValue();
        }
    }
    
    
    /**
     * Input vagy Select vs Checkbox érték-e
     * @return boolean
     */
    public function isSelectVsCheckboxValue(){
        if ($this->getProductCollectSelectValueId()!=0){
            return true;
        }
        return false;
    }
    
    /**
     * Tulajdonság obj-nek betölti az aktuális select vs checkbox értékét obj-ként
     * @param type $product_collect_property_objs
     * @return type
     */
    public static function loadSelectvsCheckboxValues(&$product_collect_property_objs){
        $product_collect_property_select_value_ids=array();
        foreach ($product_collect_property_objs as $product_collect_property_obj){
            if ($product_collect_property_obj instanceof ProductCollectPropertyObj){
                if ($product_collect_property_obj->isSelectVsCheckboxValue()){
                    $product_collect_property_select_value_ids[]=$product_collect_property_obj->getProductCollectSelectValueId();
                }
            }
        }
        if (!empty($product_collect_property_select_value_ids)){
            $select_value_objs=ProductCollectPropertySelectValueObj::getInstance()->loadByQuery("WHERE id IN(".implode(",",$product_collect_property_select_value_ids).")");
        }
        foreach ($product_collect_property_objs as $product_collect_property_obj){
            if ($product_collect_property_obj instanceof ProductCollectPropertyObj){
                if (isset($select_value_objs[$product_collect_property_obj->getProductCollectSelectValueId()])){
                    $product_collect_property_obj->setSelectValue($select_value_objs[$product_collect_property_obj->getProductCollectSelectValueId()]);
                }
            }
        }    
    }
}