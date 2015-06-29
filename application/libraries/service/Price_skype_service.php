<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Price_service.php";

class Price_skype_service extends Price_service
{
    public function __construct()
    {
        parent::__construct();
        $this->set_tool_path('marketing/pricing_tool');
    }

    // temporary change Feature #338 GE phone special commission
    public function init_dto(&$dto)
    {
        if (is_null($dto))
        {
            $dto = $this->get_dto();
        }
        else
        {
            $this->set_dto($dto);
        }

        if($dto->get_sku() == "10429-US-NA" || $dto->get_sku() == "10429-EU-NA" || $dto->get_sku() == "10429-UK-NA")
        {
            $dto->set_platform_commission("8.00");
        }
    }

}
/* End of file price_skype_service.php */
/* Location: ./system/application/libraries/service/Price_skype_service.php */
