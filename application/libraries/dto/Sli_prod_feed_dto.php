<?php

include_once "Base_dto.php";

class Sli_prod_feed_dto extends Base_dto
{
	private $sku;
	private $manufacturer_name;
	private $prod_name;
	private $short_desc;
	private $category;
	private $website_status;
	private $availability_url;
	private $status = array("I"=>"In Stock","O"=>"Out Of Stock","P"=>"Pre-order","A"=>"In-stock with Supplier");
	private $status_img = array("I"=>"images/instock6.gif","O"=>"images/outofstock6.gif","P"=>"images/preorder6.gif","A"=>"images/1-3days6.gif");
	private $retail_price;
	private $priceGBP;
	private $link;
	private $image;
	private $image_link;
	private $small_image_url;
	private $medium_image_url;
	private $normal_image_url;
	private $mpn;
	private $ean;
	private $brand_name;
	private $platform_currency_id;
	private $keywords;
	private $delivery_charge;
	private $detail_desc;
	private $priceEUR;
	private $website_quantity;
	private $cfg;

	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		$this->load = $CI->load;
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->cfg = new Context_config_service();
		$this->load->helper(array('url','image','string'));
	}

	public function get_mpn()
	{
		return $this->mpn;
	}

	public function set_mpn($value)
	{
		$this->mpn = $value;
	}

	public function get_keywords()
	{
		return str_replace(",","|",$this->keywords);
	}

	public function set_keywords($value)
	{
		$this->keywords = $value;
	}

	public function get_platform_currency_id()
	{
		return $this->platform_currency_id;
	}

	public function set_platform_currency_id($value)
	{
		$this->platform_currency_id = $value;
	}

	public function get_ean()
	{
		return $this->ean;
	}

	public function set_ean($value)
	{
		$this->ean = $value;
	}

	public function get_availability_url()
	{
		$tmp = $this->status_img;
		$s = $this->website_status;
		if($this->get_website_quantity() == 0)
		{
			$s = 'O';
		}
		return $this->cfg->value_of('website_domain').$tmp[$s];
	}

	public function set_availability_url($value)
	{
		$this->availability_url = $value;
	}

	public function set_image_link($value)
	{
		$this->image_link = $value;
	}

	public function get_image_link()
	{
		return rtrim($this->cfg->value_of('website_domain'),"/").get_image_file($this->image,"l",$this->sku);
	}

	public function set_small_image_url($value)
	{
		$this->small_image_url = $value;
	}

	public function get_small_image_url()
	{
		return rtrim($this->cfg->value_of('website_domain'),"/").get_image_file($this->image,"s",$this->sku);
	}

	public function set_medium_image_url($value)
	{
		$this->medium_image_url = $value;
	}

	public function get_medium_image_url()
	{
		return rtrim($this->cfg->value_of('website_domain'),"/").get_image_file($this->image,"m",$this->sku);
	}

	public function set_normal_image_url($value)
	{
		$this->normal_image_url = $value;
	}

	public function get_normal_image_url()
	{
		return rtrim($this->cfg->value_of('website_domain'),"/").get_image_file($this->image,"l",$this->sku);
	}

	public function get_image()
	{
		return $this->image;
	}

	public function set_image($value)
	{
		$this->image = $value;
	}

	public function get_link()
	{
		return $this->cfg->value_of('website_domain').urlencode(str_replace(' ',"-",parse_url_char($this->prod_name)))."/mainproduct/view/".$this->sku;
		//return $this->link;
	}

	public function set_link($value)
	{
		$this->link = $value;
	}

	public function get_sub_subcat_name()
	{
		return $this->sub_subcat_name;
	}

	public function set_sub_subcat_name($value)
	{
		$this->sub_subcat_name = $value;
	}

	public function get_subcat_name()
	{
		return $this->subcat_name;
	}

	public function set_subcat_name($value)
	{
		$this->subcat_name = $value;
	}

	public function get_cat_name()
	{
		return $this->cat_name;
	}

	public function set_cat_name($value)
	{
		$this->cat_name = $value;
	}

	public function get_website_status()
	{
		$tmp = $this->status;
		$s = $this->website_status;
		if($this->get_website_quantity() == 0)
		{

			$s = 'O';
		}
		return $tmp[$s];
		//return $this->website_status;
	}

	public function set_website_status($value)
	{
		$this->website_status = $value;
	}

	public function get_website_quantity()
	{
		return $this->website_quantity;
	}

	public function set_website_quantity($value)
	{
		$this->website_quantity = $value;
	}

	public function get_retail_price()
	{
		return $this->retail_price;
	}

	public function set_retail_price($value)
	{
		$this->retail_price = $value;
	}

	public function get_priceGBP()
	{
		return $this->priceGBP;
	}

	public function set_priceGBP($value)
	{
		$this->priceGBP = $value;
	}

	public function get_priceEUR()
	{
		return $this->priceEUR;
	}

	public function set_priceEUR($value)
	{
		$this->priceEUR = $value;
	}

	public function get_detail_desc()
	{
		$result = $this->detail_desc;
		$result = str_replace (array("'",",","|"),"",nl2br($this->detail_desc));
		$result = str_replace (array("<br>","<br >","<BR >","<BR />","<br />","\r\n","\n","\t"),"-",$result);
		$result = str_replace (array("<b>","</b>","<B>","</B>"),"",$result);
		$result = str_replace (array(",","'","|"),"",$result);
		$result = strip_tags($result);
		$result = strip_invalid_xml($result);
		if(strlen($result) > 1900)
		{
			$result = cutstr($result,1896,"....");
		}
		return $result;
	}

	public function set_detail_desc($value)
	{
		$this->detail_desc = $value;
	}

	public function get_short_desc()
	{
		$result = $this->short_desc;
		$result = str_replace (array("'",",","|"),"",nl2br($this->short_desc));
		$result = str_replace (array("<br>","<br >","<BR >","<BR />","<br />","\r\n","\n","\t"),"-",$result);
		$result = str_replace (array("<b>","</b>","<B>","</B>"),"",$result);
		$result = str_replace (array(",","'","|"),"",$result);
		$result = strip_tags($result);
		$result = strip_invalid_xml($result);
		if(strlen($result) > 200)
		{
			$result = cutstr($result,196,"....");
		}
		return $result;
	}

	public function set_short_desc($value)
	{
		$this->short_desc = $value;
	}

	public function get_prod_name()
	{
		return $this->prod_name;
	}

	public function set_prod_name($value)
	{
		$this->prod_name = $value;
	}

	public function get_category()
	{
		return $this->category;
	}

	public function set_category($value)
	{
		$this->category = $value;
	}

	public function get_brand_name()
	{
		return $this->brand_name;
	}

	public function set_brand_name($value)
	{
		$this->brand_name = $value;
	}

	public function get_manufacturer_name()
	{
		return $this->manufacturer_name;
	}

	public function set_manufacturer_name($value)
	{
		$this->manufacturer_name = $value;
	}

	public function get_delivery_charge()
	{
		return $this->delivery_charge;
	}

	public function set_delivery_charge($value)
	{
		$this->delivery_charge = $value;
	}

	public function get_sku()
	{
		return $this->sku;
	}

	public function set_sku($value)
	{
		$this->sku = $value;
	}

}