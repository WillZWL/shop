<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\SellingPlatformService;
// use ESG\Panther\Service\PriceWebsiteService;

class ClassFactoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . 'helpers/string_helper.php');
    }

    public function get_price_service($method = '', $set = [])
    {
        /*
            array setting:  supplier_fc - Supplier Fulfillment Centre (Warehouse id)
                            customer_fc - Customer fulfillment Centre (Warehouse id)
                            supplier_region - Supplier Region (Courier Region)
                            ccountry - Client Country (region id)

        */

        $p_srv_name = ucwords(strtolower($method)) . "_def_price_service";

        $p_srv_path = APPPATH . "libraries/service/" . $p_srv_name . ".php";

        if (!is_file($p_srv_path)) {
            return FALSE;
        }

        include_once($p_srv_path);
        $p_class_name = ucwords($p_srv_name);

        $svc = new $p_class_name($set["supplier_region"], $set["supplier_fc"], $set["customer_fc"], $set["ccountry"], $set["weight_cat"], $set["price"]);

        return $svc;
    }

    public function get_platform_price_service($platform_id)
    {
        $this->sellingPlatformService = new SellingPlatformService;

        if ($sp_obj = $this->sellingPlatformService->getDao()->get(["id" => $platform_id])) {
            $paltform_type = $sp_obj->get_type();

            $p_srv_name = "\ESG\Panther\Service\Price" . ucwords(strtolower($paltform_type)) . "Service";

            $this->svc = new $p_srv_name;

            return $this->svc;
        }
    }

}

