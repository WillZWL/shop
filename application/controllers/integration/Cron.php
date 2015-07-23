<?php

class Cron extends MY_Controller
{
    private $app_id = "INT0002";
    private $valid_amazon_platform = array("AMUK", "AMFR", "AMDE", "AMUS");

    function __construct()
    {
        // load controller parent
        parent::__construct();
        $this->load->model('integration/batch_model');
        $this->load->model('integration/ext_platform_batch_model');
        $this->load->model('integration/integration_model');
        $this->load->library('service/product_service');
        $this->load->library('service/so_service');
        $this->load->helper(array("url"));
    }

    public function find_inactive_cps_sku()
    {
        $xmlstring = file_get_contents("http://cps.eservicesgroup.net/skuinfo.php?inactive_sku_xml");

        $xml = simplexml_load_string($xmlstring, 'SimpleXMLElement');
        foreach ($xml->entries->entry as $entry)
            // var_dump($entry->sku);
            $inactive_list[] = "{$entry->sku}";

        $result = $this->product_service->find_active_master_sku_from_list($inactive_list);

        if ($result != null) {
            $sku_string = implode("\r\n", $result);

            $sku_string = "The following list of masters SKUs:\r\n" . $sku_string;

            $recp = "tslau@eservicesgroup.net";
            $recp = "purchase@aln.hk, bd@eservicesgroup.net";
            mail($recp, "[VB] Active local SKUs mapped to inactive master SKUs (SBF#2693)", "$sku_string");
        }
    }

    public function find_delayed_ebay_orders()
    {
        # SBF#3478

        $result = $this->product_service->find_delayed_ebay_orders();
        $subject = "[VB] MarketPlace Delayed Order Notification (SBF#3478)";
        //$recp = "tslau@eservicesgroup.net";
        //$recp = "csmanager@eservicesgroup.net, fherlie@supportsave.com, dibbee@supportsave.com";
        $recp = "bd.platformteam@eservicesgroup.net, marketplace-cs@eservicesgroup.com,marketplace-cses@eservicesgroup.com";

        if ($result != null) {
            $sku_string = implode("\r\n", $result);

            $sku_string = "The following list of SO #:\r\n" . $sku_string;
            mail($recp, $subject, "$sku_string");
        } else {
            mail($recp, $subject, "Delayed orders to notify today");
        }
    }

    function cron_tracking_info($wh)
    {
        set_time_limit(300);
        $this->batch_model->batch_tracking_info_service->cron_tracking_info($wh);
    }

    function cron_inventory()
    {
        $batch_id = $this->batch_model->batch_inventory_service->cron_inventory();
        if ($batch_id != NULL) {
            if ($batch_id == 1) {
                echo "Files already exists";
            } else {
                redirect(base_url() . "integration/integration/view/inventory/" . $batch_id);
            }
        } else {
            echo "No files in the folder!";
        }
    }

    function gen_amazon_prod_feed($platform = "AMUS")
    {
        if (!in_array(strtoupper($platform), $this->valid_amazon_platform)) {
            return false;
        }

        $this->batch_model->batch_service->generate_prod_feed(strtoupper($platform));
    }

    function gen_amazon_discfeed($platform = "AMUS")
    {
        if (!in_array(strtoupper($platform), $this->valid_amazon_platform)) {
            return false;
        }

        $this->batch_model->batch_service->get_discontinue_feed(strtoupper($platform));
    }

    function get_amazon_orders($platform = "AMUS")
    {
        if (!in_array(strtoupper($platform), $this->valid_amazon_platform)) {
            return false;
        }

        $this->batch_model->batch_service->cron_get_amazon_order(strtoupper($platform));
    }

    function get_fba_orders($platform = "AMUS")
    {
        if (!in_array(strtoupper($platform), $this->valid_amazon_platform)) {
            return false;
        }

        $this->batch_model->batch_service->cron_get_fba_order(strtoupper($platform));
    }

    function manual_get_amazon_orders($platform = "AMUS")
    {
        if (!in_array(strtoupper($platform), $this->valid_amazon_platform)) {
            return false;
        }

        $this->batch_model->batch_get_amazon_order(strtoupper($platform));
    }

    function gen_amazon_ackfeed($platform = "AMUS")
    {
        if (!in_array(strtoupper($platform), $this->valid_amazon_platform)) {
            return false;
        }

        $this->batch_model->batch_service->gen_amazon_ackfeed(strtoupper($platform));
    }

    function gen_amazon_fulfillfeed($platform = "AMUS")
    {
        if (!in_array(strtoupper($platform), $this->valid_amazon_platform)) {
            return false;
        }

        $this->batch_model->batch_service->gen_amazon_fulfillfeed(strtoupper($platform));
    }

    function cron_ixtens_reprice($platform = "AMUS")
    {
        if (!in_array(strtoupper($platform), $this->valid_amazon_platform)) {
            return false;
        }

        $this->batch_model->batch_service->cron_ixtens_reprice(strtoupper($platform));
    }

    function generate_sli_prod_feed()
    {
        $this->batch_model->batch_service->generate_sli_prod_feed();
    }

    function gen_prod_feeds()
    {
        $this->batch_model->batch_service->gen_prod_feeds();
    }

    function gen_purchaser_feed()
    {
        $this->batch_model->batch_service->gen_purchaser_feed();
    }

    function lstrans()
    {
        $this->batch_model->batch_service->lstrans();
    }

    function t3m_gen()
    {
        $this->batch_model->t3m_handle_service->send_request();
    }

    function t3m_update($file)
    {
        $this->batch_model->t3m_handle_service->updaterecord($file);
    }

    function t3m_response($file)
    {
        $this->batch_model->t3m_handle_service->process_response($file);
    }

    function t3m_result()
    {
        $this->batch_model->t3m_handle_service->t3m_result();
    }

    function gen_sli_feed()
    {
        $this->batch_model->batch_service->generate_sli_prod_feed("file");
    }

    function gen_shipping_override_amuk()
    {
        $this->ext_platform_batch_model->gen_shipping_override('AMUK');
    }

    function gen_dqty()
    {
        $this->batch_model->product_service->gen_display_quantity();
    }

    function cron_drop_display_qty()
    {
        $this->batch_model->display_qty_service->cron_drop_display_qty();
    }

    function get_ebay_order($ebay_account, $specified_file = "")
    {
        $this->batch_model->get_ebay_order($ebay_account, $specified_file);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function cps_allocated_so_no()
    {
        $this->integration_model->cps_allocated_so_no();
    }

    public function cancel_order($age_in_days = 10)
    {
        $this->so_service->cancel_order($age_in_days);
    }
}



