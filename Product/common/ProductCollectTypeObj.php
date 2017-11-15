<?php

class ProductCollectTypeObj extends DatabaseObj {

    const _ID                       =   'id';
    const _NAME                     =   'name';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_collect_type';
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
            ['name', 'length', 'min'=>2, 'tooShort' => 'Hiba, túl rövid adat! Minimum 2 karaktert kell megadni!'],
            ['name', 'length', 'max'=>128, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 128 karakter adható meg!']
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
    
    public static function getTypeName($type_id){
        return SQL::query("SELECT name FROM product_collect_type WHERE id=$type_id","webshop_product")->fetchValue(0);
    }
    
    
    /**
     * Típusok lekérdezése
     * @param type $objs loadByQuery vs. fetchSimpleData
     * @return type
     */
    public static function getTypes($objs=false){
        if ($objs){
            return self::getInstance()->loadByQuery("WHERE ORDER BY name");
        }
        return SQL::query("SELECT id,name FROM product_collect_type WHERE 1 ORDER BY name","webshop_product")->fetchSimpleData();
    } 
    /**
     * Típusok lekérdezése select optionbe
     * @param type $selected
     */
    public static function getTypesOptions($selected=0){
        $arr=self::getTypes(false);
        return options_for_select($arr,$selected);
    }

}