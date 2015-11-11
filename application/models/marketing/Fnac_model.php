<?php

class Fnac_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/fnac_service');
    }

    public function cron_retrieve_new_order($country_id = "ES", $start_time = "", $end_time = "", $debug = 0, $enable_log = 0)
    {
        $this->fnac_service->set_debug($debug);
        $this->fnac_service->set_enable_log($enable_log);

        return $this->fnac_service->cron_retrieve_new_order($country_id, $start_time, $end_time);
    }

    public function cron_update_payment_status($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_service->set_debug($debug);
        $this->fnac_service->set_enable_log($enable_log);

        return $this->fnac_service->cron_update_payment_status($country_id);
    }

    public function acknowledge_order($country_id = "ES", $fnac_order_id_list = "")
    {
        return $this->fnac_service->acknowledge_order($country_id, $fnac_order_id_list);
    }

    public function cron_update_shipment_status($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_service->set_debug($debug);
        $this->fnac_service->set_enable_log($enable_log);

        return $this->fnac_service->cron_update_shipment_status($country_id);
    }

    public function cron_check_offer_update_status($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_service->set_debug($debug);
        $this->fnac_service->set_enable_log($enable_log);

        return $this->fnac_service->cron_check_offer_update_status($country_id);
    }

    public function get_fnac_offer_list($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_service->set_debug($debug);
        $this->fnac_service->set_enable_log($enable_log);

        return $this->fnac_service->get_fnac_offer_list($country_id, $debug, $enable_log);
    }

    public function sync_fnac_offer_list($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_service->set_debug($debug);
        $this->fnac_service->set_enable_log($enable_log);

        return $this->fnac_service->sync_fnac_offer_list($country_id, $debug, $enable_log);
    }

    public function get_fnac_orders_list($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_service->set_debug($debug);
        $this->fnac_service->set_enable_log($enable_log);

        $this->fnac_service->get_fnac_orders_list($country_id, $debug, $enable_log);
    }

}
