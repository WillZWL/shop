<?php
class Cron_daily_order_email extends MY_Controller
{
    private $app_id = 'CRN0028';

    function __construct()
    {
        parent::__construct();
        $this->load->library('service/order_email_service');
        $this->load->library('service/split_order_email_service');
    }

    public function send_daily_order_email($supplier_id)
    {
        $supplier_ids = explode(',', $supplier_id);
        foreach ($supplier_ids as $supplier_id) {
            $supplier_id = strtoupper($supplier_id);
            $this->order_email_service->send_daily_order_by_supplier($supplier_id);
        }
    }

    public function send_order_for_split_email()
    {
        $this->split_order_email_service->send_order_for_split_email();
    }

    public function get_order_beforeship()
    {
        $this->order_email_service->get_order_beforeship();
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}

/* End of file Cron_daily_order_email.php */
/* Location: ./app/controllers/cron_daily_order_email.php */
