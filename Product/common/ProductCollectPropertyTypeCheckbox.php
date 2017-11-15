<?php

class ProductCollectPropertyTypeCheckbox extends ProductCollectPropertyType{
    
    
    public function __construct() {
        parent::__construct();
        $this->edit_template_element=Template::loadTemplate("product_collect_property_type_checkbox.html","Product");
    }
    
    public function replaceEditTemplate(){
        parent::replaceEditTemplate();
        if ($this->edit_template_element instanceof Template){
            $this->edit_template_element->replaceHTML("::onclick::",$this->getOnclick());
            $this->edit_template_element->replaceHTML("::product_collect_property_name_id::",$this->getId());
        }
        return $this->edit_template_element;
    }
}
