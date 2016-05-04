<?php

use ESG\Panther\Models\Website\CartSessionModel;
use ESG\Panther\Service\AffiliateService;

class ReviewOrder extends PUB_Controller
{
    public $affiliateService;
    public function __construct()
    {
        parent::__construct();
/*
        $this->load->model('website/cart_session_model');
        $this->load->model('website/checkout_model');
        $this->load->library('service/context_config_service');
        $this->load->library('service/complementary_acc_service');
        $this->load->library('service/affiliate_service');
        $this->load->library('service/tradedoubler_tracking_script_service');
*/
        $this->cartSessionModel = new CartSessionModel;
        $this->affiliateService = new AffiliateService();
    }

    public function index()
    {
        $data["tracking_data"]['cartInfo']=$data['cartInfo'] = $this->cartSessionModel->getCartInfo();
        $this->affiliateService->addAfCookie($_GET);


//        var_dump($data['cartInfo']);
        if ($data['cartInfo']){

            $this->load->view('review', $data);
        }
        else
            $this->load->view('reviewEmptyCart', $data);

    }

    
    public function remove($sku = "")
    {
        if ($sku != "") {
            $this->cart_session_model->remove($sku, PLATFORMID);
        }
        $this->index();
    }

    /*************************************
     *   we don't use update.ini, because there is no template file, use index.ini directly instead
     ***************************************/
    public function update($sku = "", $qty = "")
    {
        $allow_result = $this->cart_session_model->cart_session_service->is_allow_to_add($sku, 1, PLATFORMID);
        if ($allow_result <= Cart_session_service::DECISION_POINT) {
            if ($sku != "" && $qty != "") {
                $this->cart_session_model->update($sku, $qty, PLATFORMID);
            }
            $this->index();
        } else {
            redirect(base_url() . "review_order?item_status=" . $allow_result . "&not_valid_sku=" . $sku);
        }
    }
}
