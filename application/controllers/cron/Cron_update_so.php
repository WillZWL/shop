<?php

class Cron_update_so extends MY_Controller
{
    private $app_id = "CRN0033";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/so_model');
    }

    public function update_so_item_unit_cost()
    {
        $this->so_model->so_service->update_empty_so_item_cost();
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}
/* End of file cron_update_so.php */
/* Location: ./app/controllers/cron_update_so.php */
