<?php

namespace ESG\Panther\Service;

class AffiliateSkuPlatformService extends BaseService
{
    public function set_sku_feed_status($sku, $platform_id, $affiliate_id, $status_id)
    {
        return $this->getDao('AffiliateSkuPlatform')->set_sku_feed_status($sku, $platform_id, $affiliate_id, $status_id);
    }

    public function get_sku_feed_status($sku, $platform_id)
    {
        return $this->getDao('AffiliateSkuPlatform')->get_sku_feed_status($sku, $platform_id);
    }

    public function get_sku_feed_list($affiliate_id)
    {
        return $this->getDao('AffiliateSkuPlatform')->get_sku_feed_list($affiliate_id);
    }

    public function get_feed_list($platform_id = "")
    {
        return $this->getDao('AffiliateSkuPlatform')->get_feed_list($platform_id);
    }

    public function get_feed_list_by_sku($sku, $platform_id, $status=0)
    {
        return $this->getDao('AffiliateSkuPlatform')->get_feed_list_by_sku($sku, $platform_id, $status);
    }

    public function getAffiliateFeedListWithInfo($where=array(), $option=array())
    {
        return $this->getDao('AffiliateSkuPlatform')->getAffiliateFeedListWithInfo($where, $option);
    }
}