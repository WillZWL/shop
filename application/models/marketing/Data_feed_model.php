<?php
class Data_feed_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/reevoo_product_feed_service');
		$this->load->library('service/reevoo_customer_feed_service');
		$this->load->library('service/googlebase_product_feed_service');
		$this->load->library('service/linkshare_product_feed_service');
		$this->load->library('service/mediaforge_product_feed_service');
		$this->load->library('service/shopping_com_product_feed_service');
		$this->load->library('service/sli_product_feed_service');
		$this->load->library('service/searchspring_product_feed_service');
		$this->load->library('service/shopbot_product_feed_service');
		$this->load->library('service/shopbot_nz_product_feed_service');
		$this->load->library('service/shopbot_my_product_feed_service');
		$this->load->library('service/shopbot_sg_product_feed_service');
		$this->load->library('service/my_shopping_com_au_product_feed_service');
		$this->load->library('service/shop_deal_compare_com_au_product_feed_service');
		$this->load->library('service/get_price_product_feed_service');
		$this->load->library('service/criteo_product_feed_service');
		$this->load->library('service/graysonline_product_feed_service');
		$this->load->library('service/comparer_product_feed_service');
		$this->load->library('service/kelkoo_product_feed_service');
		$this->load->library('service/yandex_product_feed_service');
		$this->load->library('service/ceneo_product_feed_service');
		$this->load->library('service/skapiec_product_feed_service');
		$this->load->library('service/shoppydoo_product_feed_service');
		$this->load->library('service/shopall_product_feed_service');
		$this->load->library('service/nextag_product_feed_service');
		$this->load->library('service/omg_product_feed_service');
		$this->load->library('service/pricespy_product_feed_service');
		$this->load->library('service/tradedoubler_product_feed_service');
		$this->load->library('service/tradetracker_product_feed_service');
		$this->load->library('service/priceme_product_feed_service');
		$this->load->library('service/admin_product_feed_service');
		$this->load->library('service/prismastar_product_feed_service');
		$this->load->library('service/shopping_com_fr_product_feed_service');
		$this->load->library('service/standard_fr_product_feed_service');
		$this->load->library('service/shopzilla_fr_product_feed_service');
		$this->load->library('service/touslesprix_fr_product_feed_service');
		$this->load->library('service/price_panda_product_feed_service');
		$this->load->library('service/oizmy_product_feed_service');
		$this->load->library('service/tag_product_feed_service');
		$this->load->library('service/supplier_service');
		$this->load->library('service/dhl_shipment_tracking_feed_service');
		$this->load->library('service/shopprice_product_feed_service');
	}

	public function gen_reevoo_product_feed($country_id = NULL)
	{
		$this->reevoo_product_feed_service->gen_data_feed($country_id);
	}

	public function gen_reevoo_customer_feed()
	{
		$this->reevoo_customer_feed_service->gen_data_feed();
	}

	public function gen_googlebase_product_feed($platform_id)
	{
		$this->googlebase_product_feed_service->gen_data_feed($platform_id);
	}

	public function gen_mediaforge_product_feed($platform_id, $explain_sku)
	{
		$this->mediaforge_product_feed_service->gen_data_feed($platform_id, $explain_sku);
	}

	public function gen_linkshare_product_feed($platform_id)
	{
		$this->linkshare_product_feed_service->gen_data_feed($platform_id);
	}

	public function gen_shopping_com_product_feed()
	{
		$this->shopping_com_product_feed_service->gen_data_feed();
	}

	public function gen_sli_product_feed($where, $option, $lang_id)
	{
		$this->sli_product_feed_service->gen_data_feed($where, $option, $lang_id);
	}

	public function gen_searchspring_product_feed($platform_id)
	{
		$this->searchspring_product_feed_service->gen_data_feed($platform_id);
	}

	public function gen_shopbot_product_feed($platform_id = "WEBAU")
	{
		switch($platform_id)
		{
			case "WEBAU":
				$this->shopbot_product_feed_service->gen_data_feed();
				break;
			case "WEBNZ":
				$this->shopbot_nz_product_feed_service->gen_data_feed();
				break;
			case "WEBMY":
				$this->shopbot_my_product_feed_service->gen_data_feed();
				break;
			case "WEBSG":
				$this->shopbot_sg_product_feed_service->gen_data_feed();
				break;
			default:
				return false;
		}
	}

	public function gen_my_shopping_com_au_product_feed()
	{
		$this->my_shopping_com_au_product_feed_service->gen_data_feed();
	}

	public function gen_shop_deal_compare_com_au_product_feed()
	{
		$this->shop_deal_compare_com_au_product_feed_service->gen_data_feed();
	}

	public function gen_shopprice_product_feed($platform_id)
	{
		$this->shopprice_product_feed_service->gen_data_feed($platform_id);
	}

	public function gen_get_price_product_feed()
	{
		$this->get_price_product_feed_service->gen_data_feed();
	}

	public function gen_criteo_product_feed()
	{
		$this->criteo_product_feed_service->gen_data_feed();
	}

	public function gen_graysonline_product_feed()
	{
		$this->graysonline_product_feed_service->gen_data_feed();
	}

	public function download_graysonline_csv()
	{
		$this->graysonline_product_feed_service->download_csv();
	}

	public function gen_kelkoo_product_feed($country, $explain_sku)
	{
		$this->kelkoo_product_feed_service->gen_data_feed($country, $explain_sku);
	}

	public function gen_yandex_product_feed($platform_id, $explain_sku)
	{
		$this->yandex_product_feed_service->gen_data_feed($platform_id, $explain_sku);
	}

	public function gen_ceneo_product_feed($platform_id, $explain_sku)
	{
		$this->ceneo_product_feed_service->gen_data_feed($platform_id, $explain_sku);
	}

	public function gen_skapiec_product_feed($platform_id, $explain_sku)
	{
		$this->skapiec_product_feed_service->gen_data_feed($platform_id, $explain_sku);
	}

	public function gen_shoppydoo_product_feed($country, $explain_sku)
	{
		$this->shoppydoo_product_feed_service->gen_data_feed($country, $explain_sku);
	}

	public function gen_shopall_product_feed($platform_id, $explain_sku)
	{
		$this->shopall_product_feed_service->gen_data_feed($platform_id, $explain_sku);
	}

	public function gen_nextag_product_feed($platform_id, $explain_sku)
	{
		$this->nextag_product_feed_service->gen_data_feed($platform_id, $explain_sku);
	}


	public function gen_comparer_product_feed($country, $explain_sku)
	{
		$this->comparer_product_feed_service->gen_data_feed($country, $explain_sku);
	}

	public function gen_omg_product_feed($country)
	{
		$this->omg_product_feed_service->gen_data_feed($country);
	}

	public function gen_pricespy_product_feed($country, $explain_sku)
	{
		$this->pricespy_product_feed_service->gen_data_feed($country, $explain_sku);
	}

	public function gen_tradedoubler_product_feed($country, $explain_sku)
	{
		$this->tradedoubler_product_feed_service->gen_data_feed($country, $explain_sku);
	}

	public function gen_tradetracker_product_feed($platform_id, $explain_sku)
	{
		$this->tradetracker_product_feed_service->gen_data_feed($platform_id, $explain_sku);
	}

	public function gen_priceme_product_feed($platform_id = "WEBHK", $gen_csv = 0, $explain_sku)
	{
		$this->priceme_product_feed_service->gen_data_feed($platform_id, $gen_csv, $explain_sku);
	}

	public function gen_admin_product_feed($platform_type = NULL)
	{
		$this->admin_product_feed_service->gen_data_feed($platform_type);
	}

	public function gen_prismastar_product_feed($platform_id = "WEBGB")
	{
		$this->prismastar_product_feed_service->gen_data_feed($platform_id);
	}

	public function gen_shopping_com_fr_product_feed()
	{
		$this->shopping_com_fr_product_feed_service->gen_data_feed();
	}

	public function gen_shopzilla_fr_product_feed()
	{
		$this->shopzilla_fr_product_feed_service->gen_data_feed();
	}

	public function gen_touslesprix_fr_product_feed()
	{
		$this->touslesprix_fr_product_feed_service->gen_data_feed();
	}

	public function gen_standard_fr_product_feed()
	{
		$this->standard_fr_product_feed_service->gen_data_feed();
	}

	public function gen_price_panda_product_feed($platform_id = "WEBSG")
	{
		$this->price_panda_product_feed_service->gen_data_feed($platform_id);
	}

	public function gen_oizmy_product_feed($platform_id = "WEBMY")
	{
		$this->oizmy_product_feed_service->gen_data_feed($platform_id);
	}

	public function gen_tag_product_feed($platform_id = "WEBSG")
	{
		$this->tag_product_feed_service->gen_data_feed($platform_id);
	}

	public function download_supplier_status_csv()
	{
		$this->supplier_service->download_supplier_status_csv();
	}

	public function download_supplier_cost_csv($platform_id='')
	{
		$this->supplier_service->download_supplier_cost_csv($platform_id);
	}

	public function gen_dhl_shipment_tracking_feed()
	{
		$this->data_feed_model->dhl_shipment_tracking_feed_service->gen_dhl_shipment_tracking_feed();
	}
}
?>