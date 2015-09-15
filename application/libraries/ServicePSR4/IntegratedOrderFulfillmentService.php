<?php
namespace ESG\Panther\Service;

class IntegratedOrderFulfillmentService extends BaseService
{
    protected $so_srv;

    public function __construct()
    {
        parent::__construct();
    }

    public function renovateData($objlist = "", $option = array())
    {
        // pass in $option that will affect what data to show

        //since the integrated_order_fulfillment table is on SKU level, so when have 'limit' clause in the SQL
        //it will have the chance of splitting the last order into two separate page, therefore have to deal with the
        //first and last order, check if they are splitted.
        $objlist = (array)$objlist;
        $total = count($objlist);
        if ($total <= 0) return;
        $first_so_no = $objlist[0]->getSoNo();

        $first_order_total_sku = $objlist[0]->getOrderTotalSku();
        $p = 0;
        for ($m = 0; $m < $total; $m++) {
            $curr_so_no = $objlist[$m]->getSoNo();
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
        $last_so_no = $objlist[$total - 1]->getSoNo();
        $last_order_total_sku = $objlist[$total - 1]->getOrderTotalSku();
        $z = 0;
        for ($k = $total; $k >= 1; $k--) {
            $curr_so_no = $objlist[$k - 1]->getSoNo();
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
            $last_so_obj = $this->getDao('So')->getIntegratedFulfillmentListWithName($where, $option);
            $last_so_obj = (array)$last_so_obj;
            $objlist = array_slice($objlist, 0, $k);
            $objlist = array_merge($objlist, $last_so_obj);
        }

        return $objlist;
    }

}


