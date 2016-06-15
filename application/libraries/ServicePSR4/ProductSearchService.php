<?php
namespace ESG\Panther\Service;

use ESG\Panther\Models\Marketing\CategoryModel;
use ESG\Panther\Service\PriceWebsiteService;
use ESG\Panther\Service\PlatformBizVarService;


class ProductSearchService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = new CategoryModel;
    }

    public function getProductSearchList($where, $option)
    {
        if ($where['keyword']) {
            $res = [];
            if ($option['split_keyword']) {
                $where['skey'] = $this->formatSearchKey($where['keyword'], ".?");
                $resr_key = $option["num_rows"] ? "total" : "objlist";
                $res[ $resr_key] = $this->getDao('Product')->searchByProductName($where, $option);
                $res['skey'] = $where['skey'];
            } else {
                $res = $this->getDao('Product')->searchByProductName($where, $option);
            }

            return $res;
        }
    }

    public function formatSearchKey($skey = "", $replace = " ")
    {
        /*
         *  1.  Pre-process the search key to remove unnecessary character.
         *  2.  Insert a whitespace as default inbetween number and alphabet or any other characters as specified in the 2nd parameter
         *
         */
        $uf_arr = explode(" ", $skey);
        foreach ($uf_arr as $k => $v) {
            if ($v != "") {
                $v = trim(preg_replace('/[.,`\[\]\(\)\"\';\/\\\\?*\+]/', "$replace", $v));
                $v = trim(preg_replace('/([0-9]{1,})([a-zA-Z]{1,})/', "\\1$replace\\2", $v));
                $v = trim(preg_replace('/([a-zA-Z]{1,})([0-9]{1,})/', "\\1$replace\\2", $v));
                if ((trim(str_replace('.?', '', $v))) != "") {
                    $f_arr[] = $v;
                }
            } else {
                unset($uf_arr[$k]);
            }
        }
        $sk['unformated'] = $uf_arr;
        $sk['formated'] = $f_arr;

        return $sk;
    }


    public function getProductSearchListForSsLivePrice($platformId, $sku = '', $with_rrp = FALSE)
    {
        include_once APPPATH . 'helpers/price_helper.php';
        $priceSrv = new PriceWebsiteService();
        $pbvSrv = new PlatformBizVarService();

        $pbvObj = $pbvSrv->getPlatformBizVar($platformId);
        $langId = $pbvObj->getLanguageId();
        $language_path = APPPATH . "/language/" . $langId . "/nocontroller/data_feed.ini";
        if (file_exists($language_path)) {
            $lang = parse_ini_file($language_path);
        }

        if ($sku != '') {
            $sku_list = explode(',', $sku);
        }
        $json = array();
        foreach ($sku_list as $sku) {
            if ($listing_info = $priceSrv->getListingInfo($sku, $platformId, $langId)) {
                if ($with_rrp) {
                   
                    $rrp = $listing_info->getRrpPrice();
                    $price = $listing_info->getPrice();

                    $live_price_data = array();
                    $live_price_data[] = platform_curr_format($rrp);
                    $live_price_data[] = platform_curr_format($price);

                    /*if ($priceSrv->isDisplaySavingMessage() == 'T') {
                        $live_price_data[] = $lang['save'] . number_format(($rrp == 0 ? 0 : ($rrp - $price) / $rrp * 100), 0) . '%';
                    } else {
                        $live_price_data[] = '';
                    }*/
                    $live_price_data[] = $lang['save'] . number_format(($rrp == 0 ? 0 : ($rrp - $price) / $rrp * 100), 0) . '%';

                    $status = '';
                    switch ($listing_info->getStatus()) {
                        case 'I':
                            $status = $lang['in_stock'];
                            break;
                        case 'O':
                            $status = $lang['out_stock'];
                            break;
                        case 'P':
                            $status = $lang['pre_order'];
                            break;
                        case 'A':
                            $status = $lang['arriving'];
                            break;
                    }
                    $live_price_data[] = $listing_info->getStatus() == 'I' ? $listing_info->getQty() . " " . $status : $status;
                    $live_price_data[] = $listing_info->getStatus();

                    $json[$sku] = $live_price_data;
                } else {
                    $json[$sku] = platform_curr_format(random_markup($listing_info->getPrice()));
                }
            }
        }
        return json_encode($json);
    }

}