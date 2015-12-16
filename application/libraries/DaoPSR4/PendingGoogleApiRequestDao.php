<?php
namespace ESG\Panther\Dao;

class PendingGoogleApiRequestDao extends BaseDao
{
    private $tableName = "pending_google_api_request";
    private $voClassName = "PendingGoogleApiRequestVo";

    public function __construct() {
        parent::__construct();
    }

    public function getVoClassname() {
        return $this->voClassName;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function clearPendingGoogleApiRequest($autoCommit = false) {
        $sql = "delete from pending_google_api_request";
        if (($result = $this->db->query($sql)) !== false) {
            if ($autoCommit)
                $this->db->query("commit");
        }
//        print $sql;
        return $result;
    }

    public function insertGoogleShoppingData($platformId = "WEBGB", $affiliateId = 'GOOGB', $sku = []) {
        $userId = $this->getUserId();
        $where = "";
        if ($sku) {
            if (is_array($sku)) {
                $query_str = "";
                foreach ($sku as $v) {
                    $query_str .= "'" . $v . "',";
                }
                $query_str = rtrim($query_str, ',');
                $where = " and pr.sku in (" . $query_str . ")";
            } else {
                $where = " and pr.sku in ('" . $sku . "')";
            }
        }
        $sql = "replace into pending_google_api_request(platform_id, sku, item_group_id, colour_id, colour_name, target_country, content_language, title
            , google_product_category, product_type
            , cat_id, cat_name
            , brand_name, mpn, upc, ean 
            , shipping_weight_value, image_link
            , link , currency, price, custom_attribute_promo_id, description
            , ref_display_quantity, ref_website_quantity, ref_listing_status, ref_website_status, ref_exdemo
            , google_product_id
            , create_on, create_at, create_by, modify_at, modify_by)
            SELECT pr.platform_id, p.sku, p.prod_grp_cd, p.colour_id, clr.colour_name, pbv.platform_country_id, pbv.language_id, IFNULL(map.product_name, pc.prod_name) prod_name
            , ext_c.ext_name google_product_category, concat(cat.name, ' > ', sc.name) as product_type
            , p.cat_id, cat.name cat_name
            , br.brand_name, pi.mpn, pi.upc, pi.ean
            , fc.weight prod_weight, CONCAT('http://', sco.domain, '/images/product/', p.sku, '.', p.image) image_url
            , CONCAT('http://', sco.domain, '/product/', p.sku) as link
            , pbv.platform_currency_id, pr.price, pr.google_promo_id, pc.detail_desc
            , p.display_quantity, p.website_quantity, pr.listing_status, p.website_status, p.ex_demo
            , CONCAT('online:', pbv.language_id, ':', pbv.platform_country_id, ':', pbv.platform_country_id, '-', p.sku) google_product_id
            , now(), 2130706433, '" . $userId . "', 2130706433, '" . $userId . "'
            FROM `product` `p`
            INNER JOIN `price` `pr` ON `pr`.`sku` = `p`.`sku` AND `pr`.`listing_status` = 'L'
            INNER JOIN `colour` `clr` ON `clr`.`colour_id` = `p`.`colour_id`
            INNER JOIN `platform_biz_var` `pbv` ON `pbv`.`selling_platform_id` = `pr`.`platform_id`
            INNER JOIN `category` `cat` ON `cat`.`id` = `p`.`cat_id`
            INNER JOIN `category` `sc` ON `sc`.`id` = `p`.`sub_cat_id`
            INNER JOIN `brand` `br` ON `br`.`id` = `p`.`brand_id`
            INNER JOIN `product_content` `pc` ON `pc`.`prod_sku` = `p`.`sku` AND `pc`.`lang_id` = `pbv`.`language_id`
            LEFT JOIN `freight_category` `fc` ON `fc`.`id` = `p`.`freight_cat_id`
            INNER JOIN `category_mapping` `map` ON `p`.`sku` = `map`.`category_mapping_id` AND `map`.`ext_party` = 'GOOGLEBASE' AND `map`.`level` = 0 AND `pbv`.`platform_country_id` = `map`.`country_id` AND `map`.`status` = 1 AND `map`.`product_name` <> ''
            INNER JOIN `ext_category_mapping` `ecm` ON `ecm`.`category_id`=if(p.sub_sub_cat_id = 0, if(p.sub_cat_id = 0, p.cat_id, p.sub_cat_id), p.sub_sub_cat_id) AND `ecm`.`ext_party`='GOOGLEBASE' AND `ecm`.`country_id` = `map`.`country_id`
            INNER JOIN `external_category` `ext_c` ON `ext_c`.`id` = `ecm`.`ext_id`
            LEFT JOIN `product_identifier` `pi` ON `pi`.`prod_grp_cd` = `p`.`prod_grp_cd` AND `pi`.`colour_id` = `p`.`colour_id` AND `pi`.`country_id` = `pbv`.`platform_country_id`  AND `pi`.`status` = 1
            INNER JOIN `site_config` `sco` ON `sco`.`platform` = '" . $platformId . "' and `sco`.`domain_type`=1
            LEFT JOIN `affiliate_sku_platform` `asp` ON `asp`.`affiliate_id` = '" . $affiliateId . "' and `asp`.`sku`=`p`.`sku` and `asp`.`platform_id`=`pr`.`platform_id`
            WHERE `p`.`status` = 2
            AND `pr`.`listing_status` = 'L'
            AND `p`.`website_status` in ('I', 'P')
            AND `pr`.`platform_id` = '" . $platformId . "'
            AND `pr`.`price` IS NOT NULL
            AND `pr`.`is_advertised` = 'Y'
            AND (`asp`.`status` != 1 or `asp`.`status` is null)";

        if (($result = $this->db->query($sql)) !== false) {
            $this->db->query("commit");
        }
        return $result;
    }
/*
	public function getGoogleShoppingData($platformId = "WEBGB", $affiliateId = 'GOOGB', $where, $classname="GoogleShoppingDataDto") {
		$option = ["limit" => -1];
		$this->db->from("product p");
		$this->db->join("price pr", "pr.sku = p.sku AND pr.listing_status = 'L'", "INNER");
		$this->db->join("colour clr", "clr.colour_id = p.colour_id", "INNER");
		$this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
		$this->db->join("category cat", "cat.id = p.cat_id", "INNER");
		$this->db->join("category sc", "sc.id = p.sub_cat_id", "INNER");
		$this->db->join("brand br", "br.id = p.brand_id", "INNER");
		$this->db->join("product_content pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "INNER");
		$this->db->join("freight_category fc", "fc.id = p.freight_cat_id", "LEFT");
		$this->db->join("category_mapping map", "p.sku = map.category_mapping_id AND map.ext_party = 'GOOGLEBASE' AND map.level = 0 AND pbv.platform_country_id = map.country_id AND map.status = 1 AND map.product_name <> ''", "INNER");
		$this->db->join("ext_category_mapping ecm", "ecm.category_id=if(p.sub_sub_cat_id = 0, if(p.sub_cat_id = 0, p.cat_id, p.sub_cat_id), p.sub_sub_cat_id) AND ecm.ext_party='GOOGLEBASE' AND ecm.country_id = map.country_id", "INNER");
		$this->db->join("external_category ext_c", "ext_c.id = ecm.ext_id", "INNER");
		$this->db->join("product_identifier pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id  AND pi.status = 1", "LEFT");
        $this->db->join("site_config sco", "sco.platform = '" . $platformId . "'", "INNER");
        $this->db->join("affiliate_sku_platform asp", "asp.affiliate_id = '" . $affiliateId . "' and asp.sku=p.sku and asp.platform_id=pr.platform_id", "LEFT");

		$this->db->where(["p.status" => 2
                        , "pr.listing_status" => "L"
                        , "p.website_status in ('I', 'P')" => null
                        , "pr.platform_id" => $platformId
                        , "pr.price IS NOT NULL" => null
                        , "pr.is_advertised" => "Y"
                        , "(asp.status != 1 or asp.status is null)" => null]);
		return $this->commonGetList($classname, $where, $option, 'pr.platform_id, pr.google_promo_id, p.sku, p.prod_grp_cd, p.version_id, p.colour_id, clr.colour_name, pbv.platform_country_id, pbv.language_id,
					IFNULL(map.product_name, pc.prod_name) prod_name, p.cat_id, cat.name cat_name, p.sub_cat_id, sc.name sub_cat_name, p.brand_id, br.brand_name, pi.mpn, pi.upc, pi.ean,
					, pc.detail_desc, fc.weight, p.image, pbv.platform_currency_id, pr.price,
					CONCAT("http://", sco.domain, "/images/product/", p.sku, ".", p.image) image_url,
					p.quantity, p.display_quantity, p.website_quantity, p.website_status, p.status prod_status, pr.listing_status, p.ex_demo, ext_c.ext_name google_product_category, fc.weight prod_weight
                    , CONCAT("online:", pbv.language_id, ":", pbv.platform_country_id, ":", pbv.platform_country_id, "-", p.sku) google_ref_id');
	}
*/
}
