<?php
class ProductCollectObjLastView {
    
    const SESSION_INDEX_LAST_VIEW   =   "product_collect_last_view";
    const MAXIMUM_ELEMENT           =   5;
    /**
     * ProductCollectObj
     * @var type 
     */
    public static $product_collect_obj_last_view=array();
    
    
    private static $loaded=0;
    
    /**
     * Hozzáadás a listához
     * @param ProductCollectObj $product_collect_obj
     */
    public static function add(ProductCollectObj $product_collect_obj){
        self::load();
        self::checkIsset($product_collect_obj->getId());
        array_unshift(self::$product_collect_obj_last_view, $product_collect_obj);
        if (count(self::$product_collect_obj_last_view)>self::MAXIMUM_ELEMENT){
            array_pop(self::$product_collect_obj_last_view);
        }
        self::storeToSession();
    }
    
    private static function checkIsset($product_collect_id){
         foreach(self::$product_collect_obj_last_view as $index=>$product_collect_obj){
            if ($product_collect_obj instanceof ProductCollectObj){
                if ($product_collect_obj->getId()==$product_collect_id){
                    unset(self::$product_collect_obj_last_view[$index]);
                    break;
                }
            }
        }
    }
    
    /**
     * Megjelenítés
     */
    public static function render(){
        self::load();
        $data=array();
        $content=Template::loadTemplate("product_collect_item_last_view.html",'Product');
        foreach(self::$product_collect_obj_last_view as $product_collect_obj){
            if ($product_collect_obj instanceof ProductCollectObj){
                $data[]=array(
                    "name"=>$product_collect_obj->getName(),
                    "image"=>$product_collect_obj->getFirstImage(ImageManager::FILTER_NAME_2),
                    "image_big"=>$product_collect_obj->getFirstImage(ImageManager::FILTER_NAME_1),
                    "id"=>$product_collect_obj->getId(),
                    "short_text"=>$product_collect_obj->getShortText(),
                    "compare_at_price"=>$product_collect_obj->getCompareAtPriceWithCurrency(),
                    "price"=>$product_collect_obj->getPriceWithCurrency(),
                );
            }
        }
        $content->foreachSection("product_collect_item", $data,true);
        return $content;
    }
    
    private static function load(){
        if (self::$loaded==0){
            if (Application::getSession()->is_set(self::SESSION_INDEX_LAST_VIEW)){
                self::$product_collect_obj_last_view=Application::getSession()->get(self::SESSION_INDEX_LAST_VIEW, array());
            }else {
                self::storeToSession();
            }
        }
        self::$loaded=1;
    }
    
    private static function storeToSession(){
        Application::getSession()->set(self::SESSION_INDEX_LAST_VIEW, self::$product_collect_obj_last_view);
    }
}
