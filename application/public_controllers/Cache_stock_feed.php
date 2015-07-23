<?php
include_once(CTRLPATH . 'stock_feed.php');

class Cache_stock_feed extends Stock_feed
{
    public function xml_skype_feed($sku = "", $promotion_code = "")
    {
        $cache = $this->config->item('cache');

        if ($cache['stock_skype_feed/xml_skype_feed']['status']) {
            $this->load->model('cache/db_cache_model');

            $query_sku = (empty($promotion_code) ? $sku : $sku . '/' . $promotion_code);

            $data = $this->db_cache_model->load_cache('get_xml_skype_feed', array('sku' => $query_sku, 'platform_id' => PLATFORMID));
            if ($data) {
                $this->load->view('stock_skype_feed.php', $data);
            } else {
                $data = parent::xml_skype_feed($sku, $promotion_code);
                $data['cache_time'] = $cache['stock_skype_feed/xml_skype_feed']['time'];
                $this->db_cache_model->write_cache('save_xml_skype_feed', $data);
            }
        } else {
            //parent::xml_skype_feed($sku, $promotion_code);
        }
    }

}
