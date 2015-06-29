<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Split_order_service extends Base_service
{
    private $so_service;
    private $so_dao;
    private $soi_dao;
    private $soid_dao;
    private $sops_dao;
    private $soext_dao;
    private $ca_service;
    private $ra_product_service;
    private $order_notes_service;
    private $refund_service;
    private $order_status_history_service;
    private $so_priority_score_service;
    private $so_refund_score_service;


    public function Split_order_service($tool_path = 'marketing/pricing_tool')
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_service(new So_service());
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH . "libraries/dao/So_item_dao.php");
        $this->set_soi_dao(new So_item_dao());
        include_once(APPPATH . "libraries/dao/So_item_detail_dao.php");
        $this->set_soid_dao(new So_item_detail_dao());
        include_once(APPPATH . "libraries/dao/So_payment_status_dao.php");
        $this->set_sops_dao(new So_payment_status_dao());
        include_once(APPPATH . "libraries/dao/So_extend_dao.php");
        $this->set_soext_dao(new So_extend_dao());
        include_once(APPPATH . "libraries/service/Complementary_acc_service.php");
        $this->set_ca_service(new Complementary_acc_service());
        include_once(APPPATH . "libraries/service/Ra_product_service.php");
        $this->set_ra_product_service(new Ra_product_service());
        include_once(APPPATH . 'libraries/service/Order_notes_service.php');
        $this->set_order_notes_service(new Order_notes_service());
        include_once(APPPATH . 'libraries/service/Refund_service.php');
        $this->set_refund_service(new Refund_service());
        include_once(APPPATH . 'libraries/service/Order_status_history_service.php');
        $this->set_order_status_history_service(new Order_status_history_service());
        include_once(APPPATH . 'libraries/service/So_priority_score_service.php');
        $this->set_so_priority_score_service(new So_priority_score_service());
        include_once(APPPATH . 'libraries/service/So_refund_score_service.php');
        $this->set_so_refund_score_service(new So_refund_score_service());
        // $this->load->library('service/quick_search_service');
        // $this->load->library('service/client_service');
        // $this->load->library('service/delivery_option_service');

    }

    public function get_so_service()
    {
        return $this->so_service;
    }

    public function set_so_service($value)
    {
        $this->so_service = $value;
    }

    public function get_soid_dao()
    {
        return $this->soid_dao;
    }

    public function set_soid_dao(Base_dao $dao)
    {
        $this->soid_dao = $dao;
    }

    public function get_sops_dao()
    {
        return $this->sops_dao;
    }

    public function set_sops_dao(Base_dao $dao)
    {
        $this->sops_dao = $dao;
    }

    public function get_soext_dao()
    {
        return $this->soext_dao;
    }

    public function set_soext_dao(Base_dao $dao)
    {
        $this->soext_dao = $dao;
    }

    public function get_order_notes_service()
    {
        return $this->order_notes_service;
    }

    public function set_order_notes_service($value)
    {
        $this->order_notes_service = $value;
    }

    public function get_refund_service()
    {
        return $this->refund_service;
    }

    public function set_refund_service($value)
    {
        $this->refund_service = $value;
    }

    public function get_order_status_history_service()
    {
        return $this->order_status_history_service;
    }

    public function set_order_status_history_service($value)
    {
        $this->order_status_history_service = $value;
    }

    public function get_so_priority_score_service()
    {
        return $this->so_priority_score_service;
    }

    public function set_so_priority_score_service($value)
    {
        $this->so_priority_score_service = $value;
    }

    public function get_so_refund_score_service()
    {
        return $this->so_refund_score_service;
    }

    public function set_so_refund_score_service($value)
    {
        $this->so_refund_score_service = $value;
    }

    public function gen_split_order_logic($so_no = "")
    {
        /* This function generates the furthest split possible */
        if (!$so_no)
            return;

        $single_qty_list = array();
        if (!($so_obj = $this->get_so_dao()->get(array("so_no" => $so_no)))) {
            $ret["status"] = FALSE;
            $ret["message"] = __LINE__ . " split_order_service. so_obj not found. db error: " . $this->get_so_dao()->db->_error_message();
            return $ret;
        }

        $delivery_country_id = $so_obj->get_delivery_country_id();
        if ($soilist = $this->get_soi_dao()->get_list(array("so_no" => $so_no))) {
            foreach ($soilist as $key => $soi_obj) {
                $qty = $soi_obj->get_qty();
                for ($i = 0; $i < $qty; $i++) {
                    # split out into 1 sku x 1 qty
                    $single_qty_list[]["sku"] = $soi_obj->get_prod_sku();
                }
            }

            # process $single_qty_list and group complementary accessories to main product
            $ca_ret = $this->process_ca_group($single_qty_list, $delivery_country_id);
            if ($ca_ret["status"] === FALSE) {
                $ret["status"] = FALSE;
                $ret["message"] = $ca_ret["message"];
                return $ret;
            }
            # after CA processing, this new list have all the CAs removed from main product level
            $single_qty_list = $ca_ret["itemlist"];

            # check if any items are recommended accessories
            $ra_ret = $this->process_ra_group($ca_ret["itemlist"]);

            if ($ra_ret["status"] === FALSE) {
                $ret["status"] = FALSE;
                $ret["message"] = $ra_ret["message"];
                return $ret;
            }

            # remove anything that has empty sku
            if ($final_item_list = $ra_ret["itemlist"]) {
                foreach ($final_item_list as $k => $v) {
                    if (empty($v["sku"]))
                        unset($final_item_list[$k]);
                }

                # for display purposes, reset the keys to be in running numbers
                $resetkey = 0;
                foreach ($final_item_list as $key => $value) {
                    $reset_item_list[$resetkey] = $value;
                    $resetkey++;
                }
            }

            $ret["status"] = TRUE;
            $ret["group"] = $reset_item_list;
            return $ret;
        } else {
            $ret["status"] = FALSE;
            $ret["message"] = __LINE__ . " split_order_service. item list not found. db error: " . $this->get_soi_dao()->db->_error_message();
        }

        return $ret;
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function set_so_dao($value)
    {
        $this->so_dao = $value;
    }

    public function get_soi_dao()
    {
        return $this->soi_dao;
    }

    public function set_soi_dao(Base_dao $dao)
    {
        $this->soi_dao = $dao;
    }

    private function process_ca_group($itemlist = array(), $country_id = "", $group = array())
    {
        /*
            * $itemlist must  be array of skus in single item.
            * For example, if you have 10111-AA-BK x 2, 10122-AA-NA x 1, then $itemlist = array("10111-AA-BK", "10111-AA-BK", "10122-AA-NA")
            * This function processes $itemlist and puts SKUs into groups
        */
        $ret = $unsetkeys = array();
        if (is_array($itemlist) && $country_id) {
            $i = 0;
            $newitemlist = $itemlist;
            foreach ($itemlist as $k => $v) {
                # if currently has previously been unset, skip.
                if (in_array($k, $unsetkeys))
                    continue;

                # if CA has been added, skip.
                if (is_array($v["calist"]))
                    continue;

                if (!($sku = $v["sku"]))
                    continue;

                $v["calist"] = array();
                $where["dest_country_id"] = $country_id;
                $where["mainprod_sku"] = $sku;
                $option["all_status"] = 1;

                # get list of CAs mapped to this sku
                if ($mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option)) {
                    foreach ($mapped_ca_list as $cakey => $caobj) {
                        /*
                            Check if this mapped accessory exists in the itemlist.
                            If $key = false, probably means CA wasn't mapped at point of customer's checkout.
                            If CA found in itemlist, we empty it at main SKU level and shift it the ca list - this also
                                ensures same key is not grouped twice (allow us to make sure correct qty of CA is matched to main product).
                             - different main products can have same CA sku
                             - by right, qty of CAs will match qty of main prod. e.g main_sku x 3 => ca_sku_1 x 3, ca_sku_2 x 3....
                        */

                        # get the array key of CA that is still placed on main SKU level
                        $key = $this->recursive_get_array_key_by_field($newitemlist, $caobj->get_accessory_sku(), "sku");
                        if ($key !== FALSE) {
                            $v["calist"] = array_merge($v["calist"], (array)$caobj->get_accessory_sku());

                            # don't use unset() because we need the $key to ensure next loop will not mess up
                            $newitemlist[$key]["sku"] = array();
                            $unsetkeys = array_merge($unsetkeys, (array)$key);
                        }

                    }
                }

                # this list contains main product SKUs with their CA list grouped. CAs previous on main product level has been emptied
                $newitemlist[$k]["calist"] = $v["calist"];
            }

            $ret["status"] = TRUE;
            $ret["itemlist"] = $newitemlist;
            return $ret;
        } else {
            $ret["status"] = FALSE;
            $ret["message"] = __LINE__ . "split_order_service. Error process_ca_group. Itemlist or country_id is empty";
            return $ret;
        }
    }

    public function get_ca_service()
    {
        return $this->ca_service;
    }

    public function set_ca_service($value)
    {
        $this->ca_service = $value;
    }

    private function recursive_get_array_key_by_field($array, $needle, $field = "", $depth = 1)
    {
        // get the first array key for the $needle in $array[$field]
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if ($depth == 1) {
                    if ($field != "") {
                        if ($value[$field] == $needle)
                            return $key;
                    } else {
                        if ($value == $needle)
                            return $key;
                    }
                } else {
                    # if $depth > 1, we go deeper into the multidimensional array
                    if (is_array($value))
                        $this->recursive_get_array_key_by_field($value, $field, $needle, $depth - 1);
                    else
                        return FALSE;
                }
            }
        }
        return FALSE;
    }

    private function process_ra_group($itemlist = array())
    {
        $ret = $unsetkeys = $search_ra_list = array();
        if (is_array($itemlist)) {

            $newitemlist = $itemlist;
            foreach ($itemlist as $k => $v) {
                # if currently has previously been unset, skip.
                if (in_array($k, $unsetkeys))
                    continue;

                # if RA has been added, skip.
                if (is_array($v["ralist"]))
                    continue;

                if (!($sku = $v["sku"]))
                    continue;

                $v["ralist"] = array();

                # first get the whole list of SKUs for searching recommended accessory (RA)
                foreach ($itemlist as $listk => $listv) {
                    # exclude current $sku product in mysql checking
                    # else will cause problem if ALL items in order appears in RA list
                    if ($listv["sku"] == $sku)
                        continue;

                    if ($listv["sku"])
                        $skulist[] = $listv["sku"];

                    if ($skulist)
                        $skustring = implode("','", $skulist);
                }

                if ($skustring) {
                    // check if itemlist has SKUs that are RA products mapped to current $sku
                    $where["ragp.sku IN ('$skustring')"] = null;
                    if ($ra_list = $this->get_ra_product_service()->get_ra_group_list_by_prod_sku($sku, $where)) {
                        foreach ($ra_list as $rak => $raarr) {
                            $ra_sku = $raarr["ra_sku"];

                            # get the array key of RA that is still placed on main SKU level
                            $key = $this->recursive_get_array_key_by_field($newitemlist, $ra_sku, "sku");
                            if ($key !== FALSE) {
                                # if this product already has a CA attached, then don't treat it as a RA
                                # also must make sure SKU at this key is not the same as ra sku
                                if (empty($itemlist[$key]["calist"]) && $ra_sku != $itemlist[$key]["sku"]) {
                                    # if RA is found in the item list, we shift it to RA list and unset it from the main product list
                                    $v["ralist"] = array_merge($v["ralist"], (array)$itemlist[$key]["sku"]);

                                    # record the list of RA skus to the array key of main SKU it was mapped with
                                    $search_ra_list[$ra_sku] = $k;

                                    # don't use unset() because we need the $key to ensure next loop will not mess up
                                    $newitemlist[$key]["sku"] = array();
                                    $unsetkeys = array_merge($unsetkeys, (array)$key);
                                }
                            }
                        }
                    }
                }

                # this list contains main product SKUs with their RA list grouped. RAs previous on main product level has been emptied
                $newitemlist[$k]["ralist"] = $v["ralist"];
            }

            /*
                Because qty of RA can be more than qty of main product (e.g. customer buys 3 SD cards for one camera),
                there may be extra RAs on the main SKU level. So, loop through $newitemlist again and see if any RAs found previously
                are still on SKU level. If found, group it to the same main SKU found in the previous loop.
            */
            $unsetkeys2 = array();
            foreach ($newitemlist as $k => $v) {
                if (in_array($k, $unsetkeys2)) continue;

                if (!($sku = $v["sku"])) continue;
                if (array_key_exists($sku, $search_ra_list)) {
                    # the array key of main product that includes this RA previously
                    $mainskukey = $search_ra_list[$sku];
                    $newitemlist[$mainskukey]["ralist"] = array_merge($newitemlist[$mainskukey]["ralist"], (array)$sku);
                    unset($newitemlist[$k]);
                    $unsetkeys2 = array_merge($unsetkeys2, (array)$k);
                }
            }

            $ret["status"] = TRUE;
            $ret["itemlist"] = $newitemlist;
            return $ret;
        } else {
            $ret["status"] = FALSE;
            $ret["message"] = __LINE__ . "split_order_service. Error process_ra_group. Itemlist is empty.";
            return $ret;
        }
    }

    public function get_ra_product_service()
    {
        return $this->ra_product_service;
    }

    public function set_ra_product_service($value)
    {
        $this->ra_product_service = $value;
    }

}
