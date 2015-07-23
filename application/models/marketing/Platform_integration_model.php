<?php

class Platform_integration_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/order_email_service');
    }

    public function get_qoo10_orders($action, $country_id, $test)
    {
        $this->load->library('service/qoo10_service');
        switch ($action) {
            case 'import':
                #cron job
                $this->qoo10_service->import_orders($country_id, $test);
                break;

            case 'update_shipment_status':
                #cron job
                $this->qoo10_service->update_shipment_status($country_id);
                break;

            case 'add_items':
                #cron job
                $this->qoo10_service->add_items($country_id);
                break;


            default:
                echo "Please input correct action.";
                break;
        }

    }

    public function get_rakuten_orders($action, $country_id = "ES", $value = "", $test = FALSE)
    {
        $this->load->library('service/rakuten_service');
        switch ($action) {
            // case 'import':
            //  #cron job
            //  $this->rakuten_service->import_orders($country_id, $value, $test);
            //  break;

            case 'get_orders_list':
                #cron job
                $this->rakuten_service->get_orders_list($country_id, $value, $test);
                break;

            case 'import':
                #cron job
                $this->rakuten_service->import_orders_new($country_id, $value, $test);
                break;

            case 'import_single_order':
                $this->rakuten_service->import_single_order($country_id, $value, $test);
                break;

            case 'update_shipment_status':
                #cron job
                $this->rakuten_service->update_shipment_status($country_id, $value, $test);
                break;

            case 'add_items':
                #cron job
                $this->rakuten_service->add_items($country_id, $value, $test);
                break;

            case 'add_item_images':
                #cron job
                $this->rakuten_service->add_item_images($country_id, $value, $test);
                break;

            case 'bulk_update':
                $this->rakuten_service->bulk_update_item($country_id);
                break;
            // case 'update':
            //  #cron job
            //  $this->rakuten_service->update_item($country_id, $test);
            //  break;

            case 'delist_sku':
                #cron job
                $this->rakuten_service->delist_sku($country_id, $value, $test);
                break;
            default:
                echo "Please input correct action.";
                break;
        }

    }

    public function get_client_delivery_contact($platform_id = "ES", $day_diff = 0)
    {
        $this->order_email_service->gen_client_contact_delivery($platform_id, $day_diff);
    }


}