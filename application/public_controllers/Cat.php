<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Cat extends PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('website/website_model');
        // $this->load->model('marketing/product_model');
        // $this->load->model('marketing/category_model');
        // $this->load->model('marketing/banner_model');
        //$this->load->library('service/affiliate_service');
        // $this->load->library('service/price_website_service');
        $this->load->library('service/display_category_banner_service');
    }

    public function view($cat_id, $page = 1)
    {
        if (!$cat_obj = $this->sc['Category']->getCatInfoWithLang(array("c.id" => $cat_id, "ce.lang_id" => $this->get_lang_id(), "c.status" => 1), array("limit" => 1))) {
            $cat_obj = $this->sc['Category']->getCatInfoWithLang(array("c.id" => $cat_id, "ce.lang_id" => "en", "c.status" => 1), array("limit" => 1));
        }
        if (empty($cat_id) || !$cat_obj) {
            show_404('page');
        }
        //$this->affiliate_service->add_af_cookie($_GET);
        $level = $cat_obj->getLevel();
        $sort = $this->input->get('sort');
        $rpp = $this->input->get('rpp');
        //$page = $this->input->get('page');
        $brandId = $this->input->get('brand_id');
        $catPageData = $this->sc['categoryModel']->getProductForCategoryPage(PLATFORM, $cat_id, $level, $brandId, $sort, $rpp, $page, $langId);
        $data['sort'] = $sort;
		$data['pagination'] = 3;

        $data['show_discount_text'] = $this->sc['PriceWebsite']->isDisplaySavingMessage();

        $show_404 = TRUE;
        if ($catPageData["obj_list"]) {
            $i = 1;
            foreach ($catPageData["obj_list"] AS $key => $obj) {
                if ($obj) {
                    $show_404 = FALSE;
                    break;
                }
            }
        }
        if ($show_404) {
            show_404('page');
        }

        // generate left filter menu
        unset($option['limit']);
        if (!$rpp) {
            $rrp = 12;
        }
        $option['limit'] = $rpp;
        $option['offset'] = $rpp * ($page-1);
        $full_sku_list = $this->sc['categoryModel']->getWebsiteCatPageProductList($catPageData["criteria"], $option);
        $sku_list = [];
        foreach ($full_sku_list as $value) {
            $sku_list[] = $value->getSku();
        }
        $data['cat_result'] = $this->getCatFilterGridInfo($level, $sku_list);
        $data['brand_result'] = $this->getBrandFilterGridInfo($sku_list);
        $data["brand_id"] = $brand_id;
		$data["cat_id"] = $cat_id;
        $data['productList'] = $catPageData["obj_list"];
        $data['cat_obj'] = $cat_obj;
        $data['cat_name'] = $cat_obj->getName();
        $data['level'] = $level;

        // pagination variable
        $data['total_result'] = $catPageData["total"];
        $data['curr_page'] = $page;
        $data['total_page'] = (int)ceil($data['total_result'] / $rpp);
        $data['rpp'] = $rpp;

        // url
        $parent_cat_id = $this->sc['categoryModel']->getParentCatId($cat_id);
        $data['parent_cat_url'] = null;
        if ($level > 1) {
            $data['parent_cat_url'] = $this->sc['Website']->getCatUrl($parent_cat_id);
        }

        // meta tag
        $data['data']['lang_text'] = $this->getLanguageFile();
        $data["tracking_data"] = array("category_name" => $cat_name['cat'], "category_id" => $cat_id);
        $this->load->view('cat', $data);
    }

    public function getCatFilterGridInfo($level, $sku_list)
    {
        $condition = "p.sku IN ('" . implode("','", $sku_list) . "')";
        $where[$condition] = null;
        $where['p.status'] = 2;
        $where['scex.lang_id'] = $where['sscex.lang_id'] = $this->get_lang_id();

        switch ($level) {
            case 1:
                $option['groupby'] = "p.sub_cat_id";
                $option['orderby'] = "sc.priority";
                break;
            case 2:
                $option['groupby'] = "p.sub_sub_cat_id";
                $option['orderby'] = "ssc.priority";
                break;
            case 3:
            default:
                return null;
        }
        if ($rs = $this->sc['categoryModel']->getCatFilterGridInfo($level, $where, $option)) {
            foreach ($rs as $key => $val) {
                $rs[$key]['url'] = $this->sc['Website']->getCatUrl($val['id']);
            }
        }
        return $rs;
    }

    public function getBrandFilterGridInfo($sku_list)
    {
        $condition = "p.sku IN ('" . implode("','", $sku_list) . "')";
        $where[$condition] = null;
        $where['p.status'] = 2;
        $option['groupby'] = "p.brand_id";
        $option['orderby'] = "br.brand_name";

        return $this->sc['categoryModel']->getBrandFilterGridInfo($where, $option);
    }

    public function display_banner($cat_id = 0)
    {
        $category_banner = $this->display_category_banner_service->get_publish_banner($cat_id, 17, 1, PLATFORMCOUNTRYID, get_lang_id(), "PB");
        $banner = $category_banner;
        $banner_file_path = APPPATH . "public_views/banner_publish/publish_" . $banner["publish_key"] . ".php";
        if (file_exists($banner_file_path)) {
            include APPPATH . "public_views/banner_publish/publish_" . $banner["publish_key"] . ".php";
        }
    }

}

?>
