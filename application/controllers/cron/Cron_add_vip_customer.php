<?php

class Cron_add_vip_customer extends MY_Controller
{
    private $app_id = "CRN0009";

    function __construct()
    {
        parent::__construct();
        $this->load->model('website/client_model');
    }

    public function index()
    {
        if ($vip_list = $this->client_model->get_new_vip_customer_list()) {
            foreach ($vip_list as $client_id) {
                if ($client_obj = $this->client_model->get_client(array("id" => $client_id))) {
                    $client_obj->set_vip(1);
                    $client_obj->set_vip_joined_date(date("Y-m-d"));
                    $this->client_model->update_client($client_obj);
                }
            }
        }
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}

/* End of file cron_data_feed.php */
/* Location: ./app/controllers/cron_data_feed.php */
