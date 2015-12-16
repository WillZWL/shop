<?php
namespace ESG\Panther\Service;

class GoogleService extends BaseService
{
    const FUNC_GOOGLE_CONTENT_API = "Content API";
    const FUNC_GOOGLE_ADWORD_API = "Aword API";

    public $adwordAccountInfo = [["accountId" => "493-907-8910", "accountName" => "API Test Account"]
                        , ["accountId" => "212-603-9902", "accountName" => "VB AU"]
                        , ["accountId" => "361-241-0604", "accountName" => "VB ES"]
                        , ["accountId" => "316-460-3467", "accountName" => "VB FR"]
                        , ["accountId" => "899-782-9704", "accountName" => "VB IT"]];

    public $shoppingAcctInfo = [
        "WEBAU" => ["account_name" => "AheadDigital AU", "account_id" => 106487544, "country" => array("AU"), "language" => array("en")]
        , "WEBNZ" => ["account_name" => "AheadDigital NZ", "account_id" => 106491401, "country" => array("NZ"), "language" => array("en")]
        , "WEBES" => ["account_name" => "BuhoLoco ES", "account_id" => 106487978, "country" => array("ES"), "language" => array("es")]
        , "WEBGB" => ["account_name" => "DigitalDiscount UK", "account_id" => 106482750, "country" => array("GB"), "language" => array("en")]
        , "WEBPL" => ["account_name" => "ElektroRaj PL", "account_id" => 106487577, "country" => array("PL"), "language" => array("pl")]
        , "WEBBE" => ["account_name" => "NumeriStock BE", "account_id" => 106487592, "country" => array("BE"), "language" => array("fr")]
        , "WEBFR" => ["account_name" => "NumeriStock FR", "account_id" => 106487574, "country" => array("FR"), "language" => array("fr")]
        , "WEBIT" => ["account_name" => "NuovaDigitale IT", "account_id" => 106501714, "country" => array("IT"), "language" => array("it")]];

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
