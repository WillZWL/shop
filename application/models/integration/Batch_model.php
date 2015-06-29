<?php
class Batch_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->library('service/batch_service');
        $this->load->library('service/batch_tracking_info_service');
        $this->load->library('service/batch_inventory_service');
        $this->load->library('service/batch_youtube_video_service');
        $this->load->library('service/t3m_handle_service');
        $this->load->library('service/product_service');
        $this->load->library('service/ebay_service');
        $this->load->library('service/display_qty_service');
    }

    public function get_ebay_order($ebay_account, $specified_file="")
    {
        return $this->ebay_service->get_ebay_order($ebay_account, $specified_file);
    }
}

/* End of file batch_model.php */
/* Location: ./system/application/models/integration/batch_model.php */