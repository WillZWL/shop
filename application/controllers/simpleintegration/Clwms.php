<?php
class Clwms extends PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($include_cc = 0)
    {
        $where = [];

        if ($include_cc == 1) {
            $where['so.status in (2, 3)'] = null;
        } else {
            $where['so.status ='] = 3;
        }

        $where['so.refund_status ='] = 0;
        $where['so.hold_status ='] = 0;
        $option["orderby"] = 'so.so_no desc';
        $option['limit'] = -1;

        $feed = $this->sc['Clwms']->getSalesOrder($where, $option);
        header('Content-type: text/xml');
        print $feed;
    }
}