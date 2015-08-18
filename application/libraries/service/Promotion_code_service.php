<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Promotion_code_service extends Base_service
{

    public $promo_code = NULL;
    public $platform_id;
    public $currency_id;
    public $amount;
    public $item_list = array();
    public $brand_list;
    public $cat_list;
    public $sub_cat_list;
    public $sub_sub_cat_list;
    public $country_id;
    public $price_list;
    public $redemption = -1;
    public $total_redemption = -1;
    public $email;

    // Please use getter setter instead of public variable
    private $reg_srv;
    private $prod_srv;
    private $promotion_code_obj;
    private $valid = 1;
    private $error = 0;
    private $error_code;
    private $free_item = array();
    private $discount = 0;
    private $disc_amount = 0;
    private $free_delivery = 0;
    private $disc_item_list = array();
    private $delivery_mode = NULL;
    private $default_delivery;
    private $config;

    public function __construct($promo_code = NULL)
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Promotion_code_dao.php");
        $this->set_dao(new Promotion_code_dao());
        include_once(APPPATH . "libraries/service/Region_service.php");
        $this->set_reg_srv(new Region_service());
        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->set_prod_srv(new Product_service());
        include_once(APPPATH . "libraries/service/Platform_biz_var_service.php");
        $this->set_pbv_srv(new Platform_biz_var_service());
        if (!is_null($promo_code)) {
            $this->promo_code = $promo_code;
        }
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        $this->set_default_delivery($this->get_config()->value_of("default_delivery_type"));
    }

    public function set_pbv_srv($value)
    {
        $this->pbv_srv = $value;
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function get_promo_disc_info($cart_price = "", $platform_id = "", $promo_code = "", $sku = "")
    {
        if (empty($cart_price) || empty($platform_id) || empty($promo_code) || empty($sku)) {
            return false;
        }

        $this->promo_code = $promo_code;
        $this->item_list = array($sku => 1);
        $this->platform_id = $platform_id;
        $this->amount = $cart_price;

        if (!$this->promotion_code_obj = $this->get_dao()->get(array("code" => $promo_code))) {
            return false;
        }

        $this->valid_item_list();
        $this->valid_relevant_prod();
        $this->valid_brand_id();
        $this->valid_sub_sub_cat_id();
        $this->valid_sub_cat_id();
        $this->valid_cat_id();
        $this->valid_currency_id();
        $this->valid_country_id();
        //$this->valid_region_id();
        $this->valid_email();
        $this->valid_redemption();
        $this->valid_total_redemption();
        $this->valid_biz_type();

        if ($this->valid) {
            $disc_type = $this->promotion_code_obj->get_disc_type();
            switch ($disc_type) {
                case "FD":
                    $this->valid_over_amount();
                    $this->valid_delivery_mode();
                    if ($this->valid) {
                        return array("valid" => $this->valid, "type" => $disc_type, "free_delivery" => 1);
                    }
                    break;
                case "FI":
                    $this->valid_over_amount();
                    if ($this->valid) {
                        $fi_sku = $this->promotion_code_obj->get_free_item_sku();
                        if ($fi_obj = $this->get_prod_srv()->get(array("sku" => $fi_sku))) {
                            if ($fi_obj->get_website_status() == "O" || $fi_obj->get_status() == "0" || $fi_obj->get_website_quantity() == 0) {
                                $this->error = "FI";
                            } else {
                                $this->free_item = array("sku" => $fi_sku, "image" => $fi_obj->get_image(),
                                    "qty" => max($this->redemption, 1), "name" => $fi_obj->get_name(), "price" => 0, "total" => 0, "cost" => 0,
                                    "vat_total" => 0, "promo" => 1);
                            }
                        } else {
                            $this->error = "FI";
                        }
                        return array("valid" => $this->valid, "type" => $disc_type, "error" => $this->error, "error_code" => $this->error_code, "free_item" => $this->free_item);
                    }
                    break;
                case "A":
                case "P":
                    $ar_disc_level_value = @explode(",", $this->promotion_code_obj->get_disc_level_value());
                    switch ($this->promotion_code_obj->get_disc_level()) {
                        case "PD":
                            $disc_pd_list = array_flip($ar_disc_level_value);
                            if ($int_prod_list = @array_intersect_key($disc_pd_list, $this->item_list)) {
                                $int_prod_list = array_flip($int_prod_list);
                            }
                            $total_amount = $this->get_disc_level_amount($int_prod_list);
                            break;
                        case "CAT":
                            $total_amount = $this->get_disc_level_amount($this->cat_list[$ar_disc_level_value[0]]);
                            break;
                        case "SCAT":
                            $total_amount = $this->get_disc_level_amount($this->sub_cat_list[$ar_disc_level_value[1]]);
                            break;
                        case "SSCAT":
                            $total_amount = $this->get_disc_level_amount($this->sub_sub_cat_list[$ar_disc_level_value[2]]);
                            break;
                        case "BN":
                            $total_amount = $this->get_disc_level_amount($this->brand_list[$ar_disc_level_value[0]]);
                            break;
                        default:
                            $total_amount = $this->amount;
                            break;
                    }
                    for ($i = 1; $i < 6; $i++) {
                        $func = "get_over_amount_" . $i;
                        $disc_func = "get_discount_" . $i;
                        $disc_value = $this->promotion_code_obj->$func();
                        $discount = $this->promotion_code_obj->$disc_func();

                        if ($discount) {
                            if ($this->amount > $disc_value) {
                                $this->discount = $discount;
                                $this->disc_amount = $disc_type == "A" ? $this->discount : (number_format($total_amount * $this->discount / 100, 2, '.', ''));
                            }
                        }
                    }
                    return array("valid" => $this->valid, "type" => $disc_type, "discount" => $this->discount, "disc_amount" => $this->disc_amount);
                    break;
            }
        }
        return false;
    }

    protected function valid_item_list()
    {
        if ($this->valid) {
            if ($this->item_list) {
                $prod_srv = $this->get_prod_srv();
                foreach ($this->item_list as $sku => $qty) {
                    if ($prod_obj = $prod_srv->get_dao()->get_product_with_price($sku, $this->platform_id)) {
                        $this->brand_list[$prod_obj->get_brand_id()][] = $sku;
                        $this->cat_list[$prod_obj->get_cat_id()][] = $sku;
                        $this->sub_cat_list[$prod_obj->get_sub_cat_id()][] = $sku;
                        $this->sub_sub_cat_list[$prod_obj->get_sub_sub_cat_id()][] = $sku;
                        $this->price_list[$sku] = $prod_obj->get_price() * 1;
                    }
                }
            } else {
                $this->valid = 0;
            }
        }
    }

    public function get_prod_srv()
    {
        return $this->prod_srv;
    }

    public function set_prod_srv(Base_service $service)
    {
        $this->prod_srv = $service;
    }

    protected function valid_relevant_prod()
    {
        if ($this->valid && ($relevant_prod = $this->promotion_code_obj->get_relevant_prod())) {
            $relevant_prod = @explode(",", $relevant_prod);
            $relevant_prod = array_flip($relevant_prod);
            if ($int_prod_list = @array_intersect_key($relevant_prod, $this->item_list)) {
                foreach ($int_prod_list as $sku => $rskey) {
                    if ($this->item_list[$sku] > $this->redemption) {
                        $this->redemption = $this->item_list[$sku];
                    }
                }
            } else {
                $this->valid = 0;
            }
        }
    }

    protected function valid_brand_id()
    {
        if ($this->valid && ($brand_id = $this->promotion_code_obj->get_brand_id())) {
            if (isset($this->brand_list[$brand_id])) {
                foreach ($this->brand_list[$brand_id] as $sku) {
                    if ($this->item_list[$sku] > $this->redemption) {
                        $this->redemption = $this->item_list[$sku];
                    }
                }
            } else {
                $this->valid = 0;
            }
        }
    }

    protected function valid_sub_sub_cat_id()
    {
        if ($this->valid && ($sub_sub_cat_id = $this->promotion_code_obj->get_sub_sub_cat_id())) {
            if (isset($this->sub_sub_cat_list[$sub_sub_cat_id])) {
                foreach ($this->sub_sub_cat_list[$sub_sub_cat_id] as $sku) {
                    if ($this->item_list[$sku] > $this->redemption) {
                        $this->redemption = $this->item_list[$sku];
                    }
                }
            } else {
                $this->valid = 0;
            }
        }
    }

    protected function valid_sub_cat_id()
    {
        if ($this->valid && ($sub_cat_id = $this->promotion_code_obj->get_sub_cat_id())) {
            if (isset($this->sub_cat_list[$sub_cat_id])) {
                foreach ($this->sub_cat_list[$sub_cat_id] as $sku) {
                    if ($this->item_list[$sku] > $this->redemption) {
                        $this->redemption = $this->item_list[$sku];
                    }
                }
            } else {
                $this->valid = 0;
            }
        }
    }

    protected function valid_cat_id()
    {
        if ($this->valid && ($cat_id = $this->promotion_code_obj->get_cat_id())) {
            if (isset($this->cat_list[$cat_id])) {
                foreach ($this->cat_list[$cat_id] as $sku) {
                    if ($this->item_list[$sku] > $this->redemption) {
                        $this->redemption = $this->item_list[$sku];
                    }
                }
            } else {
                $this->valid = 0;
            }
        }
    }

    protected function valid_currency_id()
    {
        if ($this->valid && ($currency_id = $this->promotion_code_obj->get_currency_id())) {
            if ($platform_obj = $this->get_pbv_srv()->get(array("selling_platform_id" => $this->platform_id))) {
                $this->currency_id = $platform_obj->get_platform_currency_id();
                if ($currency_id != $this->currency_id) {
                    $this->valid = 0;
                }
            } else {
                $this->valid = 0;
            }
        }
    }

    public function get_pbv_srv()
    {
        return $this->pbv_srv;
    }

    protected function valid_country_id()
    {
        if ($this->valid && ($country_id = $this->promotion_code_obj->get_country_id()) && $this->country_id) {
            if ($country_id != $this->country_id) {
                $this->valid = 0;
            }
        }
    }

    protected function valid_email()
    {
        if ($this->valid && $this->email && ($chk_email = $this->promotion_code_obj->get_email()) && !preg_match('/^' . str_replace('%', '.*', $chk_email) . '$/', trim($this->email))) {
            $this->valid = 0;
        }
    }

    protected function valid_redemption()
    {
        if ($this->valid && ($redemption = $this->promotion_code_obj->get_redemption()) != -1) {
            if ($this->redemption > $redemption || $this->redemption == -1) {
                $this->redemption = $redemption;
                if ($this->redemption == 0) {
                    $this->valid = 0;
                }
            }
        }
    }

    protected function valid_total_redemption()
    {
        if ($this->valid && (($total_redemption = $this->promotion_code_obj->get_total_redemption()) != -1)) {
            $total_redemption_left = $total_redemption - $this->promotion_code_obj->get_no_taken();
            if (0 >= $total_redemption_left) {
                $this->valid = 0;
            }
        }
    }

    protected function valid_biz_type()
    {
        include_once(APPPATH . "libraries/service/Selling_platform_service.php");
        $this->set_sp_srv(new Selling_platform_service());

        $sp_obj = $this->get_sp_srv()->get(array("id" => $this->platform_id));

        if ($this->valid && $this->promotion_code_obj->get_code() == 'skypeRTX50' && $sp_obj->get_type() == 'WEBSITE') {
            $this->valid = 0;
        }
    }

    public function set_sp_srv($value)
    {
        $this->sp_srv = $value;
    }

    public function get_sp_srv()
    {
        return $this->sp_srv;
    }

    protected function valid_over_amount()
    {
        if ($this->valid && ($over_amount = $this->promotion_code_obj->get_over_amount())) {
            if ($this->amount <= $over_amount) {
                $this->valid = 0;
            }
        }
    }

    protected function valid_delivery_mode()
    {
        if ($this->valid && ($disc_level_value = $this->promotion_code_obj->get_disc_level_value()) != "All") {
            if ($disc_level_value != $this->get_delivery_mode() && $disc_level_value == $this->get_default_delivery()) {
                $this->valid = 0;
                $this->error = "FD";
                $this->error_code = $disc_level_value;
            }
        }
    }

    public function get_delivery_mode()
    {
        return $this->delivery_mode;
    }

    public function set_delivery_mode($value)
    {
        $this->delivery_mode = $value;
    }

    public function get_default_delivery()
    {
        return $this->default_delivery;
    }

    public function set_default_delivery($value)
    {
        $this->default_delivery = $value;
    }

    protected function get_disc_level_amount($ar_list)
    {
        $total_amount = 0;
        $ar_data = $ar_sku = $ar_price = array();

        if ($ar_list) {
            foreach ($ar_list as $sku) {
                $ar_data[] = array("price" => $this->price_list[$sku], "sku" => $sku);
                $ar_price[] = $this->price_list[$sku];
            }
            array_multisort($ar_price, SORT_ASC, $ar_data);
            $remain_qty = $this->redemption;
            foreach ($ar_data as $data) {
                $price = $data["price"];
                $sku = $data["sku"];
                $qty = $this->item_list[$sku];

                if ($this->promotion_code_obj->get_redemption() != -1) {
                    if ($qty > $remain_qty) {
                        $qty = $remain_qty;
                    }

                    $remain_qty -= $qty;
                }

                $total_amount += $price * $qty;
                $this->disc_item_list[$sku] = $qty;

                if (!$remain_qty) {
                    break;
                }
            }
        }
        return $total_amount;
    }

    public function check_promotion_code($skip_valid = 0)
    {
        $this->get_promotion_code_obj();
        $this->valid_item_list();
        $this->valid_relevant_prod();
        $this->valid_brand_id();
        $this->valid_sub_sub_cat_id();
        $this->valid_sub_cat_id();
        $this->valid_cat_id();
        $this->valid_currency_id();
        $this->valid_country_id();
        //$this->valid_region_id();
        $this->valid_email();
        $this->valid_redemption();
        $this->valid_total_redemption();

        // temporary hardcode to remove promo code 'skypeRTX50' from website product
        $this->valid_biz_type();

        if ($this->valid || $skip_valid) {
            $disc_type = $this->promotion_code_obj->get_disc_type();
            switch ($disc_type) {
                case "FD":
                    $this->valid_over_amount();
                    $this->valid_delivery_mode();
                    if ($this->valid) {
                        return array("valid" => $this->valid, "free_delivery" => 1, "promotion_code_obj" => $this->promotion_code_obj);
                    }
                    break;
                case "FI":
                    $this->valid_over_amount();
                    if ($this->valid) {
                        $fi_sku = $this->promotion_code_obj->get_free_item_sku();
                        if ($fi_obj = $this->get_prod_srv()->get(array("sku" => $fi_sku))) {
                            if ($fi_obj->get_website_status() == "O" || $fi_obj->get_status() == "0" || $fi_obj->get_website_quantity() == 0) {
                                $this->error = "FI";
                            } else {
                                $this->free_item = array("sku" => $fi_sku, "image" => $fi_obj->get_image(),
                                    "qty" => max($this->redemption, 1), "name" => $fi_obj->get_name(), "price" => 0, "total" => 0, "cost" => 0,
                                    "vat_total" => 0, "promo" => 1);
                            }
                        } else {
                            $this->error = "FI";
                        }
                        return array("valid" => $this->valid, "error" => $this->error, "error_code" => $this->error_code, "free_item" => $this->free_item, "promotion_code_obj" => $this->promotion_code_obj);
                    }
                    break;
                case "A":
                case "P":
                    $ar_disc_level_value = @explode(",", $this->promotion_code_obj->get_disc_level_value());
                    switch ($this->promotion_code_obj->get_disc_level()) {
                        case "PD":
                            $disc_pd_list = array_flip($ar_disc_level_value);
                            if ($int_prod_list = @array_intersect_key($disc_pd_list, $this->item_list)) {
                                $int_prod_list = array_flip($int_prod_list);
                            }
                            $total_amount = $this->get_disc_level_amount($int_prod_list);
                            break;
                        case "CAT":
                            $total_amount = $this->get_disc_level_amount($this->cat_list[$ar_disc_level_value[0]]);
                            break;
                        case "SCAT":
                            $total_amount = $this->get_disc_level_amount($this->sub_cat_list[$ar_disc_level_value[1]]);
                            break;
                        case "SSCAT":
                            $total_amount = $this->get_disc_level_amount($this->sub_sub_cat_list[$ar_disc_level_value[2]]);
                            break;
                        case "BN":
                            $total_amount = $this->get_disc_level_amount($this->brand_list[$ar_disc_level_value[0]]);
                            break;
                        default:
                            $total_amount = $this->amount;
                            break;
                    }

                    if ($total_amount > 0) {
                        for ($i = 1; $i < 6; $i++) {
                            $func = "get_over_amount_" . $i;
                            $disc_func = "get_discount_" . $i;
                            $disc_value = $this->promotion_code_obj->$func();
                            $discount = $this->promotion_code_obj->$disc_func();
                            if ($discount) {
                                $this->valid = 0;
                                if ($this->amount > $disc_value) {
                                    $this->valid = 1;
                                    $this->discount = $discount;
                                    $this->disc_amount = $disc_type == "A" ? $this->discount : (number_format($total_amount * $this->discount / 100, 2, '.', ''));
                                }
                            }
                        }
                    }
                    return array("valid" => $this->valid, "discount" => $this->discount, "disc_amount" => $this->disc_amount, "promotion_code_obj" => $this->promotion_code_obj, "disc_item_list" => $this->disc_item_list);
                    break;
            }
        }
        return array("valid" => $this->valid, "error" => $this->error, "error_code" => $this->error_code);
    }

    protected function get_promotion_code_obj()
    {
        $cur_date = date("Y-m-d");
        $option = array("limit" => 1, "orderby" => "create_on DESC");

        if (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $this->promo_code)) {
            $email = mysql_real_escape_string($this->promo_code);
            $where_str = "(code='" . $email . "' OR '" . $email . "' LIKE email)";
            $where[$where_str] = NULL;
        } else {
            $where["code"] = $this->promo_code;
        }
        $where["status"] = 1;
        $where["(ISNULL(expire_date) OR expire_date >= '" . $cur_date . "')"] = null;
        if (!is_null($this->promo_code) && ($promotion_code_obj = $this->get_list($where, $option))) {
            $this->promotion_code_obj = $promotion_code_obj;
        } else {
            $this->valid = 0;
        }
    }

    public function check_del_country()
    {
        $this->get_promotion_code_obj();
        $this->valid_country_id();
        //$this->valid_region_id();
        return $this->valid;
    }

    public function check_email()
    {
        $this->valid_email();
        return $this->valid;
    }

    public function update_no_taken()
    {
        $this->get_promotion_code_obj();
        if ($this->promotion_code_obj) {
            $this->promotion_code_obj->set_no_taken($this->promotion_code_obj->get_no_taken() + 1);
            $this->update($this->promotion_code_obj);
        }
    }

    protected function valid_region_id()
    {
        if ($this->valid && !$this->promotion_code_obj->get_country_id() && ($region_id = $this->promotion_code_obj->get_region_id()) && $this->country_id) {
            if (!$this->get_reg_srv()->region_country_dao->get_num_rows(array("region_id" => $region_id, "country_id" => $this->country_id))) {
                $this->valid = 0;
            }
        }
    }

    public function get_reg_srv()
    {
        return $this->reg_srv;
    }

    public function set_reg_srv(Base_service $service)
    {
        $this->reg_srv = $service;
    }
}



