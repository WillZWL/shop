<?php

class Sku_mapping_feed_model extends CI_Model
{
    const SKU_SCHEDULE_JOB_ID = 'SKU_MAPPING_FEED';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/sku_mapping_feed_service');
    }

    public function generate_sku_mapping_difference($need_all_sku)
    {
        $this->sku_mapping_feed_service->generate_sku_mapping_difference($need_all_sku, Sku_mapping_feed_model::SKU_SCHEDULE_JOB_ID);
    }
}

