<?php
abstract class ListManager {
    
    /**
     * SQL feltétel
     * @var type 
     */
    protected $sql_condition=array();
    
    /**
     * Offset
     * @var type 
     */
    protected $offset=0;
    
    /**
     * Order Type
     * @var type 
     */
    protected $order_type=0;
    
    
    /**
     * Order Dir
     * @var type 
     */
    protected $order_dir=0;
    
    /**
     * Limit
     * @var type 
     */
    protected $limit=0;
    
    
    /**
     * Találati id-k
     * @var type 
     */
    protected $ids=array();
    
    
    /**
     * Találati obj-k
     * @var type 
     */
    protected $objs=array();
    
    
    /**
     *
     * @var type ProductCollectObjLoader
     */
    protected $loader_obj=null;
    
    
    /**
     *
     * @var type ProductCollectObjListRender
     */
    protected $render_obj=null;
    
    /**
     * Lapozó osztály
     * @var type Paginator
     */
    protected $paginator_obj;
    
    
    /**
     * Template
     * @var type 
     */
    protected $items;
    
    
    
    /**
     * Rendezés típusa 
     * @var type 
     */
    protected $order_type_option=array();
    
    
    protected $record_per_page_arr=array(
        //10=>10,
        //30=>30,
        //50=>50,
        10=>10,
        30=>30,
        50=>50,
    );
    
    
    
    const ORDER_DIR_ASC     =   1;
    const ORDER_DIR_DESC    =   2;
    
    
    /**
     * Beállítás
     * @param type $val
     */
    public function setSQLCondition($val){
        $this->sql_condition=$val;
    }
    
    public function addSQLCondition($key, $val, $negate = false){
        if($negate){
            $this->sql_condition['negate'][$key] = $val;
        }else{
            $this->sql_condition['normal'][$key] = $val;
        }
        return $this;
    }
    
    public function addSQLSearchCondition($key,$val){
        $this->sql_condition['search'][$key] = $val;
    }
    
    
    
    /**
     * A lekérdezéshez
     * @return string
     */
    protected function getSQLCondition(){
        if(!empty($this->sql_condition)){
            
            if (isset($this->sql_condition['search'])){
                foreach ($this->sql_condition['search'] as $search_field => $search_val){
                    $int_search_val = intval($search_val);
                    if(empty($int_search_val)){
                        $search_val = SQL::escape($search_val);
                        $where[] = "$search_field LIKE '%$search_val%'";
                    }else{
                        $where[] = "$search_field=$search_val";
                    }
                }
            }
            
            
            if (isset($this->sql_condition['normal'] )){
                foreach ($this->sql_condition['normal'] as $field => $field_val){
                    if(!is_array($field_val)){
                        $field_val = SQL::escape($field_val);
                        $where[] = "$field='$field_val'";
                    }else{
                        if(!empty($field_val)){
                            $where[] = "$field IN ('".implode("','", $field_val)."')";
                        }
                    }
                }
            }
            if (isset($this->sql_condition['negate'] )){
                foreach ($this->sql_condition['negate'] as $field => $field_val){
                    if(!is_array($field_val)){
                        $field_val = SQL::escape($field_val);
                        $where[] = "$field!='$field_val'";
                    }else{
                        if(!empty($field_val)){
                            $where[] = "$field NOT IN ('".implode("','", $field_val)."')";
                        }
                    }
                }
            }
            
        }
        if(empty($where)){
            $where[] = '1';
        }
        $where = implode(' AND ', $where);
       
        return $where;
    }
    
    
    public function setOffset($val){
        $this->offset=$val;
    }
    
    public function getOffset(){
        return $this->offset;
    }
    
    public function setLimit($val){
        $this->limit=$val;
    }
    
    public function getLimit(){
        return $this->limit;
    }
    
    public function setOrderType($val){
        $this->order_type=$val;
    }
    public function getOrderType(){
        return $this->order_type;
    }
    
    public function getOrderDirSQL(){
        if ($this->getOrderDir()==self::ORDER_DIR_ASC){
            return "ASC";
        }else {
            return "DESC";
        }
        
    }
    public function setOrderDir($val){
        $this->order_dir=$val;
    }
    
    public function getOrderDir(){
        return $this->order_dir;
    }
    
    public function setIds($val){
        $this->ids=$val;
    }
    
    public function getIds(){
        return $this->ids;
    }
    
    public function setObjs($val){
        $this->objs=$val;
        $this->render_obj->setObjs($val);
    }
    
    public function getObjs(){
        $this->getObjs();
    }
    
    public function setLoadOptions($option, $value){
        $this->loader_obj->setLoadOption($option, $value);
    }
    
    public function getLoadOptions(){
        return $this->load_options;
    }
    
    public function setRenderObj($val){
        $this->render_obj=$val;
    }
    
    public function getRenderObj(){
        return $this->render_obj;
    }
    
    public function addRenderOption($key,$val){
        $this->render_obj->addOptions($key,$val);
    }
    
    public function getPaginatorHtml(){
        return $this->paginator_obj->getHTML();
    }
    
    public function getPaginatorTotalRecords(){
        return $this->paginator_obj->getTotalRecords();
    }
    public function getPaginatorRecord(){
        return $this->paginator_obj->getCurrentRecord();
    }
    public function getPaginatorNum(){
        return $this->paginator_obj->getRecordsPerPage();
    }
    public function getPaginatorCurrentPage(){
        return $this->paginator_obj->getCurrentPage();
    }
    
    public function getPaginatorFrom(){
        return $this->getPaginatorRecord()+1;
    }
    public function getPaginatorTo(){
        $to=$this->getPaginatorFrom()+($this->getPaginatorNum()-1);
        if ($to>$this->getPaginatorTotalRecords()){
            $to=$this->getPaginatorFrom()+($this->getPaginatorTotalRecords()-$this->getPaginatorFrom());
        }
        return $to;
    }
    /**
     * 
     * @return type
     */
    public function getOrderTypeOptionArr(){
        foreach ($this->order_type_option as $id=>$order_type){
            $arr[$id]=lang($order_type);
        }
        return $arr;
    }
    /**
     * 
     * @param type $selected_item
     * @return type
     */
    public function getOrderTypeOptionArrOptions($selected_item=0){
        $arr=$this->getOrderTypeOptionArr();
        return options_for_select($arr, $selected_item);
    }
    
    /**
     * 
     * @param type $option
     * @return string
     */
    public function getOrderTypeOptionName($option){
        if (isset($this->order_type_option[$option])){
            return lang($this->order_type_option[$option]);
        }else {
            return "";
        }
    }
    
    
    /**
     * A függvény, amit a listát előállítja
     * @return type
     */
    public function getItems(){
        $this->getSQLResult();
        $this->loadObjs();
        $this->renderItems();
        return $this->getResult();
        
    }
    
    /**
     * SQL lekérdezés
     */
    abstract function getSQLResult();
    
    /**
     * Objektum betöltés
     */
    abstract function loadObjs();
    
    
    /**
     * Megjelenítés
     */
    abstract function renderItems();
    
    
    /**
     * 
     */
    abstract function getResult();
    
}
