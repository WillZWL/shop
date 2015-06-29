<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Clearance extends PUB_Controller
{

    public function Clearance()
    {
        parent::PUB_Controller();
        $this->load->helper(array('url'));
        $this->load->model('marketing/product_model');
        $this->load->model('marketing/category_model');
        $this->load->model('marketing/best_seller_model');
        $this->load->model('marketing/latest_arrivals_model');
        $this->load->model('marketing/top_deals_model');
        $this->load->model('marketing/banner_model');
    }

    public function index()
    {
        $data["brandlist"] = $this->category_model->get_display_list($catid, "brand");
        $data["best_seller"] = $bs;
        $data["latest_arrival"] = $la;
        $data["top_deals"] = $td;

        if ($this->input->get("cat") != "") {
            $where["cat"] = $this->input->get("cat");
        }

        if ($this->input->get("scat") != "") {
            $where["scat"] = $this->input->get("scat");
        }

        if ($this->input->get("cat") != "") {
            $where["scat"] = $this->input->get("scat");
        }

        if ($this->input->get("brand") != "") {
            $where["brand"] = $this->input->get("brand");
        }

        if ($this->input->get("colour") != "") {
            $where["colour"] = $this->input->get("colour");
        }

        $page = $this->input->get('page') * 1;

        if ($page <= 0) {
            $page = 1;
        }

        $option["page"] = $page - 1;

        if ($this->input->get('displayqty') * 1 > 0) {
            $option["limit"] = $this->input->get('displayqty') * 1;
        } else {
            $option["limit"] = 10;
        }

        switch ($this->input->get('sort')) {
            case 'price':
                $option["sort"] = "pr.price";
                $option["order"] = $this->input->get('order');
                break;

            case 'colour':
                $option["sort"] = 'p.colour_id';
                $option["order"] = $this->input->get('order');
                break;

            default:
                break;
        }

        $data["prodcnt"] = $prodnum = $this->product_model->get_clearance_list_cnt($where);
        if ($page > ceil($prodnum / $option["limit"])) {
            $page = ceil($prodnum / $option["limit"]) - 1;
            $option["page"] = $page;
        }

        $data["page"] = $page;
        $data["rpp"] = $option["limit"];
        $data["prodlist"] = $prodlist = $this->product_model->get_clearance_list($where, $option);
        $data["colour_list"] = $this->category_model->get_colour_code();
        $this->load_view('clearance.php', $data);
    }
}

/* End of file clearance.php */
/* Location: ./app/public_controllers/clearance.php */