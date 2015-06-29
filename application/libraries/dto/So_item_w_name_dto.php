<?php
include_once 'Base_dto.php';

class So_item_w_name_dto extends Base_dto
{

    //class variable
    private $so_no;
    private $line_no;
    private $prod_sku;
    private $name;
    private $cat_name;
    private $main_prod_sku;
    private $main_img_ext;
    private $qty;
    private $unit_price;
    private $vat_total;
    private $gst_total;
    private $amount;
    private $website_status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $warranty_in_month;

    //instance method
    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_line_no()
    {
        return $this->line_no;
    }

    public function set_line_no($value)
    {
        $this->line_no = $value;
        return $this;
    }

    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
        return $this;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
        return $this;
    }

    public function get_unit_price()
    {
        return $this->unit_price;
    }

    public function set_unit_price($value)
    {
        $this->unit_price = $value;
        return $this;
    }

    public function get_vat_total()
    {
        return $this->vat_total;
    }

    public function set_vat_total($value)
    {
        $this->vat_total = $value;
        return $this;
    }

    public function get_gst_total()
    {
        return $this->gst_total;
    }

    public function set_gst_total($value)
    {
        $this->gst_total = $value;
        return $this;
    }

    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($value)
    {
        $this->amount = $value;
        return $this;
    }

    public function get_website_status()
    {
        return $this->website_status;
    }

    public function set_website_status($value)
    {
        $this->website_status = $value;
        return $this;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
        return $this;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
        return $this;
    }

    public function get_main_prod_sku()
    {
        return $this->main_prod_sku;
    }

    public function set_main_prod_sku($value)
    {
        $this->main_prod_sku = $value;
        return $this;
    }

    public function get_main_img_ext()
    {
        return $this->main_img_ext;
    }

    public function set_main_img_ext($value)
    {
        $this->main_img_ext = $value;
        return $this;
    }

    public function get_item_weight()
    {
        return $this->item_weight;
    }

    public function set_item_weight($value)
    {
        $this->item_weight = $value;
        return $this;
    }

    public function get_warranty_in_month()
    {
        return $this->warranty_in_month;
    }

    public function set_warranty_in_month($value)
    {
        $this->warranty_in_month = $value;
        return $this;
    }

}

?>