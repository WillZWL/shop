<?php

class Website_bank_transfer_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/so_service');
        $this->load->library('service/website_bank_transfer_service');
        $this->load->library('service/pmgw');
    }

    public function include_vo($dao)
    {
        $dao = "get_" . $dao;
        return $this->so_service->$dao()->include_vo();
    }

    public function payment_reminder($platform_id)
    {
        $this->website_bank_transfer_service->payment_reminder($platform_id);
    }

    public function cancel_unpaid($platform_id)
    {
        $this->website_bank_transfer_service->cancel_unpaid($platform_id);
    }

    public function _trans_start($dao)
    {
        $this->so_service->$dao()->trans_start();
    }

    public function _trans_complete($dao)
    {
        $this->so_service->$dao()->trans_complete();
    }

}

?>