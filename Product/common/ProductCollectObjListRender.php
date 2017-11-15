<?php
class ProductCollectObjListRender {
    
    
    /**
     * ProductCollectObj tömb
     * @var type 
     */
    protected $objs=array();

    /**
     * Render opciók
     * @var type 
     */
    protected $options=array();
    
    /**
     * Konstansok
     */
    const OPTIONS_KEY_LIST_TYPE="options_list_type";
    const OPTIONS_LIST_TYPE_LIST    = 1;
    const OPTIONS_LIST_TYPE_GRID    = 2;
    
    const OPTIONS_KEY_PARAMS    ="options_params";
    const OPTIONS_FAVOURITE     =1;
    
    
    
    public function __construct() {
        $this->addOptions(self::OPTIONS_KEY_LIST_TYPE,self::OPTIONS_LIST_TYPE_LIST);
        $this->addOptions(self::OPTIONS_KEY_PARAMS,0);
        
    }
    /**
     * Renderelés
     * @return type
     */
    public function render(){
        $data=array();
        $item=$this->getItemTemplate();
        if (!empty($this->objs)){
            $params=$this->getOptions(self::OPTIONS_KEY_PARAMS);
            foreach($this->objs as $obj){
                if ($obj instanceof ProductCollectObj){
                    $data[]=array(
                        "name"=>$obj->getName(),
                        "image"=>$obj->getFirstImage(ImageManager::FILTER_NAME_2),
                        "image_big"=>$obj->getFirstImage(ImageManager::FILTER_NAME_1),
                        "id"=>$obj->getId(),
                        "short_text"=>$obj->getShortText(),
                        "compare_at_price"=>$obj->getCompareAtPriceByProductsWithCurrency(),
                        "price"=>$obj->getPriceByProductsWithCurrency(),
                    );
                }
            }
            $item->foreachSection("product_collect_item", $data,true);
            $section=($params==self::OPTIONS_FAVOURITE)?"remove":"add";
            $item->switchSection("favourite", $section);
            return $item;
        }
        return Template::loadTemplate("product_collect_list_no_result.html","Product");
        
    }
    
    public function setObjs($val){
        $this->objs=$val;
    }
    
    public function getObjs(){
        return $this->objs;
    }
    
    /**
     * Az item listázás
     * @return type
     */
    public function getItemTemplate(){
        if ($this->getOptions(self::OPTIONS_KEY_LIST_TYPE)==self::OPTIONS_LIST_TYPE_LIST){
            return Template::loadTemplate("product_collect_item_list.html","Product");
        }
        elseif($this->getOptions(self::OPTIONS_KEY_LIST_TYPE)==self::OPTIONS_LIST_TYPE_GRID){
            return Template::loadTemplate("product_collect_item_grid.html","Product");
        }    
    }
    public function addOptions($key,$val){
        $this->options[$key]=$val;
    }
    public function getOptions($key){
        if (isset($this->options[$key])){
            return $this->options[$key];
        }else {
            return "";
        }
    }
}
