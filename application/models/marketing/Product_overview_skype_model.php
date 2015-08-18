<?php
include_once "product_overview_model.php";

class Product_overview_skype_model extends Product_overview_model
{

    public function __construct()
    {
        parent::Product_overview_model("SKYPE", 'marketing/pricing_tool');
    }

}
