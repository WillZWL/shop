<?php
namespace ESG\Panther\Dao;

class GoogleApiRequestDao extends BaseDao
{
    private $tableName = "google_api_request";
    private $voClassName = "GoogleApiRequestVo";

    public function __construct() {
        parent::__construct();
    }

    public function getVoClassname() {
        return $this->voClassName;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function cloneGoogleApiRequestDataWithBatchId($requestBatchId) {
        $userId = $this->getUserId();
        $sql = "insert into google_api_request(request_batch_id, platform_id, sku, item_group_id, colour_id, colour_name, target_country, content_language, title
                , google_product_category, product_type
                , cat_id, cat_name
                , brand_name, gtin, mpn, upc, ean 
                , shipping_weight_value, image_link, link, currency, price, custom_attribute_promo_id, description, google_product_status
                , ref_display_quantity, ref_website_quantity, ref_listing_status, ref_website_status, ref_exdemo, ref_is_advertised
                , google_product_id, availability, result
                , create_on, create_at, create_by, modify_at, modify_by)
                select " . $requestBatchId . " as request_batch_id, platform_id, sku, item_group_id, colour_id, colour_name, target_country, content_language, title
                            , google_product_category, product_type
                            , cat_id, cat_name
                            , brand_name, gtin, mpn, upc, ean 
                            , shipping_weight_value, image_link, link, currency, price, custom_attribute_promo_id, description, google_product_status
                            , ref_display_quantity, ref_website_quantity, ref_listing_status, ref_website_status, ref_exdemo, ref_is_advertised
                            , google_product_id, if((ref_display_quantity <=0 || ref_website_quantity <=0), 'N', 'Y') as availability, 'N' as result, now(), '2130706433', '" . $userId . "', '2130706433', '" . $userId . "'
                from pending_google_api_request";

        $result = $this->db->query($sql);
//        print $sql;
        return $result;
    }
    
    public function updateGoogleApiRequestFields($batchId) {
        $sql = "update google_api_request set google_product_status='D' 
                where request_batch_id=" . $batchId . " and (availability <> 'Y' or ref_listing_status <> 'L' or ref_is_advertised <> 'Y')";

        $result = $this->db->query($sql);
//        print $sql;
        return $result;
    }
/*
    public function insertBatchRequestToPriceExtend($batchId) {
        $userId = $this->getUserId();
        $sql = "insert ignore into price_extend (sku, platform_id, ext_status, last_update_result, create_on, create_at, create_by, modify_at, modify_by)
                select sku, platform_id, CONCAT(google_product_status, result), key_message, now(), '2130706433', '" . $userId . "', '2130706433', '" . $userId . "' from google_api_request
                where request_batch_id=" . $batchId;

        return $this->db->query($sql);
    }
*/
    public function updateBatchRequestToPrice($batchId) {
        $userId = $this->getUserId();
        $sql = "update price pr
                inner join google_api_request gar on gar.sku=pr.sku and gar.platform_id=pr.platform_id
                set google_status=CONCAT(gar.google_product_status, gar.result), google_update_result=CONCAT(now(), " " ,gar.key_message), gar.modify_by='" . $userId . "'
                where gar.request_batch_id=" . $batchId;

        return $this->db->query($sql);
    }
}
