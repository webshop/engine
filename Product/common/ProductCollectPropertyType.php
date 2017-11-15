<?php

/**
 * Product tulajdonság osztály, a gyűjtő tulajdonságainak szerkesztésére
 */
class ProductCollectPropertyType {
    
    /**
     *
     * @var type 
     */
    protected $id;
    
    
    /**
     *
     * @var type 
     */
    protected $title;
    
    /**
     *
     * @var type 
     */
    protected $name;
    
    /**
     *
     * @var type 
     */
    protected $value;
    
    
    /**
     *
     * @var type 
     */
    protected $options;
    
    /**
     *
     * @var type 
     */
    protected $onclick;
    
    /**
     * Szerkesztő template element
     * @var type Template
     */
    
    protected $edit_template_element;
    
    /**
     * Megjelenítő template element
     * @var type 
     */
    protected $template_element;


    
    
    const _DEFAULT_HTML_ELEMENT_NAME    =   "property[::product_collect_property_name_id::]";
    
    const _DEFAULT_HTML_ELEMENT_ONCLICK =   "ProductCollect.changePropertiesCheckbox(::product_collect_property_name_id::)";
    
    /**
     * 
     * @param type $type
     * @return \ProductCollectPropertyType
     */
    final static function getInstanceByType($type=ProductCollectPropertyNameObj::PROPERTY_TYPE_INPUT){
        switch ($type){
            default:
            case ProductCollectPropertyNameObj::PROPERTY_TYPE_INPUT:
            {
                $obj=new ProductCollectPropertyTypeInput();
            }
            break;
            case ProductCollectPropertyNameObj::PROPERTY_TYPE_SELECT:
            {
                $obj=new ProductCollectPropertyTypeSelect();
            }
            break;
            case ProductCollectPropertyNameObj::PROPERTY_TYPE_CHECKBOX:
            {
                $obj=new ProductCollectPropertyTypeCheckbox();
            }
            break;
        }
        return $obj;
    }
    
    public function __construct() {
        $this->template_element=Template::loadTemplate("product_collect_property_type_view.html","Product");
    }
    
    public function replaceEditTemplate(){
        if ($this->edit_template_element instanceof Template){
            $this->edit_template_element->replaceHTML("::title::",$this->getTitle());
            $this->edit_template_element->replaceHTML("::id::",$this->getId());
            $this->edit_template_element->replaceHTML("::name::",$this->getName());
            $this->edit_template_element->replaceHTML("::value::",$this->getValue());
        }
        return $this->edit_template_element;
    }
    public function replaceTemplate(){
        if ($this->template_element instanceof Template){
            $this->template_element->replaceHTML("::title::",$this->getTitle());
            $this->template_element->replaceHTML("::name::",$this->getName());
            $this->template_element->replaceHTML("::value::",$this->getValue());
        }
        return $this->template_element;
    }

    public static function getDefaultHTMLElementName($product_collect_property_name_id){
        return str_replace("::product_collect_property_name_id::", $product_collect_property_name_id, self::_DEFAULT_HTML_ELEMENT_NAME);
    }
    public static function getDefaultHTMLElementOnClick($product_collect_property_name_id){
        return str_replace("::product_collect_property_name_id::", $product_collect_property_name_id, self::_DEFAULT_HTML_ELEMENT_ONCLICK);
    }
    
    //GET & SET 
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getValue(){
        return $this->value;
    }
    public function getEditElement(){
        return $this->edit_element;
    }
    public function getTitle(){
        return $this->title;
    }
    public function getOptions(){
        return $this->options;
    }
    public function getOnclick(){
        return $this->onclick;
    }

    public function setId($val){
        $this->id=$val;
    }
    public function setName($val){
        $this->name=$val;
    }
    public function setValue($val){
        $this->value=$val;
    }
    public function setTitle($val){
        $this->title=$val;
    }
    public function setOptions($val){
        $this->options=$val;
    }
    public function setOnclick($val){
        $this->onclick=$val;
    }
    
    
    
}
