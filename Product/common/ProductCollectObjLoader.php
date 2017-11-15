<?php
/**
 * Description of ProductCollectObjLoader
 *
 * @author csabe
 */
class ProductCollectObjLoader {
    
    
    const LOAD_PRODUCT_COLLECT_TYPE             =   'load_type';
    const LOAD_PRODUCT_COLLECT_PROPERTY_NAME    =   'load_property_name';
    const LOAD_PRODUCT_COLLECT_VARIANT          =   'load_variant';
    const LOAD_PRODUCT_COLLECT_PRODUCT          =   'load_product';
    const LOAD_PRODUCT_COLLECT_IMAGE            =   'load_image';
    const LOAD_PRODUCT_COLLECT_TAG              =   'load_tag';
    
    
    
    
    
    /**
     * @return ProductCollectObjLoader
     */
    public static function getInstance() {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }
    
    
    /**
     * Egy objektum
     * @var type 
     */
    private $single = false;
    
    /**
     *
     * @var \ProductCollectObj
     */
    private $product_collect_objs = array();
    
    private $ids_in_keys = true;
    private $product_collect_ids_orig = array();
    private $product_collect_ids = array();
    private $product_collect_ids_str = array();
    
    
    /**
     * Betöltve
     * @var type 
     */
    private $loaded = false;
    
    /**
     * Opciók, betöltésre
     * @var type 
     */
    private $load_options = array(
        self::LOAD_PRODUCT_COLLECT_TYPE             => true,
        self::LOAD_PRODUCT_COLLECT_PROPERTY_NAME    => true,
        self::LOAD_PRODUCT_COLLECT_VARIANT          => true,
        self::LOAD_PRODUCT_COLLECT_PRODUCT          => true,
        self::LOAD_PRODUCT_COLLECT_IMAGE            => true,
        self::LOAD_PRODUCT_COLLECT_TAG              => true,
    );
    
    
    
    /**
     * Opciók beállítása
     * @param type $option
     * @param type $value
     * @return \ProductCollectObjLoader
     */
    public function setLoadOption($option, $value=false){
        if(is_array($option)){
            foreach($option as $opt => $val){
                $this->load_options[$opt] = $val;
            }
        }elseif(is_string($option)){
            $this->load_options[$option] = $value;
        }
        return $this;
    }
    
    /**
     * Opció lekérdezése
     * @param type $option
     * @return type
     */
    public function getOption($option){
        return (isset($this->load_options[$option])) ? $this->load_options[$option] : null ;
    }
    
    /**
     * 
     * @param type $ids
     * @param type $ids_in_keys
     * @return \ProductCollectObjLoader
     */
    public function setProductCollectIds($ids, $ids_in_keys = false){

        if(!is_array($ids)){
            $this->single = true;
            $ids = array($ids => $ids);
        }
        
        $this->ids_in_keys = $ids_in_keys;
        $this->product_collect_ids_orig = $ids;
        
        if($this->ids_in_keys) $ids = array_keys($ids);
            
        $this->product_collect_ids = $ids;
        $this->product_collect_ids_str = implode(',',$ids);
        
        return $this;
    }
    
    protected function load(){
        if(!$this->loaded && !empty($this->product_collect_ids)){
            $this->loadProductCollectObjs();

            if ($this->load_options[self::LOAD_PRODUCT_COLLECT_TYPE]) {
                $this->product_collect_objs=ProductCollectObj::loadTypeObjMassive($this->product_collect_objs);
            }

            if ($this->load_options[self::LOAD_PRODUCT_COLLECT_PROPERTY_NAME]) {
                $this->product_collect_objs=ProductCollectObj::loadPropertyNameObjsMassive($this->product_collect_objs);
            }

            if ($this->load_options[self::LOAD_PRODUCT_COLLECT_VARIANT]) {
                $this->product_collect_objs=ProductCollectObj::loadVariantObjsMassive($this->product_collect_objs);
            }

            if ($this->load_options[self::LOAD_PRODUCT_COLLECT_PRODUCT]) {
                $this->product_collect_objs=ProductCollectObj::loadProductObjsMassive($this->product_collect_objs);
            }
            
            if ($this->load_options[self::LOAD_PRODUCT_COLLECT_IMAGE]) {
                $this->product_collect_objs=ProductCollectObj::loadImageObjsMassive($this->product_collect_objs);
            }
            
            if ($this->load_options[self::LOAD_PRODUCT_COLLECT_TAG]) {
                $this->product_collect_objs=ProductCollectObj::loadTagObjsMassive($this->product_collect_objs);
            }
          
            $this->loaded = true;
        }
    }
    protected function loadProductCollectObjs(){
        try {
            $this->product_collect_objs = ProductCollectObj::getInstance()->load($this->product_collect_ids);
        } catch (DatabaseObjException $e) {}
    }
    
    /**
     * 
     * @return type ProductCollectObj
     */
    public function getObjs() {
        $return = array();
        if(!$this->loaded){
            $this->load();
        }
        if($this->loaded && !empty($this->product_collect_objs)){
            if($this->ids_in_keys){
                foreach($this->product_collect_ids_orig as $product_collect_id => $v){
                    if(isset($this->product_collect_objs[$product_collect_id])){
                        $return[$product_collect_id] = $this->product_collect_objs[$product_collect_id];
                    }
                }
            }else{
                
                foreach($this->product_collect_ids_orig as $product_collect_id){
                    if(isset($this->product_collect_objs[$product_collect_id])){
                        $return[$product_collect_id] = $this->product_collect_objs[$product_collect_id];
                    }
                }
            }
        }
        
        if($this->single){
            $return = reset($return);
        }
        return $return;
    }
    
}
