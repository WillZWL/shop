<?php
namespace ESG\Panther\Service;

class PromotionCodeService extends BaseService
{

    public $promo_code = NULL;
    public $platform_id;
    public $currency_id;
    public $amount;
    public $item_list = [];
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
    private $promotion_code_obj;
    private $valid = 1;
    private $error = 0;
    private $error_code;
    private $free_item = [];
    private $discount = 0;
    private $disc_amount = 0;
    private $free_delivery = 0;
    private $disc_item_list = [];
    private $delivery_mode = NULL;
    private $default_delivery;
    private $config;

    public function __construct($promo_code = NULL)
    {
        parent::__construct();

        if (!is_null($promo_code)) {
            $this->promo_code = $promo_code;
        }

        $this->setDefaultDelivery($this->getDao('Config')->valueOf("default_delivery_type"));
    }

    public function get_promo_disc_info($cart_price = "", $platform_id = "", $promo_code = "", $sku = "")
    {
        if (empty($cart_price) || empty($platform_id) || empty($promo_code) || empty($sku)) {
            return false;
        }

        $this->promo_code = $promo_code;
        $this->item_list = [$sku => 1];
        $this->platform_id = $platform_id;
        $this->amount = $cart_price;

        if (!$this->promotion_code_obj = $this->getDao('PromotionCode')->get(["code" => $promo_code])) {
            return false;
        }

        $this->validItemList();
        $this->validRelevantProd();
        $this->validBrandId();
        $this->validSubSubCatId();
        $this->validSubCatId();
        $this->validCatId();
        $this->validCurrencyId();
        $this->validCountryId();
        //$this->validRegionId();
        $this->validEmail();
        $this->validRedemption();
        $this->validTotalRedemption();
        $this->validBizType();

        if ($this->valid) {
            $disc_type = $this->promotion_code_obj->getDiscType();
            switch ($disc_type) {
                case "FD":
                    $this->validOverAmount();
                    $this->validDeliveryMode();
                    if ($this->valid) {
                        return ["valid" => $this->valid, "type" => $disc_type, "free_delivery" => 1];
                    }
                    break;
                case "FI":
                    $this->validOverAmount();
                    if ($this->valid) {
                        $fi_sku = $this->promotion_code_obj->getFreeItemSku();
                        if ($fi_obj = $this->getDao('Product')->get(["sku" => $fi_sku])) {
                            if ($fi_obj->getWebsiteStatus() == "O" || $fi_obj->getStatus() == "0" || $fi_obj->getWebsiteQuantity() == 0) {
                                $this->error = "FI";
                            } else {
                                $this->free_item = [
                                    "sku" => $fi_sku,
                                    "image" => $fi_obj->getImage(),
                                    "qty" => max($this->redemption, 1),
                                    "name" => $fi_obj->getName(),
                                    "price" => 0,
                                    "total" => 0,
                                    "cost" => 0,
                                    "vat_total" => 0,
                                    "promo" => 1,
                                ];
                            }
                        } else {
                            $this->error = "FI";
                        }
                        return ["valid" => $this->valid, "type" => $disc_type, "error" => $this->error, "error_code" => $this->error_code, "free_item" => $this->free_item];
                    }
                    break;
                case "A":
                case "P":
                    $ar_disc_level_value = @explode(",", $this->promotion_code_obj->getDiscLevelValue());
                    switch ($this->promotion_code_obj->getDiscLevel()) {
                        case "PD":
                            $disc_pd_list = array_flip($ar_disc_level_value);
                            if ($int_prod_list = @array_intersect_key($disc_pd_list, $this->item_list)) {
                                $int_prod_list = array_flip($int_prod_list);
                            }
                            $total_amount = $this->getDiscLevelAmount($int_prod_list);
                            break;
                        case "CAT":
                            $total_amount = $this->getDiscLevelAmount($this->cat_list[$ar_disc_level_value[0]]);
                            break;
                        case "SCAT":
                            $total_amount = $this->getDiscLevelAmount($this->sub_cat_list[$ar_disc_level_value[1]]);
                            break;
                        case "SSCAT":
                            $total_amount = $this->getDiscLevelAmount($this->sub_sub_cat_list[$ar_disc_level_value[2]]);
                            break;
                        case "BN":
                            $total_amount = $this->getDiscLevelAmount($this->brand_list[$ar_disc_level_value[0]]);
                            break;
                        default:
                            $total_amount = $this->amount;
                            break;
                    }
                    for ($i = 1; $i < 6; $i++) {
                        $func = "getOverAmount" . $i;
                        $disc_func = "getDiscount" . $i;
                        $disc_value = $this->promotion_code_obj->$func();
                        $discount = $this->promotion_code_obj->$disc_func();

                        if ($discount) {
                            if ($this->amount > $disc_value) {
                                $this->discount = $discount;
                                $this->disc_amount = $disc_type == "A" ? $this->discount : (number_format($total_amount * $this->discount / 100, 2, '.', ''));
                            }
                        }
                    }
                    return [
                        "valid" => $this->valid,
                        "type" => $disc_type,
                        "discount" => $this->discount,
                        "disc_amount" => $this->disc_amount,
                    ];
                    break;
            }
        }
        return false;
    }

