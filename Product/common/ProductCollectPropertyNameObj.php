<?php

class ProductCollectPropertyNameObj extends DatabaseObj {

    const _ID                       =   'id';
    const _NAME                     =   'name';
    const _TYPE                     =   'type';
    const _PUBLIC                   =   'public';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_collect_property_name';
    protected $fields = [
        self::_ID,self::_NAME,self::_TYPE,self::_PUBLIC
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_ID => 0,
            self::_NAME => '',
            self::_TYPE => 0,
            self::_PUBLIC => 1
        ]);
    }

    public function getId() { return $this->getValue(self::_ID); }
    public function getName() { return $this->getValue(self::_NAME); }
    public function getType() { return $this->getValue(self::_TYPE); }
    public function getPublic() { return $this->getValue(self::_PUBLIC); }


    public function setId($value) { $this->setValue(self::_ID, $value); }
    public function setName($value) { $this->setValue(self::_NAME, $value); }
    public function setType($value) { $this->setValue(self::_TYPE, $value); }
    public function setPublic($value) { $this->setValue(self::_PUBLIC, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['name, type, public', 'required', 'message' => 'Kötelezően megadandó!'],
            ['name', 'length', 'max'=>255, 'tooLong' => 'Hiba, túl hosszú adat! Maximum 255 karakter adható meg!'],
            ['type', 'numerical', 'integerOnly', 'message' => 'Hibás adat!'],
            ['public', 'numerical', 'integerOnly', 'message' => 'Hibás adat!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['id', 'name', 'type', 'public'],
        ];
    }


    
    
    
    /**************
     *** ADDED  ***
     *************/
    
    const PROPERTY_TYPE_INPUT    =   1;
    const PROPERTY_TYPE_SELECT   =   2;
    const PROPERTY_TYPE_CHECKBOX =   3;
    
    
    private $property_type=array(
        
        self::PROPERTY_TYPE_INPUT       =>  'property_type_input',
        self::PROPERTY_TYPE_SELECT      =>  'property_type_select',
        self::PROPERTY_TYPE_CHECKBOX    =>  'property_type_checkbox',
        
    );
    
    /**
     * Tulajdonság értékek
     * @var type 
     */
    private $property_values=array();
    
    /**
     * Aktuális tulajdonság érték, egy adott product_collect esetében
     * @var type 
     */
    private $product_collect_property_values=array();
    
    
    
    public function getPropertyTypes(){
        $return=array();
        foreach($this->property_type as $id=>$lang_name){
            $return[$id]=lang($lang_name);
        }
        return $return;
    }
    
    public function getPropertyTypesOptions($selected_item=0){
        $arr=$this->getPropertyTypes();
        return options_for_select($arr, $selected_item);
    }
    
    
    public function getPropertyTypeName($id=0){
        if (empty($id)){
            $id=$this->getType();
        }
        if (isset($this->property_type[$id])){
            return lang($this->property_type[$id]);
        }
        return 0;
    }
    public function isSelectVsCheckboxType(){
        if ($this->getType()!=self::PROPERTY_TYPE_INPUT){
            return true;
        }
        return false;
    }
    
    public function delSelectCheckboxValues($new_product_collect_property_type){
        if ($new_product_collect_property_type==self::PROPERTY_TYPE_INPUT && $this->getProductCollectPropertyType()!=self::PROPERTY_TYPE_INPUT){
            SQL::query("DELETE FROM product_collect_property_select_value WHERE product_collect_property_name_id=".$this->getId(),"webshop_product");
        }
    }
    
    public function addPropertyValues($key,$val){
        $this->property_values[$key]=$val;
    }
    
    public function getPropertyValues($implode=false,$option=false,$selected=0){
        if ($implode && is_array($this->property_values)){
            return implode(", ",$this->property_values);
        }elseif($option && is_array($this->property_values)){
            return options_for_select($this->property_values,$selected);
        }
        return $this->property_values;
    }
    
    /**
     * Setter
     * @param type $val
     */
    public function setPropertyValues($val){
        $this->property_values=$val;
    }
    
    
    public function addProductCollectPropertyValues($key,$val){
        $this->product_collect_property_values[$key]=$val;
    }
    
    public function getProductCollectPropertyValues(){
        return $this->product_collect_property_values;
    }
    
    public function getProductCollectPropertyValue($key){
        if (isset($this->product_collect_property_values[$key])){
            return $this->product_collect_property_values[$key];
        }
        return "";
    }
    
    /**
     * Aktuális értékek kinyerése típus alapján
     * @param type $type
     * @return type
     */
    public function getProductCollectPropertyValuesByType($type,$edit=true){
        $objs=$this->getProductCollectPropertyValues();
        Log::dump($type);
        switch ($type){
            default:
            case ProductCollectPropertyNameObj::PROPERTY_TYPE_INPUT:
            {
                if (empty($objs)){
                    $val="";
                }else {
                    $obj=reset($objs);
                    $val=$obj->getProductCollectPropertyValueOrSelectvsCheckboxValue();
                }
                
            }
            break;
            case ProductCollectPropertyNameObj::PROPERTY_TYPE_SELECT:
            {
                if (empty($objs)){
                     $val=0;
                }else {
                    $obj=reset($objs);
                    if ($edit){
                        $val=$obj->getProductCollectSelectValueId();
                    }else {
                        $val=$obj->getProductCollectPropertyValueOrSelectvsCheckboxValue();
                    }
                    
                }
            }
            break;
            case ProductCollectPropertyNameObj::PROPERTY_TYPE_CHECKBOX:
            {
                if (empty($objs)){
                    $val="";
                }else {
                    
                    $val=array();
                    foreach($objs as $id=>$obj){
                        $val[]=$obj->getSelectValue()->getName();
                    }
                    $val=implode(", ",$val);
                }
            }
            break;
        }
        return $val;
    }
    
    /**
     * Setter
     * @param type $val
     */
    public function setProductCollectPropertyValues($val){
        $this->product_collect_property_values=$val;
    }
    
    
    
    /**
     * Betölti a tulajdonsághoz tartozó összes lehetséges értéket
     */
    public function loadPropertyValue(){
        if ($this->isSelectVsCheckboxType()){
            $select_vs_checkbox_values=SQL::query("SELECT product_collect_property_name_id,id,name FROM product_collect_property_select_value WHERE product_collect_property_name_id=".$this->getId(),"webshop_product")->fetchMultiData("product_collect_property_name_id", "id");
            foreach($select_vs_checkbox_values[$this->getId()] as $id=>$val){
                $this->addPropertyValues($id, $val);
            }
        }
    }
    
    /**
     * Betölti a ProductCollectPropertyNameObj -hez tartozó összes lehetséges értéket a property_values-ba
     * @param type $product_collect_property_name_objs ProductCollectPropertyNameObj
     */
    public static function loadPropertyValues(&$product_collect_property_name_objs){
        $select_vs_checkbox_values=array();
        foreach($product_collect_property_name_objs as $product_collect_property_name_id=>$product_collect_property_name_obj){
            if ($product_collect_property_name_obj instanceof ProductCollectPropertyNameObj){
                if ($product_collect_property_name_obj->isSelectVsCheckboxType()){
                    $ids[]=$product_collect_property_name_id;
                }
            }
        }
        if (!empty($ids)){
            $select_vs_checkbox_values=SQL::query("SELECT product_collect_property_name_id,id,name FROM product_collect_property_select_value WHERE product_collect_property_name_id IN(".implode(",",$ids).")","webshop_product")->fetchMultiData("product_collect_property_name_id", "id");
            foreach($product_collect_property_name_objs as $product_collect_property_name_id=>$product_collect_property_name_obj){
                if ($product_collect_property_name_obj instanceof ProductCollectPropertyNameObj){
                    if (isset($select_vs_checkbox_values[$product_collect_property_name_id])){
                        foreach($select_vs_checkbox_values[$product_collect_property_name_id] as $id=>$val){
                            $product_collect_property_name_obj->addPropertyValues($id, $val["name"]);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Beállítja egy adott product_collect esetében az értékeket a product_collect_property_values-ba
     * @param type $product_collect_property_name_objs
     * @param type $product_collect_property_objs
     */
    public static function loadCollectPropertyValues($product_collect_property_name_objs,$product_collect_property_objs){
        //PROPERTIES
        if (!empty($product_collect_property_objs)){
            
            foreach($product_collect_property_name_objs as $product_collect_property_name_id=>$product_collect_property_name_obj){
                if ($product_collect_property_name_obj instanceof ProductCollectPropertyNameObj){
                    //PRODUCT COLLECT PROPERTY
                    foreach($product_collect_property_objs as $product_collect_property_id=>$product_collect_property_obj){
                        if ($product_collect_property_obj instanceof ProductCollectPropertyObj){
                            //ha az a tulajdonság
                            if ($product_collect_property_name_id==$product_collect_property_obj->getProductCollectPropertyNameId()){
                                $product_collect_property_name_obj->addProductCollectPropertyValues($product_collect_property_id,$product_collect_property_obj);
                            }
                        }
                    }
                }
            }
        }
        return $product_collect_property_name_objs;
    }
}