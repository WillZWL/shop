<?php
namespace ESG\Panther\Service;

class GoogleService extends BaseService
{
    public $adwordAccountInfo = [["accountId" => "493-907-8910", "accountName" => "API Test Account"]
                        , ["accountId" => "212-603-9902", "accountName" => "VB AU"]
                        , ["accountId" => "361-241-0604", "accountName" => "VB ES"]
                        , ["accountId" => "316-460-3467", "accountName" => "VB FR"]
                        , ["accountId" => "899-782-9704", "accountName" => "VB IT"]];

    public $shoppingAcctInfo = [["account_name" => "ValueBasket.SG", "account_id" => 8384686, "country" => array("SG"), "language" => array("en")]
        , ["account_name" => "ValueBasket.it", "account_id" => 9674225, "country" => array("IT"), "language" => array("it")]
        , ["account_name" => "ValueBasket.fi", "account_id" => 11038072, "country" => array("FI"), "language" => array("en")]
        , ["account_name" => "ValueBasket.ch", "account_id" => 11328624, "country" => array("CH"), "language" => array("en")]
        , ["account_name" => "ValueBasket.fr", "account_id" => 7852736, "country" => array("FR"), "language" => array("fr")]
        , ["account_name" => "ValueBasket.com.au", "account_id" => 8113126, "country" => array("AU"), "language" => array("en")]
        , ["account_name" => "Valuebasket.be", "account_id" => 8121966, "country" => array("BE"), "language" => array("fr")]
        , ["account_name" => "ValueBasket.com", "account_id" => 8551995, "country" => array("GB", "CH"), "language" => array("en")]
        , ["account_name" => "ValueBasket.es", "account_id" => 15241301, "country" => array("ES"), "language" => array("es")]
        , ["account_name" => "ValueBasket.pl", "account_id" => 100892246, "country" => array("PL"), "language" => array("pl")]
        , ["account_name" => "ValueBasket.com", "account_id" => 101019203, "country" => array("US"), "language" => array("en")]];

    public function __construct() {
        parent::__construct();
    }
    
    public function getAdwordAccountInfoList() {
        return $this->adwordAccountInfo;
    }
    
    public function getShoppingAccountInfoList() {
        return $this->shoppingAcctInfo;
    }
}
