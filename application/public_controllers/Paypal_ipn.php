<?php

class Paypal_ipn extends PUB_Controller
{

    public function Paypal_ipn()
    {
        parent::PUB_Controller();
        $this->load->model('website/checkout_model');
        $this->load->library('service/context_config_service');
    }

    public function index($debug = 0)
    {
        $this->checkout_model->paypal_ipn_notification($debug);
    }
}

/* End of file paypal_ipn.php */
/* Location: ./app/public_controllers/paypal_ipn.php */