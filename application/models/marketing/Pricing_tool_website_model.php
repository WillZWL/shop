<?php
include_once "Pricing_tool_model.php";

class Pricing_tool_website_model extends Pricing_tool_model
{

    public function __construct()
    {
        parent::Pricing_tool_model("WEBSITE");
    }

    public function get_rrp_factor_by_sku($sku)
    {
        return $this->price_service->get_rrp_factor_by_sku($sku);
    }

    public function update_rrp_factor()
    {
        return $this->price_service->update_rrp_factor();
    }
}
/* End of file pricing_tool_website_model.php */
/* Location: ./system/application/models/pricing_tool_website_model.php */
