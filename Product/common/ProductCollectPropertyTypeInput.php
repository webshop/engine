<?php
class ProductCollectPropertyTypeInput extends ProductCollectPropertyType{

    public function __construct() {
        parent::__construct();
        $this->edit_template_element=Template::loadTemplate("product_collect_property_type_input.html","Product");
    }
}
