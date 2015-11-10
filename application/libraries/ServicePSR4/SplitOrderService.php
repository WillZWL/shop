<?php
namespace ESG\Panther\Service;

class SplitOrderService extends BaseService
{
    public function __construct($tool_path = 'marketing/pricing_tool')
    {
        parent::__construct();
    }

    public function genSplitOrderLogic($so_no = "")
    {
        /* This function generates the furthest split possible */
        if (!$so_no)
            return;

        $single_qty_list = [];
        if (!($so_obj = $this->getDao('So')->get(["so_no" => $so_no]))) {
            $ret["status"] = FALSE;
            $ret["message"] = __LINE__ . " SplitOrderService. so_obj not found. db error: " . $this->getDao('So')->db->_error_message();
            return $ret;
        }

        $delivery_country_id = $so_obj->getDeliveryCountryId();

        if ($soidlist = $this->getDao('SoItemDetail')->getList(["so_no" => $so_no])) {
            foreach ($soidlist as $key => $soid_obj) {
                $qty = $soid_obj->getQty();
                for ($i = 0; $i < $qty; $i++) {
                    # split out into 1 sku x 1 qty
                    $single_qty_list[]["sku"] = $soid_obj->getItemSku();
                }
            }

            # process $single_qty_list and group complementary accessories to main product
            $ca_ret = $this->processCaGroup($single_qty_list, $delivery_country_id);
            if ($ca_ret["status"] === FALSE) {
                $ret["status"] = FALSE;
                $ret["message"] = $ca_ret["message"];
                return $ret;
            }
            # after CA processing, this new list have all the CAs removed from main product level
            $single_qty_list = $ca_ret["itemlist"];

            # check if any items are recommended accessories
            $ra_ret = $this->processRaGroup($ca_ret["itemlist"]);

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
            $ret["message"] = __LINE__ . " SplitOrderService. item list not found. db error: " . $this->getDao('SoItem')->db->_error_message();
        }

        return $ret;
    }

    private function processCaGroup($itemlist = [], $country_id = "", $group = [])
    {
        /*
            * $itemlist must  be array of skus in single item.
            * For example, if you have 10111-AA-BK x 2, 10122-AA-NA x 1, then $itemlist = array("10111-AA-BK", "10111-AA-BK", "10122-AA-NA")
            * This function processes $itemlist and puts SKUs into groups
        */
        $ret = $unsetkeys = [];
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

                $v["calist"] = [];
                $where["dest_country_id"] = $country_id;
                $where["mainprod_sku"] = $sku;
                $option["all_status"] = 1;

                # get list of CAs mapped to this sku
                if ($mapped_ca_list = $this->getDao('ProductComplementaryAcc')->getMappedAccListWithName($where, $option)) {
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
                        $key = $this->recursiveGetArrayKeyByField($newitemlist, $caobj->getAccessorySku(), "sku");
                        if ($key !== FALSE) {
                            $v["calist"] = array_merge($v["calist"], (array)$caobj->getAccessorySku());

                            # don't use unset() because we need the $key to ensure next loop will not mess up
                            $newitemlist[$key]["sku"] = [];
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
            $ret["message"] = __LINE__ . "SplitOrderService. Error processCaGroup. Itemlist or country_id is empty";
            return $ret;
        }
    }

    private function recursiveGetArrayKeyByField($array, $needle, $field = "", $depth = 1)
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
                        $this->recursiveGetArrayKeyByField($value, $field, $needle, $depth - 1);
                    else
                        return FALSE;
                }
            }
        }
        return FALSE;
    }

    private function processRaGroup($itemlist = [])
    {
        $ret = $unsetkeys = $search_ra_list = [];
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

                $v["ralist"] = [];

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
                    if ($ra_list = $this->getDao('RaProduct')->getRaGroupListByProdSku($sku, $where)) {
                        foreach ($ra_list as $rak => $raarr) {
                            $ra_sku = $raarr["ra_sku"];

                            # get the array key of RA that is still placed on main SKU level
                            $key = $this->recursiveGetArrayKeyByField($newitemlist, $ra_sku, "sku");
                            if ($key !== FALSE) {
                                # if this product already has a CA attached, then don't treat it as a RA
                                # also must make sure SKU at this key is not the same as ra sku
                                if (empty($itemlist[$key]["calist"]) && $ra_sku != $itemlist[$key]["sku"]) {
                                    # if RA is found in the item list, we shift it to RA list and unset it from the main product list
                                    $v["ralist"] = array_merge($v["ralist"], (array)$itemlist[$key]["sku"]);

                                    # record the list of RA skus to the array key of main SKU it was mapped with
                                    $search_ra_list[$ra_sku] = $k;

                                    # don't use unset() because we need the $key to ensure next loop will not mess up
                                    $newitemlist[$key]["sku"] = [];
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
            $unsetkeys2 = [];
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
            $ret["message"] = __LINE__ . "SplitOrderService. Error processRaGroup. Itemlist is empty.";
            return $ret;
        }
    }
}
