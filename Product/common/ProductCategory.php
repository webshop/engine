<?php

/**
 * Description of WebshopCategory
 *
 * @author Imre Péter
 */
class ProductCategory {//extends BaseModule {

    const STORE_CATEGORY_EXCEPTION = "store_category_exception";
    
    const DIRECTION_UP = 'up';
    const DIRECTION_DOWN = 'down';
    
    /**
     * @return ProductCategory
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
     * Kategória fa megjelenítés adminisztráláshoz
     * @param int $selected kiválasztott kategória ID
     * @return Template kategória fa
     */
    public function listToAdmin($selected) {
        $content = Template::loadTemplate('category_list.html', 'Product');
        $content->replaceRaw("::tree::", $this->displayTreeAdmin());
        $content->replaceRaw("::selected::", $selected);
        //TODO: meg kell jeleníteni a mentési hibát, ha volt (SESSION-ba van tárolva)
        return $content;
    }

    /**
     * Kategória fa megjelenítés adminisztráláshoz. Felolvassa a kategóriákat adatbázisból és fába rendezi
     * @return string kategória fa
     */
    public function displayTreeAdmin($need_outer_ul = true) {
        $first = true;
        $prev_level = 1;
        $tree="";
        if ($need_outer_ul) {
            $tree = "<ul class='category-tree'>\n";
        }
        $tree.="<li>";
        $item = new Template("<a href='javascript:void(0);' id='sore_category_::id::' data-id='::id::' data-img_url='::img_url::'></i>::name::</a>\n", false);
        $categories = ProductCategoryObj::getInstance()->loadByQuery("ORDER BY `left`");
        foreach ($categories as $category) {
            $item->reset();
            // mélyebb szint következik
            if ($prev_level < $category->getLevel()) {
                // Ha minden jó, akkor itt csak 1 mélységget lehet lépni egyszerre
                $tree.="\n<ul>\n<li>";
            } else
            // vége egy szintnek
            if ($prev_level > $category->getLevel()) {
                // ahánnyal kisebb, annyi lezárás kell ide!
                $tree.=str_repeat("\n</li>\n</ul>", $prev_level - $category->getLevel());
                $tree.="</li><li>\n";
            } elseif (!$first) {
                // ha marad a szint, akkor új elemet kezdünk
                $tree.="</li><li>\n";
            }
            $img_url = $category->getImageURL();
            $item->replace("::name::", $category->getName());
            $item->replace("::id::", $category->getId());
            $item->replace("::img_url::", $img_url);
            $tree.=$item->getContent();
            $prev_level = $category->getLevel();
            $first = false;
        }
        $tree.=str_repeat("\n</li>\n</ul>", $prev_level); // lezárjuk a darabban maradt szinteket

        if (!$need_outer_ul) {
            $tree = substr($tree, 0, -5); // Ha nem kell a külső UL tag, akkor az utolsó 5 karektert vágjuk le: </ul>
        }
        return $tree;
    }
    /**
     * Kategória fa megjelenítés adminisztráláshoz. Felolvassa a kategóriákat adatbázisból és fába rendezi
     * @return string kategória fa
     */
    public function displayTreeFrontEnd($active_category_id = 0) {
        $first = true;
        $prev_level = 1;
        $tree = "\n<ul class='category-tree'>\n";       
        $tree.="\t<li>";
        $item = new Template("\n::tabs::<a href='::href::' class=\"::class::\">::name::</a>", false);
        
        try {
            $active_category_obj = ProductCategoryObj::getInstance()->load($active_category_id);
        } catch (Exception $e) {
            $active_category_obj = new ProductCategoryObj();
        }
        
        $categories = ProductCategoryObj::getInstance()->loadByQuery("ORDER BY `left`");
        $tab_cnt = 1;
        $active_parent_ids = $active_category_obj->getParentIdsArray();
        foreach ($categories as $category) {
            if($first) $prev_cat_id = $category->getId();
            $item->reset();
            
            // mélyebb szint következik
            $visibility = ($category->getLevel() < 3) ? ' show' : ' hide' ;            
            
            if ($prev_level < $category->getLevel()) {
                // Ha minden jó, akkor itt csak 1 mélységget lehet lépni egyszerre
                if(in_array($prev_cat_id, $active_parent_ids)) $visibility = ' show';
                $tab_cnt+=1;
                $tree.="\n".tabs($tab_cnt)."<ul class=\"level_" . $category->getLevel() . $visibility . "\">\n";
                $tab_cnt+=1;
                $tree.=tabs($tab_cnt)."<li>";
            } else
            // vége egy szintnek
            if ($prev_level > $category->getLevel()) {
                // ahánnyal kisebb, annyi lezárás kell ide!
                $tab_cnt_ul = $tab_cnt-1;
                $tree.=str_repeat("\n".tabs($tab_cnt)."</li>\n".tabs($tab_cnt_ul)."</ul>\n", $prev_level - $category->getLevel());
                $tab_cnt-=2;
                $tree.=tabs($tab_cnt)."</li>\n".tabs($tab_cnt)."<li>";
            } elseif (!$first) {
                // ha marad a szint, akkor új elemet kezdünk
                $tree.="\n".tabs($tab_cnt)."</li>\n".tabs($tab_cnt)."<li>";
            }
            $img_url = $category->getImageURL();
            
            $item->replace("::tabs::", tabs($tab_cnt+1));
            $item->replace("::href::", $category->getURL());
            $item->replace("::name::", $category->getName());
            $item->replace("::class::", ($active_category_id == $category->getId()) ? 'active' : '');
            $item->replace("::id::", $category->getId());
            $item->replace("::img_url::", $img_url);
            $tree.= $item->getContent();
            $prev_level = $category->getLevel();
            $prev_cat_id = $category->getId();
            $first = false;            
        }
        for($i=$prev_level;$i>0;$i--){
            $tree.="\n".tabs($tab_cnt)."</li>\n";
            $tab_cnt-=1;
            $tree.=tabs($tab_cnt)."</ul>";
            $tab_cnt-=1;
        }

//        $tree = substr($tree, 0, -5); // Ha nem kell a külső UL tag, akkor az utolsó 5 karektert vágjuk le: </ul>       
        return $tree;
    }

