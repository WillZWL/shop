<?php

class Batch extends MY_Controller
{

    function __construct()
    {

        // load controller parent
        parent::__construct();
        $this->load->model('integration/batch_model');
    }

    function ixtens_reprice($platform = "AMUK")
    {
        $this->batch_model->batch_service->batch_ixtens_reprice($platform);
    }

    function batch_get_amazon_order($platform = "AMUK")
    {
        $this->batch_model->batch_service->batch_get_amazon_order($platform);
    }

}

/* End of file batch.php */
/* Location: ./system/application/controllers/batch.php */