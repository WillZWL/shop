<?php

class Deliverytime_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/deliverytime_service');
        $this->load->library('service/language_service');
        $this->load->library('service/region_service');
        $this->load->library('service/country_service');
    }

}

/* End of file deliverytime_model.php */
/* Location: ./system/application/mastercfg/deliverytime_model.php */