    /**
     * 
     * @param array $category a mentendő adatokat tartamlazó tömb. Kulcsai: id, parent_id, name.<br>
     * Ha az 'id'>0, akkor módosítás<br>
     * Ha a 'parent_id'>0, akkor új beszúrás
     * @return ProductCategoryObj a módosított vagy az új objektum
     * @throws Exception Ha valami nincs kitöltve vagy nem lehet a szülőt betölteni
     */
    public function save($category) {
        /** HISTORY-hoz **/
        $new=false;
        $category_name_before="";        
        
        if (!isset($category['id'])) {
            $category['id'] = '';            
        }
        if (!isset($category['parent_id'])) {
            $category['parent_id'] = '';
        }
        if (!isset($category['name'])) {
            $category['name'] = '';
        }
        if (!isset($category['default_sort_by'])) {
            $category['default_sort_by'] = '';
        }
        $category['id'] = intval($category['id']);
        $category['parent_id'] = intval($category['parent_id']);
        $category['name'] = trim($category['name']);
        $category['default_sort_by'] = trim($category['default_sort_by']);

        if ($category['name'] == "") {
            throw new Exception('The name field can not be empty!');
        }

        if (($category['id'] + $category['parent_id']) > 0) {
            if ($category['id'] > 0) {                
                $store_category = ProductCategoryObj::getInstance()->load($category['id'], false, false);                
                if (!empty($store_category) && $store_category instanceof ProductCategoryObj) {
                    $category_name_before=$store_category->getName();
                    $store_category->setDefaultSortBy($category['default_sort_by']);                    
                    $store_category->setName($category['name']);                    
                    $store_category->save();
                } else {
                    throw new Exception('Category not found!');
                }
            } else if ($category['parent_id'] > 0) {
                $new=true;
                $store_category = ProductCategoryObj::insert($category['parent_id'], $category['name']);
            }

            /** HISTORY **/
            $params=new HistoryParams();
            $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_CATEGORY);
            $params->setRefId($store_category->getId());
            if ($new){
                $params->setType(HistoryObj::_OPERATION_NEW);
                $params->setText(lang("product_collect_category_history_new").": ".$category['name']);
            }else {
                $params->setType(HistoryObj::_OPERATION_MODIFY);
                $text=lang("product_collect_category_history_modify");
                $text.="<br/>";
                $text.=lang("product_collect_category_history_modify_before").$category_name_before.",";
                $text.=lang("product_collect_category_history_modify_after").$category['name'];
                $params->setText($text);
            }
            HistoryObj::addHistory($params);
            
            
            
            return $store_category;
        } else {
            throw new Exception('Bad parameters!');
        }
    }

    /**
     * paraméretben meghatározott kategória törlése
     * @param int $category_id
     * @return array ajax-os lekérdezés válasz tömbje
     */
    public function delete($category_id) {
        $ret = array("success" => 0);
        $error = "";
        
        $store_category = ProductCategoryObj::getInstance()->load($category_id, false);
        if (!empty($store_category)) {
            $category_name=$store_category->getName();
            $ret['parent_id'] = $store_category->getParentId();
            $all_category_ids = $store_category->getAllChildIds();
            $all_category_ids[] = $category_id;
            
            $product_count = SQL::query("SELECT count(*) from product_collect WHERE category_id in (" . implode(', ', $all_category_ids ) . ")","webshop_product")->fetchValue();
            
            if ($product_count == 0) {                
                $store_category->delete();
                
                /** HISTORY **/
                $params=new HistoryParams();
                $params->setModuleId(MainMenuService::MENU_ITEM_PRODUCT_COLLECT_CATEGORY);
                $params->setRefId($category_id);            
                $params->setType(HistoryObj::_OPERATION_DELETE);
                $text=lang("product_collect_category_history_delete");
                $text.="<br/>";
                $text.=lang('product_collect_category_name').": ".$category_name;
                $params->setText($text);            
                HistoryObj::addHistory($params);
                
            } else {
                $error = "Existing product";
            }
        } else {
            $error = "Category not found";
        }

        if (empty($error)) {
            $ret['success'] = 1;
        } else {
            $ret['error'] = $error;
        }
        return $ret;
    }

    /**
     * kategória fa megjelenítése XML fában
     * @return string kategória fa XML formátumban
     */
    public function apiGetCategories() {
/*        
        $tree = new SimpleXMLElement("<?xml version='1.0' encoding ='UTF-8' ?>\n<categories>\n</categories>", LIBXML_NOCDATA);
 */
        $tree = new SimpleXMLElement("<?xml version='1.0' encoding ='UTF-8' ?>\n<categories>\n</categories>", null);
        // Első elem (main page) betöltése
        $categories = ProductCategoryObj::getInstance()->loadByQuery("WHERE `level`=1 ORDER BY `left`");
        // Gyermek elemek felolvasása rekurzívan
        foreach ($categories as $category) {
            $this->loadChildsToXML($tree, $category);
        }
        return $tree->asXML();
    }

    /**
     * A $parent_category következő szenten lévő gyermekeit olvassa fel adatbázisból és teszi be az XML fába
     * Ha van a kategóriának gyermeke, akkor rekurzívan meghívja önmagát a következő szint feldolgozására
     * @param SimpleXMLElement $tree
     * @param ProductCategoryObj $category
     */
    private function loadChildsToXML(&$tree, $parent_category) {
        if ($parent_category->hasChild()) {
            $sql = "WHERE parent_id=".$parent_category->getId();
            $sql.=" ORDER BY `left`";
            
            $categories = ProductCategoryObj::getInstance()->loadByQuery($sql);
            foreach ($categories as $category) {
                $category_item = $tree->addChild('category');
                $category_item->addChild('id', $category->getId());
//                $category_item->addChild('name', Language::getTranslateText($category->getLangCode()));
                
                $name_node = $category_item->addChild('name');
                $this->xmlAddCData($name_node, $category->getName());
                
                $category_item->addChild('has_childs', $category->hasChild() ? 1 : 0);
                $childs = $category_item->addChild('childs');
                if ($category->hasChild()){
                    $this->loadChildsToXML($childs, $category);
                }
            }
        }
    }
    
    /**
     * CDATA beszúrása az XML-be. A SimpleXML alapból nem támogatja, ezért kell trükközni
     * @param SimpleXMLElement $xml Ebbe az elembe kell a speciális szöveg
     * @param string $cdata_text ezt kell beszúrni
     */
    private function xmlAddCData(&$xml, $cdata_text){
        $node= dom_import_simplexml($xml); 
        $no = $node->ownerDocument; 
        $node->appendChild($no->createCDATASection($cdata_text)); 
    }
    
    /**
     * Kategória rendezés - Hátra (felfelé) mozgatás a fában
     * @param int $moved_id mozgatott kategória ID
     * @param int $next_id ez elé az ID elé került a mozgatás után
     * @return string Hiba szövege ha volt hiba egyébként üres
     */
    public function moveBackward($moved_id, $next_id){
        $error = "";
        $moved = ProductCategoryObj::getInstance()->load($moved_id, false);
        $next = ProductCategoryObj::getInstance()->load($next_id, false);
        if (!empty($moved) && !empty($next)){
            $moved_width = $moved->getRight() - $moved->getLeft() + 1;
            $moved_ids = $moved->getAllChildIds();
            $moved_ids[] = $moved->getId();
            
            // ami a next és a moved között van, azt növelni kell a moved szélességével
            $sql = "UPDATE `category` SET `left`=`left`+$moved_width, `right`=`right`+$moved_width ";
            $sql.=" WHERE `left`>=".$next->getLeft()." AND `left`<".$moved->getLeft();
            SQL::query($sql,"webshop_product");
            
            // Moved és gyermekeinek left-jét és right-ját csökkenteni kell a moved és a next leftjeinek külömbségével
            $other_width = $moved->getLeft() - $next->getLeft();
            $sql = "UPDATE `category` SET `left`=`left`-($other_width), `right`=`right`-($other_width)";
            $sql.=" WHERE id IN (".implode(',', $moved_ids).")";
            SQL::query($sql,"webshop_product");
        } else {
            $error = "IDs not found, please refresh your screen!";
        }
        return $error;
    }
    
    /**
     * Kategória rendezés - Előre (lefelé) mozgatás a fában
     * @param int $moved_id mozgatott kategória ID
     * @param int $prev_id e mögé az ID mögé került a mozgatás után
     * @return string Hiba szövege ha volt hiba egyébként üres
     */
    public function moveForward($moved_id, $prev_id){
        $error = "";
        $moved = ProductCategoryObj::getInstance()->load($moved_id, false);
        $prev = ProductCategoryObj::getInstance()->load($prev_id, false);
        if (!empty($moved) && !empty($prev)){
            $moved_width = $moved->getRight() - $moved->getLeft() + 1;
            $moved_ids = $moved->getAllChildIds();
            $moved_ids[] = $moved->getId();
            
            // ami a prev és a moved között van, azt csökkenteni kell a moved szélességével
            $sql = "UPDATE `category` SET `left`=`left`-$moved_width, `right`=`right`-$moved_width ";
            $sql.=" WHERE `left`>".$moved->getRight()." AND `left`<".$prev->getRight();
            SQL::query($sql,"webshop_product");
            
            // Moved és gyermekeinek left-jét és right-ját növelni kell a moved és a prev leftjeinek külömbségével
            $other_width = $prev->getRight() - $moved->getRight();
            $sql = "UPDATE `category` SET `left`=`left`+$other_width, `right`=`right`+$other_width";
            $sql.=" WHERE id IN (".implode(',', $moved_ids).")";
            SQL::query($sql,"webshop_product");
        } else {
            $error = "IDs not found, please refresh your screen!";
        }
        return $error;
    }

    /**
     * Store kezdő oldali kategória listát generálja le
     * @param ProductCategoryObj $category
     * @return Template
     */
    public function listMainPage(){
        $content = Template::loadTemplate('category_main_list', "Product");
        $main_category_template = $content->getSection("main_category");
        $main_categories_template = new Template();
        
        $query = "WHERE `level`=".ProductCategoryObj::LEVEL_TOP." ORDER BY `left`";
        $categories = ProductCategoryObj::getInstance()->loadByQuery($query);
        foreach ($categories as $category){
            $main_category_template->reset();
            
            $main_category_template->replace("::main_category_name::", $category->getNameLang());
            $main_category_template->replace("::main_category_img_name::", $category->getNameLang());
            $main_category_template->replace("::main_category_url::", $category->getURL());
            $img_url = $category->getImageURL();
            if (empty($img_url)){
                $img_url = ProductService::NO_IMAGE_PATH;
            }
            $main_category_template->replace("::main_category_img::", $img_url);
            
            $main_category_template->foreachSection("sub_category_item", $category->getPopularData(ProductCategoryObj::LEVEL_PRODUCT, 9));
            
            $main_categories_template->concat($main_category_template);
        }
        $content->replace("::main_categories::", $main_categories_template);
        return $content;
    }
    
    /**
     * Főkategória alá tartozó al-kategória listát generál
     * @param ProductCategoryObj $category
     * @return Template
     */
    public function listSubCategories($category){
        $content = Template::loadTemplate('sub_categories_main', "Product");
        $content->replace('::category_id::', $category->getId());
        $content->replace("::main_category_name::",$category->getName()." főkategória");
        try{
            //paraméterben kapott kategória id által lekérem a főkategória összes mezőjét
            //$main_category = ProductCategoryObj::getInstance()->load($category_id);
            if(!empty($category) && $category->getLevel()==2){ 
                //kell a left és a right a főkategóriának, hogy behatároljam a lenti SQL-lel , hogy az adott főkategóriában
                //mely alkategóriák és termékek vannak
                $left = $category->getLeft();
                $right = $category->getRight();
                
                $sub_categories = SQL::query("SELECT * FROM category sc WHERE sc.`left`>$left AND sc.right<$right","webshop_product")->fetchData('id');
                //erre még kitalálok valamit, de arra csináltam, hogy alapból csak a level = 3-at nézem az első foreach-ben
                //ezért ha true,akkor if-en belül végigmegyek a clone táblán, ahol viszont már level = 4-et nézek, plusz a left-right-okat
                $sub_categories_clone = $sub_categories;
                $content_temp = new Template("",false);
                if(!empty($sub_categories)){
                    $sub_categories_template = Template::loadTemplate('sub_categories_items', "Product");
                    foreach($sub_categories as $scat_key => $scat_value){
                        if($scat_value['level']==ProductCategoryObj::LEVEL_MIDDLE){
                            $sub_categories_template->reset();
                            $sub_categories_template->replace("::sub_title::",$scat_value['name']);                            
                            $left = $scat_value['left'];
                            $right = $scat_value['right'];                            
                            $data=array();
                            foreach($sub_categories_clone as $scat_clone_key => $scat_clone_value){                                 
                                if($scat_clone_value['level']==ProductCategoryObj::LEVEL_PRODUCT 
                                   && $scat_clone_value['left']>$left 
                                   && $scat_clone_value['right']<$right)
                                { 
                                    //foreach_section miatt
                                    $data[]=array(
                                        'item_name' => ProductCategoryObj::_getNameLang($scat_clone_key, $scat_clone_value['name']),
                                        'item_url'  => ProductCategoryObj::_getUrl($scat_clone_key, $scat_clone_value['name']),
                                    );
                                }
                            }   
                            
                            $sub_items=Template::loadTemplate("sub_categories_item","Product");
                            $sub_items->foreachSection("subitem", $data);
                            $sub_categories_template->replace("::items::",$sub_items);
                            $img_url = $scat_value['image_url'];
                            if (empty($img_url)){
                                $img_url = StoreService::NO_IMAGE_PATH;
                            }
                            $sub_categories_template->replace("::sub_category_img_src::",$img_url);
                            $content_temp->concat($sub_categories_template);
                            
                        }
                    }
                }
                else{
                    throw new StoreException(StoreException::_ERROR_STORE_SUBCATEGORY_IS_MISSING);
                }
            }
            else{ 
                throw new StoreException(StoreException::_ERROR_STORE_CATEGORY_IS_MISSING);
            }
        }
        catch(DatabaseObjException $e){
            return $e->getMessage();
        }
        catch(SQLException $e){
            return $e->getMessage();
        }
        $content->replace("::sub_items::",$content_temp);
        return $content;
    }
    
    /**
     * kategória ID alapján vissaadja a szülők ID-ját és nevét
     * @param int $category_id
     * @param int $expand
     * @return array
     */
    public function getCategorySelectorData($category_id, $expand){
        $ret = array("success" => 0);
        $error = "";
        if ($category_id>0){
            try{
                $category = ProductCategoryObj::getInstance()->load($category_id);
            }catch(DatabaseObjException $e){
                $category = new ProductCategoryObj();
            }
        }else{
            if ($expand){
                $category = ProductCategoryObj::getInstance()->loadByQuerySimple("WHERE `level`=".ProductCategoryObj::LEVEL_TOP." ORDER BY `left` LIMIT 1");
            }else{
                $category = ProductCategoryObj::getInstance()->loadByQuerySimple("WHERE `level`=".ProductCategoryObj::LEVEL_MAIN." ORDER BY `left` LIMIT 1");
            }
        }
        if ($expand){
            if($category instanceof ProductCategoryObj){
            // Lemegyünk az alsó szintig minden esetben, látszódjon a valós kategória
                while($category->hasChild()){
                    $category = $category->getFirstChild();
                }
            }
        }
        if (!empty($category)) {
            $where = " WHERE `parent_id` IN (".$category->getParentIds().")";
//            if ($expand){
                $where.=" AND parent_id<>0";
//            }
            
            $query = "SELECT id, name, `level`, `left`, `right` FROM `category` ";
            $query.=" $where ORDER BY `level`, `left`";
            $categories = array();
            $sql_result = SQL::query($query,"webshop_product");
            $last_selected = 0;
            while($row = $sql_result->fetchArray()){
                $categories[$row['level']][]=array(
                    'id'=>$row['id'],
                    'name'=>  ProductCategoryObj::_getNameLang($row['id'], $row['name']),
                    'real' => (($row['left']+1)==$row['right']),
                    'selected' => ($category_id>0) ? in_array($row['id'], explode(',', $category->getParentIds())) : false,
                );
                if (in_array($row['id'], explode(',', $category->getParentIds()))){
                    $last_selected = $row['id'];
                }
            }
        } else {
            $error = "Bad parameters";
        }
        if (empty($error)) {
            $ret['success'] = 1;
            $ret['categories'] = $categories;
            $ret['selected'] = $last_selected;
        } else {
            $ret['error'] = $error;
        }
        return  $ret;
    }
}

?>