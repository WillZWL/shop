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
                , google_product_id, availability, condition, result
                , create_on, create_at, create_by, modify_at, modify_by)
                select " . $requestBatchId . " as request_batch_id, platform_id, sku, item_group_id, colour_id, colour_name, target_country, content_language, title
                            , google_product_category, product_type
                            , cat_id, cat_name
                            , brand_name, gtin, mpn, upc, ean 
                            , shipping_weight_value, image_link, link, currency, price, custom_attribute_promo_id, description, google_product_status
                            , ref_display_quantity, ref_website_quantity, ref_listing_status, ref_website_status, ref_exdemo, ref_is_advertised
                            , google_product_id, if((ref_display_quantity <=0 || ref_website_quantity <=0), 'N', 'Y') as availability, condition, 'N' as result, now(), '2130706433', '" . $userId . "', '2130706433', '" . $userId . "'
                from pending_google_api_request";

        $result = $this->db->query($sql);
//        print $sql;
        return $result;
    }
    
    public function updateGoogleApiRequestFields($batchId) {
        $sql = "update google_api_request set google_product_status='D' 
                where request_batch_id=" . $batchId . " and (availability <> 'N' or ref_listing_status <> 'N' or ref_is_advertised='Y')";

        $result = $this->db->query($sql);
//        print $sql;
        return $result;
    }
}
