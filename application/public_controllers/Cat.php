<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Cat extends PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('website/website_model');
        $this->load->model('marketing/product_model');
        $this->load->model('marketing/category_model');
        $this->load->model('marketing/banner_model');
        $this->load->library('service/affiliate_service');
        $this->load->library('service/price_website_service');
        $this->load->library('service/display_category_banner_service');
    }

    public function view($cat_id, $page = 1)
    {

        if (!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $cat_id, "ce.lang_id" => $this->get_lang_id(), "c.status" => 1), array("limit" => 1))) {
            $cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id" => $cat_id, "ce.lang_id" => "en", "c.status" => 1), array("limit" => 1));
        }

        if (empty($cat_id) || !$cat_obj) {
            show_404('page');
        }

        $this->affiliate_service->add_af_cookie($_GET);

        $level = $cat_obj->get_level();
        $sort = $this->input->get('sort');
        $rpp = $this->input->get('rpp');
        $page = $this->input->get('page');
        $brandId = $this->input->get('brand_id');
        $catPageData = $this->category_model->getProductForCategoryPage(PLATFORMID, $cat_id, $level, $brandId, $sort, $rpp, $page, $langId);
        $data['sort'] = $sort;

        $data['show_discount_text'] = $this->price_website_service->is_display_saving_message();

        $show_404 = TRUE;
        if ($catPageData["obj_list"]) {
            $i = 1;
            // this flag is used to check against the list to make sure there is at least one available to be listed
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
        $option['limit'] = -1;
        $full_sku_list = $this->category_model->get_website_cat_page_product_list($catPageData["criteria"], $option);
        $data['cat_result'] = $this->get_cat_filter_grid_info($level, $full_sku_list);
        $data['brand_result'] = $this->get_brand_filter_grid_info($full_sku_list);
        $data["brand_id"] = $brand_id;

        $data['productList'] = $catPageData["obj_list"];
        $data['cat_obj'] = $cat_obj;
        $data['cat_name'] = $cat_obj->get_name();
        $data['level'] = $level;

        // pagination variable
        $data['total_result'] = $catPageData["total"];
        $data['curr_page'] = $page;
        $data['total_page'] = (int)ceil($data['total_result'] / $rpp);
        $data['rpp'] = $rpp;

        // url
        $parent_cat_id = $this->category_model->get_parent_cat_id($cat_id);
        $data['parent_cat_url'] = null;
        if ($level > 1) {
            $data['parent_cat_url'] = $this->website_model->get_cat_url($parent_cat_id);
        }

        // meta tag
        $data['data']['lang_text'] = $this->_get_language_file();
        $data["tracking_data"] = array("category_name" => $cat_name['cat'], "category_id" => $cat_id);

        $this->load->view('/default/cat', $data);
    }

    public function get_cat_filter_grid_info($level, $sku_list)
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

        if ($rs = $this->category_model->get_cat_filter_grid_info($level, $where, $option)) {
            foreach ($rs as $key => $val) {
                $rs[$key]['url'] = $this->website_model->get_cat_url($val['id']);
            }
        }
        return $rs;
    }

    public function get_brand_filter_grid_info($sku_list)
    {
        $condition = "p.sku IN ('" . implode("','", $sku_list) . "')";
        $where[$condition] = null;
        $where['p.status'] = 2;
        $option['groupby'] = "p.brand_id";
        $option['orderby'] = "br.brand_name";

        return $this->category_model->get_brand_filter_grid_info($where, $option);
    }

    function display_banner($cat_id = 0)
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
