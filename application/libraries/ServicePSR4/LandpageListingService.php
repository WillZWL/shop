<?php
namespace ESG\Panther\Service;

class LandpageListingService extends BaseService
{
    public $product_service;

    public function __construct()
    {
        parent::__construct();
        $this->product_service = new ProductService;
    }

    public function getLandpageList($where = [], $option = [])
    {
        return $this->getDao('LandpageListing')->getLandpageList($where, $option);
    }

    public function cronUpdateSeller($type)
    {
        $platform_lsit = $this->getDao('SellingPlatform')->getList(['status' => 1,'type' => 'WEBSITE'], ['limit' => -1]);
        foreach ($platform_lsit as $platform_obj) {
            $platform_id = $platform_obj->getSellingPlatformId();
            $this->updateByPlatformAndType($platform_id, $type);
        }
    }

    public function updateByPlatformAndType($platform_id, $type)
    {
        $where = ['catid' => 0, 'platform_id' => $platform_id, 'type' => $type, 'mode' => 'A'];
        $num = $this->getDao('LandpageListing')->getNumRows($where);
        if ($num > 0) {
            $seller_list = $this->getSellerList(['type' => $type, 'platform_id' => $platform_id, 'limit' => $num]);
            if ($seller_list) {
                $need_update_lsit = $this->getDao('LandpageListing')->getList($where, ['limit' => -1]);
                foreach ($need_update_lsit as $k => $need_update_obj) {
                    $rank = $need_update_obj->getRank();
                    $seller_obj = $this->getSellerList(['type' => $type, 'platform_id' => $platform_id, 'cat_id'=> $rank, 'limit' => 1]);
                    $seller_sku = $seller_obj->getSku();
                    $need_update_obj->setSelection($seller_sku);
                    $this->getDao('LandpageListing')->update($need_update_obj);
                }
            } else {
                $res = 'No Sku Match the Rule';
                return $res;
            }
        }
    }

    public function getSellerList($option)
    {
        $type = $option['type'];
        $platform_id = $option['platform_id'];
        if ($option['cat_id'] > 0) {
            $where['p.cat_id'] = $option['cat_id'];
        }
        $where['p.status'] = 2;
        $where['p.website_quantity > 0'] = NULL;
        $where['p.website_status'] = 'I';
        $where['pr.platform_id'] = $platform_id;
        $where['p.sourcing_status'] = 'A';
        $where['pr.price > 150'] = NULL;
        switch ($type) {
            case 'BS':
                return $this->getBestSellerLsit($where, $option['limit']);
                break;
            case 'LA':
                return $this->getLatestSellerLsit($where, $option['limit']);
                break;
            default:
                return false;
                break;
        }
    }

    public function getBestSellerLsit($where, $limit)
    {
        $option = ['limit' => $limit, 'orderby' => 'sum(sid.qty) desc', 'group_by' => 'sid.item_sku'];
        $date = date("Y-m-d",strtotime("-7 days")); // a week ago
        $where["sid.create_on > '$date'"] = NULL;
        $sku_list = $this->getDao('Product')->getBestSellerLsit($where, $option);
        return $sku_list;
    }

    public function getLatestSellerLsit($where, $limit)
    {
        $option = ['limit' => $limit,'orderby' => 'p.create_on desc'];
        $sku_list = $this->getDao('Product')->getLatestSellerLsit($where, $option);
        return $sku_list;
    }
}
