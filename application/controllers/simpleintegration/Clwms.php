<?php
class Clwms extends PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($include_cc = 0)
    {
        $feed = $this->sc['Clwms']->getSalesOrder($include_cc);
        header('Content-type: text/xml');
        print $feed;
    }
}