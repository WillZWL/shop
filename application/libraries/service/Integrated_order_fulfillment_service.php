<?php

include_once "Base_service.php";

class Integrated_order_fulfillment_service extends Base_service
{
    protected $so_srv;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Integrated_order_fulfillment_dao.php");
        $this->set_dao(new Integrated_order_fulfillment_dao());

        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_srv(new So_service());
    }

    public function renovate_data($objlist = "", $option = array())
    {
        // pass in $option that will affect what data to show

        //since the integrated_order_fulfillment table is on SKU level, so when have 'limit' clause in the SQL
        //it will have the chance of splitting the last order into two separate page, therefore have to deal with the
        //first and last order, check if they are splitted.
        $objlist = (array)$objlist;
        $total = count($objlist);
        if ($total <= 0) return;
        $first_so_no = $objlist[0]->get_so_no();

        $first_order_total_sku = $objlist[0]->get_order_total_sku();
        $p = 0;
        for ($m = 0; $m < $total; $m++) {
            $curr_so_no = $objlist[$m]->get_so_no();
            if ($curr_so_no != $first_so_no) {
                break;
            }
            $p++;
        }

        //if the No. of sku displayed NOT equal to the order total sku, then it mean this order is splitted, so
        //can remove it, and it also means this order is included in last page already
        if ($p != $first_order_total_sku) {
            $objlist = array_slice($objlist, $m);
        }

        //re-count the array length
        $total = count($objlist);
        $last_so_no = $objlist[$total - 1]->get_so_no();
        $last_order_total_sku = $objlist[$total - 1]->get_order_total_sku();
        $z = 0;
        for ($k = $total; $k >= 1; $k--) {
            $curr_so_no = $objlist[$k - 1]->get_so_no();
            if ($curr_so_no != $last_so_no) {
                break;
            }
            $z++;
        }

        //if the No. of sku displayed NOT equal to the order total sku, then it mean this order is splitted, so
        //can remove it. But need to select that order again from database and append to the list.
        if ($last_order_total_sku != $z) {
            $where['iof.so_no = '] = $last_so_no;
            $option['limit'] = -1;
            $last_so_obj = $this->get_so_srv()->get_dao()->get_integrated_fulfillment_list_w_name($where, $option);
            $last_so_obj = (array)$last_so_obj;
            $objlist = array_slice($objlist, 0, $k);
            $objlist = array_merge($objlist, $last_so_obj);
        }

        return $objlist;
    }

    public function get_so_srv()
    {
        return $this->so_srv;
    }

    public function set_so_srv($srv)
    {
        $this->so_srv = $srv;
    }

}

/* End of file integrated_order_fulfillment_service.php */
/* Location: ./app/libraries/service/Integrated_order_fulfillment_service.php */