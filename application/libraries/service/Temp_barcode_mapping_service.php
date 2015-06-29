<?php

include_once "Base_service.php";

class Temp_barcode_mapping_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Temp_barcode_mapping_dao.php");
        $this->set_dao(new Temp_barcode_mapping_dao());
    }

    public function get_barcode($sku, $country_id)
    {
        return $this->get_dao()->get_barcode($sku, $country_id);
    }
}

/* End of file temp_barcode_mapping.php */
/* Location: ./app/libraries/service/Temp_barcode_mapping.php */