<?php
class Cron_data_feed extends MY_Controller
{
	private $app_id="CRN0009";
	function __construct()
	{
		parent::__construct();
		$this->load->model('marketing/data_feed_model');
	}

	public function gen_reevoo_product_feed($country_id = NULL)
	{
		$this->data_feed_model->gen_reevoo_product_feed($country_id);
	}

	public function gen_reevoo_customer_feed()
	{
		$this->data_feed_model->gen_reevoo_customer_feed();
	}

	public function gen_googlebase_product_feed($platform_id)
	{
		$this->data_feed_model->gen_googlebase_product_feed($platform_id);
	}

	public function gen_mediaforge_product_feed($platform_id="WEBFR", $explain_sku="")
	{
		$this->data_feed_model->gen_mediaforge_product_feed($platform_id, $explain_sku);
	}

	public function gen_linkshare_product_feed($platform_id="WEBGB")
	{
		$this->data_feed_model->gen_linkshare_product_feed($platform_id);
	}

	public function gen_shopping_com_product_feed()
	{
		$this->data_feed_model->gen_shopping_com_product_feed();
	}

	public function gen_sli_product_feed($lang_id = 'en')
	{
		$where = array("pbv.language_id"=>$lang_id, "pbv.platform_country_id != 'GB'"=>NULL);
		$this->data_feed_model->gen_sli_product_feed($where, $option, $lang_id);
	}

	public function gen_searchspring_product_feed($platform_id='WEBGB')
	{
		$this->data_feed_model->gen_searchspring_product_feed(strtoupper($platform_id));
	}

	public function gen_shopbot_product_feed($platform_id = "WEBAU")
	{
		$this->data_feed_model->gen_shopbot_product_feed($platform_id);
	}

	public function gen_my_shopping_com_au_product_feed()
	{
		$this->data_feed_model->gen_my_shopping_com_au_product_feed();
	}

	public function gen_shop_deal_compare_com_au_product_feed()
	{
		$this->data_feed_model->gen_shop_deal_compare_com_au_product_feed();
	}

	public function gen_shopprice_product_feed($platform_id)
	{
		$platform_ids = explode(',', $platform_id);
		foreach ($platform_ids as $platform_id) {
			$this->data_feed_model->gen_shopprice_product_feed($platform_id);
		}
	}

	public function gen_get_price_product_feed()
	{
		$this->data_feed_model->gen_get_price_product_feed();
	}

	public function gen_criteo_product_feed()
	{
		$this->data_feed_model->gen_criteo_product_feed();
	}

	public function gen_graysonline_product_feed()
	{
		$this->data_feed_model->gen_graysonline_product_feed();
	}

	public function download_graysonline_csv()
	{
		$this->data_feed_model->download_graysonline_csv();
	}

	public function gen_kelkoo_product_feed($country = "FR", $explain_sku = "")
	{
		$this->data_feed_model->gen_kelkoo_product_feed($country, $explain_sku);
	}

	public function gen_yandex_product_feed($platform_id = "WEBRU", $explain_sku = "")
	{
		$this->data_feed_model->gen_yandex_product_feed($platform_id, $explain_sku);
	}

	public function gen_ceneo_product_feed($platform_id = "WEBPL", $explain_sku = "")
	{
		$this->data_feed_model->gen_ceneo_product_feed($platform_id, $explain_sku);
	}

	public function gen_skapiec_product_feed($platform_id = "WEBPL", $explain_sku = "")
	{
		$this->data_feed_model->gen_skapiec_product_feed($platform_id, $explain_sku);
	}

	public function gen_dhl_shipment_tracking_feed()
	{
		$this->data_feed_model->gen_dhl_shipment_tracking_feed();
	}

	public function gen_shoppydoo_product_feed($country = "ES", $explain_sku = "")
	{
		$this->data_feed_model->gen_shoppydoo_product_feed($country, $explain_sku);
	}

	public function gen_shopall_product_feed($platform_id = "WEBES", $explain_sku = "")
	{
		$this->data_feed_model->gen_shopall_product_feed($platform_id, $explain_sku);
	}

	public function gen_nextag_product_feed($platform_id = "WEBES", $explain_sku = "")
	{
		$this->data_feed_model->gen_nextag_product_feed($platform_id, $explain_sku);
	}

	public function gen_comparer_product_feed($country = "FR", $explain_sku = "")
	{
		$this->data_feed_model->gen_comparer_product_feed($country, $explain_sku);
	}

	public function gen_omg_product_feed($country = "SG")
	{
		$this->data_feed_model->gen_omg_product_feed($country);
	}

	public function gen_pricespy_product_feed($country = "NZ", $explain_sku = "")
	{
		$this->data_feed_model->gen_pricespy_product_feed($country, $explain_sku);
	}

	public function gen_tradedoubler_product_feed($country = "FR", $explain_sku = "")
	{
		$this->data_feed_model->gen_tradedoubler_product_feed($country, $explain_sku);
	}

	public function gen_tradetracker_product_feed($platform_id = "WEBBE", $explain_sku = "")
	{
		$this->data_feed_model->gen_tradetracker_product_feed($platform_id, $explain_sku);
	}

	public function gen_priceme_product_feed($platform_id = "WEBHK", $explain_sku = "", $gen_csv = 0)
	{
		$this->data_feed_model->gen_priceme_product_feed($platform_id, $gen_csv, $explain_sku);
	}

	public function gen_admin_product_feed($platform_type = NULL)
	{
		$this->data_feed_model->gen_admin_product_feed($platform_type);
	}

	public function gen_prismastar_product_feed($platform_id)
	{
		$this->data_feed_model->gen_prismastar_product_feed($platform_id);
	}

	public function gen_shopping_com_fr_product_feed()
	{
		$this->data_feed_model->gen_shopping_com_fr_product_feed();
	}

	public function gen_shopzilla_fr_product_feed()
	{
		$this->data_feed_model->gen_shopzilla_fr_product_feed();
	}

	public function gen_touslesprix_fr_product_feed()
	{
		$this->data_feed_model->gen_touslesprix_fr_product_feed();
	}

	public function gen_standard_fr_product_feed()
	{
		$this->data_feed_model->gen_standard_fr_product_feed();
	}

	public function gen_price_panda_product_feed($platform_id = "WEBSG")
	{
		$this->data_feed_model->gen_price_panda_product_feed($platform_id);
	}

	public function gen_oizmy_product_feed($platform_id = "WEBMY")
	{
		$this->data_feed_model->gen_oizmy_product_feed($platform_id);
	}

	public function gen_tag_product_feed($platform_id = "WEBSG")
	{
		$this->data_feed_model->gen_tag_product_feed($platform_id);
	}

	public function download_supplier_status_csv()
	{
		$this->data_feed_model->download_supplier_status_csv();
	}

	public function download_supplier_cost_csv($platform_id='')
	{
		$this->data_feed_model->download_supplier_cost_csv($platform_id);
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}
}

/* End of file cron_data_feed.php */
/* Location: ./app/controllers/cron_data_feed.php */
