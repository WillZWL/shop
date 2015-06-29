<?php
class Special_order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/so_service');
        $this->load->library('service/product_service');
    }

}

/* End of file special_order_model.php */
/* Location: ./system/application/models/special_order_model.php */
?>