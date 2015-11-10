<?php
namespace ESG\Panther\Dao;

class SiteConfigDao extends BaseDao
{
    private $table_name = 'site_config';
    private $vo_class_name = 'SiteConfigVo';

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getSiteInitialParameter($where, $option = [], $className = "SiteDto")
    {
        $option = ["limit" => 1];
        $this->db->from("site_config AS sc");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id=sc.platform", 'INNER');
        $this->db->join("selling_platform sp", "sp.selling_platform_id=sc.platform", 'INNER');
        $this->db->join("currency c", "currency_id=pbv.platform_currency_id", 'INNER');

        $select = "sp.type as platformType, sc.domain, site_name as siteName, lang, logo, email, sc.platform, sc.domain_type as domainType, sc.status as siteStatus, pbv.platform_country_id as platformCountryId, pbv.platform_currency_id as platformCurrencyId, pbv.sign_pos as signPos, pbv.dec_place as decPlace, pbv.dec_point as decPoint, pbv.thousands_sep as thousandsSep, c.sign, c.round_up_nearest_for_price_table as roundUpNearestForPriceTable, pbv.language_id as langId";
        return $this->commonGetList($className, $where, $option, $select);
    }
}
