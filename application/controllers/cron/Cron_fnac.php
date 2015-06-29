<?php

class Cron_fnac extends MY_Controller
{
    private $app_id = "CRN0005";

    function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/fnac_model');
    }

    /*
        NOTES FROM FNAC DOCUMENTATION:
        Orders statuses are following this workflow:
        Created > Accepted > ToShip > Shipped > Received
        The seller acts only at acceptation and shipping steps.
    */

    public function cron_retrieve_new_order($country_id = "ES", $start_time = "", $end_time = "", $debug = 0, $enable_log = 0)
    {
        $this->fnac_model->cron_retrieve_new_order($country_id, $start_time, $end_time, $debug, $enable_log);
    }

    public function cron_update_payment_status($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_model->cron_update_payment_status($country_id, $debug, $enable_log);
    }

    public function acknowledge_order($country_id = "ES", $fnac_order_id_list = "")
    {
        $this->fnac_model->acknowledge_order($country_id, $fnac_order_id_list);
    }

    public function cron_update_shipment_status($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_model->cron_update_shipment_status($country_id, $debug, $enable_log);
    }

    public function cron_check_offer_update_status($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_model->cron_check_offer_update_status($country_id, $debug, $enable_log);
    }

    public function get_fnac_offer_list($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_model->get_fnac_offer_list($country_id, $debug, $enable_log);
    }

    public function sync_fnac_offer_list($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_model->sync_fnac_offer_list($country_id, $debug, $enable_log);
    }

    public function get_fnac_orders_list($country_id = "ES", $debug = 0, $enable_log = 0)
    {
        $this->fnac_model->get_fnac_orders_list($country_id, $debug, $enable_log);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}

/* End of file cron_fnac.php */
/* Location: ./app/controllers/cron_fnac.php */
