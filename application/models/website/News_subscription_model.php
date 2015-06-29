<?php

class News_subscription_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('service/news_subscriber_service');
    }

    public function add_subscriber($email)
    {
        $this->news_subscriber_service->add_subscriber($email);
    }
}

