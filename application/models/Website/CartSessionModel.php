<?php
namespace AtomV2\Models\Website;

use AtomV2\Service\CartSessionService;

class CartSessionModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->cart_session_service = new CartSessionService;
        // $this->load->model('marketing/product_model');
        // $this->load->library('service/cart_session_service');
        // $this->load->library('service/ra_product_service');
        // $this->load->library('service/best_seller_service');
        // $this->load->library('service/website_service');
        // $this->load->library('service/category_service');
        // $this->load->library('service/warranty_service');
    }

    public function addItemQty($sku, $qty, $platform)
    {
        if (isset($_SESSION['cart'][$platform][$sku])) {
            // return $this->cart_session_service->updateItemQty($sku, $qty, $platform);
        } else {
            return $this->cart_session_service->addItemQty($sku, $qty, $platform);
        }
    }

    public function get_cart_info()
    {
        return $this->cart_session_service->get_cart_info();
    }

    public function get_detail($platform)
    {
        return $this->cart_session_service->get_detail($platform);
    }

    public function get($platform = NULL)
    {
        if (is_null($platform)) {
            $platform = PLATFORMID;
        }
        return $this->cart_session_service->get($platform);
    }

    public function update($sku, $qty, $platform)
    {
        return $this->cart_session_service->modify($sku, $qty, $platform);
    }

    public function get_cart($platform = NULL)
    {
        if (is_null($platform)) {
            $platform = defined(PLATFORMID) ? PLATFORMID : "WSUS";
        }
        return $this->cart_session_service->get_cart($platform);
    }

    public function remove($sku, $platform = NULL)
    {
        if (is_null($platform)) {
            $platform = PLATFORMID;
        }
        return $this->cart_session_service->remove($sku, $platform);
    }


    public function get_warranty_by_sku($sku, $platform, $lang_id = "en")
    {
        return $this->warranty_service->get_warranty_by_sku($sku, $platform, $lang_id);
    }

    public function get_warranty_list_by_sku($sku, $platform, $lang_id = "en")
    {
        return $this->warranty_service->get_warranty_list_by_sku($sku, $platform, $lang_id);
    }

    public function get_ra_prod_list($sku, $platform, $lang_id = "en")
    {
        $where["rap.sku"] = $sku;
        $where["rag.warranty"] = '0';
        $where["platform_id"] = $platform;
        $where["pr.listing_status"] = 'L';
        $where["p.status"] = '2';
        $where["price >"] = '0';
        $where["language_id"] = $lang_id;

        $where["website_status != "] = 'O';     # items that are not OOS, SBF#1584
        $where["website_quantity > "] = '0';        # items that are not OOS, SBF#1584

        $option["array_list"] = 1;
        $data = array();
        if ($ra_list = $this->ra_product_service->get_ra_product_w_group_name($where, $option, $lang_id)) {
            $data['ra_list'] = $ra_list['ra_list'];
            $data['ra_group_list'] = $ra_list['ra_group_list'];
        }

        if (($prod = $this->product_service->get(array("sku" => $sku))) &&
            ($recommended = $this->best_seller_service->get_ra_bs_list($prod->get_sub_cat_id(), $platform, $lang_id, 8, TRUE))
        ) {
            $data["recommended"] = $recommended;
        }
        return $data;
    }

    public function get_prod_url($sku, $relative_path = FALSE)
    {
        return $this->website_service->get_prod_url($sku, $relative_path);
    }

    public function get_product_content($where = array())
    {
        return $this->product_model->get_product_content($where);
    }
}
