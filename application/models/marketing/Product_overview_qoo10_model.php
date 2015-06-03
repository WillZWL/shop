<?php
include_once "product_overview_model.php";

class Product_overview_qoo10_model extends Product_overview_model
{

	public function __construct()
	{
		parent::Product_overview_model("QOO10", 'marketing/pricing_tool_website');
	}


}

/* End of file product_overview_qoo10_model.php */
/* Location: ./system/application/models/product_overview_qoo10_model.php */
