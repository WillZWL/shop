<?php

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Promotion extends PUB_Controller
{

    public function Promotion()
    {
        parent::PUB_Controller();
        $this->load->model('website/promotion_model');
    }

    public function view($promotion = "", $affiliate = "")
    {
        if ($promotion && $affiliate) {
            if ($this->promotion_model->check_promotion($promotion) && $this->promotion_model->check_affiliate($affiliate)) {
                $data = $this->promotion_model->get_view_data($promotion);
                $this->load->view("promotion/" . str_replace("-", "_", $promotion) . ".php", $data);
            } else {
                show_404('page');
            }
        } else {
            show_404('page');
        }
    }

    public function skypepremium()
    {
        redirect(base_url() . "FaceVsion-TouchCam-V1/product_skype/add_promote/10120-AA-NA/skypepremiumV1");
    }
}
