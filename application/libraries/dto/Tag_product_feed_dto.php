<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

// #table name
// data

// #Input column name   Output column name

// sku  ProductCode
// sku  ProductNumber
// prod_name    ProductName
// detail_desc  Description
// cat_id   ProductCategoryCode
// cat_name ProductCategoryName
// product_url  ProductURL
// image_url    PictureThumbnailURL


class Tag_product_feed_dto extends Base_dto
{
    private $platform_id;
    private $sku;
    private $prod_name;
    private $cat_id;
    private $cat_name;
    private $product_url;
    private $image_url;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }
    public function get_sku()
    {
        return $this->sku;
    }
    public function get_prod_name()
    {
        return $this->prod_name;
    }
    public function get_detail_desc()
    {
        return $this->detail_desc;
    }
    public function get_cat_id()
    {
        return $this->cat_id;
    }
    public function get_cat_name()
    {
        return $this->cat_name;
    }
    public function get_product_url()
    {
        return $this->product_url;
    }
    public function get_image_url()
    {
        return $this->image_url;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }
    public function set_sku($value)
    {
        $this->sku = $value;
    }
    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }
    public function set_detail_desc($value)
    {
        $this->detail_desc = $value;
    }
    public function set_cat_id($value)
    {
        $this->cat_id = $value;
    }
    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }
    public function set_product_url($value)
    {
        $this->product_url = $value;
    }
    public function set_image_url($value)
    {
        $this->image_url = $value;
    }

}
/* End of file price_panda_product_feed_dto.php */
/* Location: ./system/application/libraries/dto/price_panda_product_feed_dto.php */