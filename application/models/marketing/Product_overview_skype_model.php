<?php
include_once "product_overview_model.php";

class Product_overview_skype_model extends Product_overview_model
{

	public function __construct()
	{
		parent::Product_overview_model("SKYPE", 'marketing/pricing_tool');
	}

}

/* End of file product_overview_skype_model.php */
/* Location: ./system/application/models/product_overview_skype_model.php */
