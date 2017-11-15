<?php

class ProductCollectPropertyTypeSelect extends ProductCollectPropertyType{
    
    public function __construct() {
        parent::__construct();
        $this->edit_template_element=Template::loadTemplate("product_collect_property_type_select.html","Product");
    }

    public function replaceEditTemplate(){
        parent::replaceEditTemplate();
        if ($this->edit_template_element instanceof Template){
            $this->edit_template_element->replaceHTML("::options::",  options_for_select($this->getOptions(),$this->getValue()));
        }
        return $this->edit_template_element;
    }
}
