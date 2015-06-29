<?php

class Promotion_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/affiliate_service.php');
        $this->load->library('service/skype_promotion_service.php');
        $this->load->library('service/product_service.php');
    }

    public function check_promotion($promotion)
    {
        $valid_promo = array("skype-vouchers-60");
        return in_array($promotion, $valid_promo);
    }

    public function check_affiliate($affiliate)
    {
        if ($this->affiliate_service->get(array("id" => $affiliate))) {
            $this->affiliate_service->add_af_cookie($affiliate);
            return TRUE;
        }
        return FALSE;
    }

    public function get_view_data($promotion)
    {
        $data = array();
        switch ($promotion) {
            case "skype-vouchers-60":
                $data["popular"] = $this->product_service->get_product_info('10055-AA-NA', PLATFORMID, get_lang_id());
                $data["cat_list"]["headsets"] = $this->skype_promotion_service->get_promotion_landing_prod_list_by_cat(3, PLATFORMID, get_lang_id());
                $data["cat_list"]["webcams"] = $this->skype_promotion_service->get_promotion_landing_prod_list_by_cat(array(10, 11), PLATFORMID, get_lang_id());
                $data["cat_list"]["smartphones"] = $this->skype_promotion_service->get_promotion_landing_prod_list_by_cat(array(15, 22, 8), PLATFORMID, get_lang_id());
                break;
        }
        return $data;
    }
}

/* End of file promotion_model.php */
/* Location: ./app/models/promotion_model.php */