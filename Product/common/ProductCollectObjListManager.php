<?php
class ProductCollectObjListManager extends ListManager{
    
    
    const ORDER_TYPE_PRICE_ASC     =   1;
    const ORDER_TYPE_PRICE_DESC    =   2;
    const ORDER_TYPE_NAME_ASC      =   3;
    const ORDER_TYPE_NAME_DESC     =   4;
    
    const DEFAULT_PAGE_NUM         =   10;
    
    protected $order_type_option=array(
        
        self::ORDER_TYPE_PRICE_ASC      =>  'order_type_price_asc',
        self::ORDER_TYPE_PRICE_DESC     =>  'order_type_price_desc',
        self::ORDER_TYPE_NAME_ASC       =>  'order_type_name_asc',
        self::ORDER_TYPE_NAME_DESC      =>  'order_type_name_desc',
        
        
    );
    
    
    public function __construct() {
        $this->render_obj=new ProductCollectObjListRender();
        $this->loader_obj=new ProductCollectObjLoader();
    }
    
    /**
     * Id-kat határozza meg
     */
    public function getSQLResult() {
        $ids=SQL::query("SELECT SQL_CALC_FOUND_ROWS id FROM product_collect WHERE ".$this->getSQLCondition()." ORDER BY ".$this->getOrderTypeSQL()." ".$this->getOrderDirSQL()." LIMIT ".$this->getOffset().",".$this->getLimit(),"webshop_product")->fetchListData();
        $total_records = SQL::query("SELECT FOUND_ROWS();","webshop_product")->fetchValue();
        $this->setIds($ids);
        
        $paginatoroptions=array(
            Paginator::TOTAL_RECORDS => $total_records,
            Paginator::CURRENT_RECORD => $this->getOffset(),
            Paginator::RECORDS_PER_PAGE => $this->getLimit(),
            Paginator::RECORDS_PER_PAGE_OPTIONS=>$this->record_per_page_arr,
            Paginator::URL => "javascript:ProductCollect.listReload(::num::,::record::)",
            Paginator::TEMPLATE => '::PrevPageLink:: ::LessSign::::PageLinks::::MoreSign:: ::NextPageLink::',
            Paginator::HTML_PAGE => new Template('::page::',false),
            Paginator::HTML_MORESIGN => '...',
            Paginator::HTML_LESSSIGN => '...',
        );
        $paginator=new Paginator($paginatoroptions);
        $this->paginator_obj=$paginator;
    }
    
    
    public function setOrderType($val){
        if (!isset($this->order_type_option[$val])){
            $val=self::ORDER_TYPE_PRICE_ASC;
        }
        if ($val==self::ORDER_TYPE_PRICE_ASC ||
            $val==self::ORDER_TYPE_NAME_ASC){
            $this->setOrderDir(self::ORDER_DIR_ASC);
        }else {
            $this->setOrderDir(self::ORDER_DIR_DESC);
        }
        $this->order_type=$val;
    }
    
    public function getOrderTypeSQL(){
        switch ($this->getOrderType()) {
            case self::ORDER_TYPE_PRICE_DESC:
            case self::ORDER_TYPE_PRICE_ASC:
                $val="price";
                break;
            case self::ORDER_TYPE_NAME_ASC:
            case self::ORDER_TYPE_NAME_DESC:
                $val="name";
                break;
            default:
                $val="name";
                break;
        }
        return $val;
    }
    
    /**
     * Az itemeket rendereli ki
     */
    public function renderItems() {
        $this->items=$this->render_obj->render();
    }
    
    /**
     * Betölti az ID-K szerint az objektumokat és minden hozzá tartozó adatot
     */
    public function loadObjs() {
        $this->setObjs($this->loader_obj->setProductCollectIds($this->getIds())->getObjs());
    }
    
    /**
     * Becseréli az aktuális lapozót, paramétereket, itemeket
     * @return type
     */
    public function getResult() {
        $template=Template::loadTemplate("product_collect_list_container.html","Product");
        $params=Template::loadTemplate("product_collect_list_params.html","Product");
        $params->replaceHTML("::option_order_type::", $this->getOrderTypeOptionArrOptions($this->getOrderType()));
        $params->replaceHTML("::list_type::", $this->render_obj->getOptions(ProductCollectObjListRender::OPTIONS_KEY_LIST_TYPE));
        $params->replaceHTML("::option_record_per_page::", options_for_select($this->record_per_page_arr, $this->getPaginatorNum()));
        $params->replaceHTML("::paginator::", $this->getPaginatorHtml());
        $params->replaceHTML("::total::", $this->getPaginatorTotalRecords());
        $params->replaceHTML("::from::", ($this->getPaginatorTotalRecords()!=0)?$this->getPaginatorFrom():0);
        $params->replaceHTML("::to::", $this->getPaginatorTo());
        $params->replaceHTML("::record::", $this->getPaginatorRecord());
        $params->replaceHTML("::num::", $this->getPaginatorNum());
        $template->replaceHTML("::product_collect_list_params::", $params);
        $template->replaceHTML("::product_collect_items::", $this->items);
        return $template;
    }

}