    protected function validItemList()
    {
        if ($this->valid) {
            if ($this->item_list) {
                foreach ($this->item_list as $sku => $qty) {
                    if ($prod_obj = $this->getDao('Product')->getProductWithPrice($sku, $this->platform_id)) {
                        $this->brand_list[$prod_obj->getBrandId()][] = $sku;
                        $this->cat_list[$prod_obj->getCatId()][] = $sku;
                        $this->sub_cat_list[$prod_obj->getSubCatId()][] = $sku;
                        $this->sub_sub_cat_list[$prod_obj->getSubSubCatId()][] = $sku;
                        $this->price_list[$sku] = $prod_obj->getPrice() * 1;
                    }
                }
            } else {
                $this->valid = 0;
            }
        }
    }

    protected function validRelevantProd()
    {
        if ($this->valid && ($relevant_prod = $this->promotion_code_obj->getRelevantProd())) {
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

    protected function validBrandId()
    {
        if ($this->valid && ($brand_id = $this->promotion_code_obj->getBrandId())) {
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

    protected function validSubSubCatId()
    {
        if ($this->valid && ($sub_sub_cat_id = $this->promotion_code_obj->getSubSubCatId())) {
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

    protected function validSubCatId()
    {
        if ($this->valid && ($sub_cat_id = $this->promotion_code_obj->getSubCatId())) {
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

    protected function validCatId()
    {
        if ($this->valid && ($cat_id = $this->promotion_code_obj->getCatId())) {
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

    protected function validCurrencyId()
    {
        if ($this->valid && ($currency_id = $this->promotion_code_obj->getCurrencyId())) {
            if ($platform_obj = $this->getDao('PlatformBizVar')->get(["selling_platform_id" => $this->platform_id])) {
                $this->currency_id = $platform_obj->getPlatformCurrencyId();
                if ($currency_id != $this->currency_id) {
                    $this->valid = 0;
                }
            } else {
                $this->valid = 0;
            }
        }
    }

    protected function validCountryId()
    {
        if ($this->valid && ($country_id = $this->promotion_code_obj->getCountryId()) && $this->country_id) {
            if ($country_id != $this->country_id) {
                $this->valid = 0;
            }
        }
    }

    protected function validEmail()
    {
        if ($this->valid && $this->email && ($chk_email = $this->promotion_code_obj->getEmail()) && !preg_match('/^' . str_replace('%', '.*', $chk_email) . '$/', trim($this->email))) {
            $this->valid = 0;
        }
    }

    protected function validRedemption()
    {
        if ($this->valid && ($redemption = $this->promotion_code_obj->getRedemption()) != -1) {
            if ($this->redemption > $redemption || $this->redemption == -1) {
                $this->redemption = $redemption;
                if ($this->redemption == 0) {
                    $this->valid = 0;
                }
            }
        }
    }

    protected function validTotalRedemption()
    {
        if ($this->valid && (($total_redemption = $this->promotion_code_obj->getTotalRedemption()) != -1)) {
            $total_redemption_left = $total_redemption - $this->promotion_code_obj->getNoTaken();
            if (0 >= $total_redemption_left) {
                $this->valid = 0;
            }
        }
    }

    protected function validBizType()
    {
        $sp_obj = $this->getDao('SellingPlatform')->get(["selling_platform_id" => $this->platform_id]);

        if ($this->valid && $this->promotion_code_obj->getCode() == 'skypeRTX50' && $sp_obj->getType() == 'WEBSITE') {
            $this->valid = 0;
        }
    }

    protected function validOverAmount()
    {
        if ($this->valid && ($over_amount = $this->promotion_code_obj->getOverAmount())) {
            if ($this->amount <= $over_amount) {
                $this->valid = 0;
            }
        }
    }

    protected function validDeliveryMode()
    {
        if ($this->valid && ($disc_level_value = $this->promotion_code_obj->getDiscLevelValue()) != "All") {
            if ($disc_level_value != $this->getDeliveryMode() && $disc_level_value == $this->getDefaultDelivery()) {
                $this->valid = 0;
                $this->error = "FD";
                $this->error_code = $disc_level_value;
            }
        }
    }

    public function getDeliveryMode()
    {
        return $this->delivery_mode;
    }

    public function setDeliveryMode($value)
    {
        $this->delivery_mode = $value;
    }

    public function getDefaultDelivery()
    {
        return $this->default_delivery;
    }

    public function setDefaultDelivery($value)
    {
        $this->default_delivery = $value;
    }

    protected function getDiscLevelAmount($ar_list)
    {
        $total_amount = 0;
        $ar_data = $ar_sku = $ar_price = [];

        if ($ar_list) {
            foreach ($ar_list as $sku) {
                $ar_data[] = ["price" => $this->price_list[$sku], "sku" => $sku];
                $ar_price[] = $this->price_list[$sku];
            }
            array_multisort($ar_price, SORT_ASC, $ar_data);
            $remain_qty = $this->redemption;
            foreach ($ar_data as $data) {
                $price = $data["price"];
                $sku = $data["sku"];
                $qty = $this->item_list[$sku];

                if ($this->promotion_code_obj->getRedemption() != -1) {
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
        $this->getPromotionCodeObj();
        $this->validItemList();
        $this->validRelevantProd();
        $this->validBrandId();
        $this->validSubSubCatId();
        $this->validSubCatId();
        $this->validCatId();
        $this->validCurrencyId();
        $this->validCountryId();
        //$this->validRegionId();
        $this->validEmail();
        $this->validRedemption();
        $this->validTotalRedemption();

        // temporary hardcode to remove promo code 'skypeRTX50' from website product
        $this->validBizType();

        if ($this->valid || $skip_valid) {
            $disc_type = $this->promotion_code_obj->getDiscType();
            switch ($disc_type) {
                case "FD":
                    $this->validOverAmount();
                    $this->validDeliveryMode();
                    if ($this->valid) {
                        return ["valid" => $this->valid, "free_delivery" => 1, "promotion_code_obj" => $this->promotion_code_obj];
                    }
                    break;
                case "FI":
                    $this->validOverAmount();
                    if ($this->valid) {
                        $fi_sku = $this->promotion_code_obj->getFreeItemSku();
                        if ($fi_obj = $this->getDao('Product')->get(["sku" => $fi_sku])) {
                            if ($fi_obj->getWebsiteStatus() == "O" || $fi_obj->getStatus() == "0" || $fi_obj->getWebsiteQuantity() == 0) {
                                $this->error = "FI";
                            } else {
                                $this->free_item = [
                                    "sku" => $fi_sku,
                                    "image" => $fi_obj->getImage(),
                                    "qty" => max($this->redemption, 1),
                                    "name" => $fi_obj->getName(),
                                    "price" => 0,
                                    "total" => 0,
                                    "cost" => 0,
                                    "vat_total" => 0,
                                    "promo" => 1,
                                ];
                            }
                        } else {
                            $this->error = "FI";
                        }
                        return [
                            "valid"=>$this->valid,
                            "error"=>$this->error,
                            "error_code"=>$this->error_code,
                            "free_item"=>$this->free_item,
                            "promotion_code_obj"=>$this->promotion_code_obj,
                        ];
                    }
                    break;
                case "A":
                case "P":
                    $ar_disc_level_value = @explode(",", $this->promotion_code_obj->getDiscLevelValue());
                    switch ($this->promotion_code_obj->getDiscLevel()) {
                        case "PD":
                            $disc_pd_list = array_flip($ar_disc_level_value);
                            if ($int_prod_list = @array_intersect_key($disc_pd_list, $this->item_list)) {
                                $int_prod_list = array_flip($int_prod_list);
                            }
                            $total_amount = $this->getDiscLevelAmount($int_prod_list);
                            break;
                        case "CAT":
                            $total_amount = $this->getDiscLevelAmount($this->cat_list[$ar_disc_level_value[0]]);
                            break;
                        case "SCAT":
                            $total_amount = $this->getDiscLevelAmount($this->sub_cat_list[$ar_disc_level_value[1]]);
                            break;
                        case "SSCAT":
                            $total_amount = $this->getDiscLevelAmount($this->sub_sub_cat_list[$ar_disc_level_value[2]]);
                            break;
                        case "BN":
                            $total_amount = $this->getDiscLevelAmount($this->brand_list[$ar_disc_level_value[0]]);
                            break;
                        default:
                            $total_amount = $this->amount;
                            break;
                    }

                    if ($total_amount > 0) {
                        for ($i = 1; $i < 6; $i++) {
                            $func = "getOverAmount" . $i;
                            $disc_func = "getDiscount" . $i;
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
                    return [
                        "valid" => $this->valid,
                        "discount" => $this->discount,
                        "disc_amount" => $this->disc_amount,
                        "promotion_code_obj" => $this->promotion_code_obj,
                        "disc_item_list" => $this->disc_item_list
                    ];
                    break;
            }
        }
        return ["valid" => $this->valid, "error" => $this->error, "error_code" => $this->error_code];
    }

    protected function getPromotionCodeObj()
    {
        $cur_date = date("Y-m-d");
        $option = ["limit" => 1, "orderby" => "create_on DESC"];

        if (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $this->promo_code)) {
            $email = mysql_real_escape_string($this->promo_code);
            $where_str = "(code='" . $email . "' OR '" . $email . "' LIKE email)";
            $where[$where_str] = NULL;
        } else {
            $where["code"] = $this->promo_code;
        }
        $where["status"] = 1;
        $where["(ISNULL(expire_date) OR expire_date >= '" . $cur_date . "')"] = null;
        if (!is_null($this->promo_code) && ($promotion_code_obj = $this->getDao('PromotionCode')->getList($where, $option))) {
            $this->promotion_code_obj = $promotion_code_obj;
        } else {
            $this->valid = 0;
        }
    }

    public function check_del_country()
    {
        $this->getPromotionCodeObj();
        $this->validCountryId();
        return $this->valid;
    }

    public function check_email()
    {
        $this->validEmail();
        return $this->valid;
    }

    public function update_no_taken()
    {
        $this->getPromotionCodeObj();
        if ($this->promotion_code_obj) {
            $this->promotion_code_obj->setNoTaken($this->promotion_code_obj->getNoTaken() + 1);
            $this->update($this->promotion_code_obj);
        }
    }

    protected function validRegionId()
    {
        if ($this->valid && !$this->promotion_code_obj->getCountryId() && ($region_id = $this->promotion_code_obj->getRegionId()) && $this->country_id) {
            if (!$this->gatDao('RegionCountry')->getNumRows(["region_id" => $region_id, "country_id" => $this->country_id])) {
                $this->valid = 0;
            }
        }
    }

}
