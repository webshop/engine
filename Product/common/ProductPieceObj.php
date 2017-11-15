<?php

class ProductPieceObj extends DatabaseObj {

    const _PRODUCT_ID               =   'product_id';
    const _STORE_ID                 =   'store_id';
    const _PIECE                    =   'piece';

    protected $sql_connection_id = 'webshop_product';
    

    protected $tablename = 'product_piece';
    protected $fields = [
        self::_PRODUCT_ID,self::_STORE_ID,self::_PIECE
    ];

    /**
     * @return static
     */
    public function reset() {
        parent::reset();
        $this->setValues([
            self::_PRODUCT_ID => 0,
            self::_STORE_ID => 0,
            self::_PIECE => 0
        ]);
    }

    public function getProductId() { return $this->getValue(self::_PRODUCT_ID); }
    public function getStoreId() { return $this->getValue(self::_STORE_ID); }
    public function getPiece() { return $this->getValue(self::_PIECE); }


    public function setProductId($value) { $this->setValue(self::_PRODUCT_ID, $value); }
    public function setStoreId($value) { $this->setValue(self::_STORE_ID, $value); }
    public function setPiece($value) { $this->setValue(self::_PIECE, $value); }



    /**
     * Leírás a dokumentációban.
     * @return array
     */
    public function rules() {
        return [
            ['piece', 'required', 'message' => 'Kötelezően megadandó!'],
            ['piece', 'numerical', 'integerOnly', 'message' => 'Hibás adat!']
        ];
    }

    /**
     * A safe kulcs értéke azon mezők listája, amelyeket a felhasználók módosíthatnak.
     * @return array
     */
    public function scopes() {
        return [
            'safe' => ['product_id', 'store_id', 'piece'],
        ];
    }

    
    
    public static function getPiecesByProductId($product_id){
        $store_objs=StoreObj::getInstance()->loadByQuery("WHERE 1");
        $product_pieces_saved=SQL::query("SELECT store_id,piece FROM product_piece WHERE product_id=$product_id","webshop_product")->fetchSimpleData();
        $product_pieces=array();
        foreach($store_objs as $store_obj){
            if ($store_obj instanceof StoreObj){
                $product_pieces[$store_obj->getId()]["store_name"]=$store_obj->getName();
                $product_pieces[$store_obj->getId()]["store_id"]=$store_obj->getId();
                if (!isset($product_pieces_saved[$store_obj->getId()])){
                    $product_pieces[$store_obj->getId()]["store_piece"]=0;
                }else {
                    $product_pieces[$store_obj->getId()]["store_piece"]=$product_pieces_saved[$store_obj->getId()];
                }
            }
        }
        return $product_pieces;
    }
    
    public static function getPiecesByProductIds($product_ids){
        if (!is_array($product_ids)){
            $product_ids[]=$product_ids;
        }
        return SQL::query("SELECT product_id,store_id,piece FROM product_piece WHERE product_id IN (".implode(",",$product_ids).") ORDER BY product_id ASC,store_id ASC","webshop_product")->fetchMultiData("product_id","store_id");
    }
    
    
    public static function updateProductPieces($pieces_settings){
        Log::dump("update price");
        $pieces=SQL::query("SELECT product_id,store_id,piece FROM product_piece ORDER BY product_id ASC,store_id ASC","webshop_product")->fetchMultiData("product_id","store_id");
        $prod_pieces=array();
        $online_id=StoreObj::getOnlineId();
        foreach($pieces as $product_id=>$pieces_arr){
            $piece=0;
            foreach($pieces_arr as $store_id=>$piece_arr){
                if ($pieces_settings==WebshopObj::_STORE_SETTINGS_PRODUCT_PIECE_ALL){
                    $piece+=$piece_arr["piece"];
                }elseif($store_id==$online_id){
                    $piece+=$piece_arr["piece"];
                }
            }
            $prod_pieces[$product_id]=$piece;
        }
        foreach($prod_pieces as $product_id=>$piece){
            SQL::query("UPDATE product SET piece=$piece WHERE id=$product_id","webshop_product");
        }
    }
}