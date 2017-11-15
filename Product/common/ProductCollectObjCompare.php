<?php

class ProductCollectObjCompare {
    //put your code here
    
    const SESSION_INDEX_IDS     =   "product_collect_compare_objs";
    const MAXIMUM_ELEMENT       =   4;
    
    
    /**
     * Gyűjtő objektumok tömbje
     * @var type ProductCollectObj
     */
    private $product_collect_objs=array();
    
    
    /**
     * Aktuális típus
     * @var type 
     */
    private $type=0;
    
    public function init(){
        if (Application::getSession()->is_set(self::SESSION_INDEX_IDS)){
            $this->product_collect_objs=Application::getSession()->get(self::SESSION_INDEX_IDS, array());
            $this->setTypeByObjs();
        }
    }
    
    public function __construct() {
        $this->init();
    }
    
    /**
     * Hozzáadás
     * @param ProductCollectObj $product_collect_obj
     */
    public function add(ProductCollectObj $product_collect_obj){
        $this->check($product_collect_obj);
        $this->product_collect_objs[$product_collect_obj->getId()]=$product_collect_obj;
        if (empty($this->type)){
            $this->setTypeByObjs();
        }
        $this->storeToSession();
    }
    
    public function getContent(){
        $content=Template::loadTemplate("product_collect_compare.html","Product");
        $data=array();
        foreach($this->product_collect_objs as $product_collect_obj){
            if ($product_collect_obj instanceof ProductCollectObj){
                $data[]=array(
                    "id"=>$product_collect_obj->getId(),
                    "img"=>$product_collect_obj->getFirstImage(ImageManager::FILTER_NAME_2, true, false),
                );
            }
        }
        $content->foreachSection("product_collect_item", $data,true);
        $content->replace("::db::",$this->getCount());
        return $content;
    }
    
    
    
    /**
     * SESSION-be mentés
     */
    public function storeToSession(){
        Application::getSession()->set(self::SESSION_INDEX_IDS, $this->product_collect_objs);
    }
    
    /**
     * Eltávolítás
     * @param type $product_collect_id
     */
    public function remove($product_collect_id){
        unset($this->product_collect_objs[$product_collect_id]);
        if (empty($this->product_collect_objs)){
            $this->setType(0);
        }
        $this->storeToSession();
    }
    
    /**
     * Eltávolít mindent
     */
    public function removeAll(){
        $this->product_collect_objs=array();
        $this->type=0;
        $this->storeToSession();
    }
    
    /**
     * Összehasonlítás megjelenítése
     * @return \Template
     * @throws ProductCollectObjCompareException
     */
    public function render(){
        try{
            if (empty($this->product_collect_objs)){
                throw new ProductCollectObjCompareException(lang("compare_empty_list"));
            }
            if ($this->getCount()==1){
                throw new ProductCollectObjCompareException(lang("compare_one_element"));
            }
            
            $div=new Template('<div style="width:180px;float:left;padding:10px;">::content::</div>',false);
            $clear=new Template('<div style="clear: both;"></div>',false);
            $item=Template::loadTemplate("product_collect_compare_item.html","Product");
            $content=new Template('<input type="hidden" name="on_compare_render" value="" id="id_on_compare_render">',false);
            $div->replace('::content::',lang('compare'));
            $content->concat($div);
            //ELSŐ SOR
            foreach($this->product_collect_objs as $product_collect_obj){
                if ($product_collect_obj instanceof ProductCollectObj){
                    $property_name_objs=$product_collect_obj->getPropertyNameObjs();
                    $item->reset();
                    $item->replaceHTML("::name::", $product_collect_obj->getName());
                    $item->replaceHTML("::first_img::", $product_collect_obj->getFirstImage(ImageManager::FILTER_NAME_2, true, false));
                    $item->replaceHTML("::price::", $product_collect_obj->getPriceWithCurrency());
                    $item->replaceHTML("::compare_at_price::", $product_collect_obj->getCompareAtPriceWithCurrency());
                    $item->replaceHTML("::id::", $product_collect_obj->getId());
                    $content->concat($item);
                }
            }
            $content->concat($clear);
            //PROPERTIES SOR
            foreach($property_name_objs as $property_name_obj){
                if ($property_name_obj instanceof ProductCollectPropertyNameObj){
                    $div->reset();
                    $div->replace("::content::",$property_name_obj->getName());
                    $content->concat($div);
                    foreach($this->product_collect_objs as $product_collect_obj){
                        if ($product_collect_obj instanceof ProductCollectObj){
                            $div->reset();
                            $collect_property_name_obj=$product_collect_obj->getPropertyNameObj($property_name_obj->getId());
                            $div->replace("::content::",$collect_property_name_obj->getProductCollectPropertyValuesByType($collect_property_name_obj->getType(),false));
                            $content->concat($div);
                        }
                    }    
                    $content->concat($clear);
                }
            }
            return $content;
        }  catch (ProductCollectObjCompareException $e){
            return new Template($e->getMessage(),false);
        }
    }
    
    
    /**
     * Ellenőrzés
     * @param ProductCollectObj $product_collect_obj
     * @throws ProductCollectObjCompareException
     */
    private function check(ProductCollectObj $product_collect_obj){
        if (!empty($this->type) && $this->type!=$product_collect_obj->getProductCollectTypeId()){
            throw new ProductCollectObjCompareException(lang("compare_bad_type"),ProductCollectObjCompareException::CODE_BAD_TYPE);
        }
        if (count($this->product_collect_objs)+1>self::MAXIMUM_ELEMENT){
            throw new ProductCollectObjCompareException(lang("compare_maximum_element"),ProductCollectObjCompareException::CODE_MAXIMUM_ELEMENT);
        }
    }
    
    /**
     * Típus beállítás gyűjtők alapján
     */
    protected function setTypeByObjs(){
        foreach($this->product_collect_objs as $product_collect_obj){
            if ($product_collect_obj instanceof ProductCollectObj){
                $this->setType($product_collect_obj->getProductCollectTypeId());
                break;
            }
        }
    }
    
    public function setType($val){
        $this->type=$val;
    }
    
    public function getCount(){
        return count($this->product_collect_objs);
    }
    
    public function getContainerClass(){
        if ($this->getCount()==0){
            return "none";
        }else {
            return "block";
        }
    }
}

class ProductCollectObjCompareException extends Exception{
    
    const CODE_MAXIMUM_ELEMENT  = 3;
    const CODE_BAD_TYPE         = 2;
 
}
