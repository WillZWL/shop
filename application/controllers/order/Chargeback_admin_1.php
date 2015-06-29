<?php defined('BASEPATH') OR exit('No direct script access allowed');

load_class('MY_Datagrid', FALSE);

class Chargeback_admin_1 extends gridpage
{
    protected $app_id="ORD0028";
    private $lang_id="en";

    function __construct()
    {
        global $_dbprefix;
        parent::__construct("Case history", "chargeback_audit");
    }

    function index()
    {
        extract($this->var);

        for ($i = 0; $i <= 6; $i++)
        {
            switch ($i)
            {
                case 0: $x = 6; break;
                case 1: $x = 8; break;
                case 2: $x = 9; break;
                case 3: $x = 39; break;
                case 4: $x = 51; break;
                case 5: $x = 52; break;
                case 6: $x = 1; break;
            }
            $_POST["tfa_$x"] = $this->import_param("tfa_$x");
        }

        $where = $this->create_criteria_from_post();
        $this->var["where"] = $where;
        $_SESSION["where"] = $where;

        // var_dump($where);

        // $this->config($title, $table);

        $parentid = $this->link_parent("chargeback_id", "CB#", true);

        // override with search handler
        $this->config("Case history CB#$parentid", $table);
        $this->execute(true);
    }

    function setup_columns()
    {
        extract($this->var);

        $objGrid->keyfield("id");
        $objGrid->searchby("name");
        $objGrid->orderby("id", "desc");

        $objGrid->buttons(false,true,false,false,-1,"");


        $objGrid->FormatColumn("id","ID",                           "0", "50", 1, "1", "center", "text");
        $objGrid->FormatColumn("remarks","Remarks",                 "0", "50", 1, "1", "left", "text");

        $objGrid->FormatColumn("so_no","SO#",                       "0", "50", 1, "1", "left", "text");
        $objGrid->FormatColumn("chargeback_status_id","Status",     "0", "50", 1, "1", "left", "selected:select * from lookup_chargeback_status");

        $objGrid->FormatColumn("chargeback_reason","Reason",        "0", "50", 1, "30", "left", "text");

        $objGrid->FormatColumn("chargeback_remark_id","Remarks",    "0", "50", 1, "20", "left", "selected:select * from lookup_chargeback_remark");
        $objGrid->FormatColumn("chargeback_remark","Order Notes",   "0", "50", 1, "30", "left", "text");

        $objGrid->FormatColumn("modify_by","Modified By",           "0", "50", 1, "10", "left", "text");
        $objGrid->FormatColumn("modify_on","Date",                  "0", "50", 1, "10", "left", "text");


        $objGrid->chField("_margin", "R", true);
        $objGrid->chField("_shipping_cost", "R", true);
        $objGrid->chField("_supplier_cost", "R", true);
        $objGrid->chField("_cost", "R", true);

        $objGrid->total("OnlineOrders,OfflineOrders");
    }

    function execute_custom_sql()
    {
        return;
        extract($this->var);

        // this is the correct way to count children, use GROUP BY and inner join
        // instead of using a sub-query
        $query =
        "
            select
                sum(if(biz_type = 'ONLINE' or biz_type = 'MOBILE' or biz_type = 'QOO10' or biz_type = 'EBAY', IFNULL(qty,0), 0)) OnlineOrders,
                sum(if(biz_type = 'OFFLINE', IFNULL(qty,0), 0)) OfflineOrders,
                so.so_no,
                SI.qty,
                so.platform_id,
                concat(cm.competitor_name, ' ', ifnull(cm.note_1,'')) Competitor,
                so.currency_id,
                cm.now_price CompetitorPrice,
                px.price OurPrice,
                (cm.now_price - px.price) Difference,
                px.listing_status,
                so.biz_type,
                si.prod_sku,

                cm.comp_ship_charge _shipping_cost,

                pp.clearance,
                si.prod_name
                #pp.prod_name
            from so
            inner join so_item si           on si.so_no = so.so_no and so.status > 2
            inner join sku_mapping sm       on sm.sku = si.prod_sku and sm.status = 1 and sm.ext_sys = 'wms'
            inner join price px             on px.sku = si.prod_sku and px.platform_id = so.platform_id
            inner join product pp           on pp.sku = si.prod_sku

            inner join platform_biz_var pbv on pbv.selling_platform_id = so.platform_id

            #left join competitor_map cm on cm.ext_sku = sm.ext_sku
            #left join competitor c on c.id = cm.competitor_id and c.country_id = pbv.platform_country_id
            left join
            (
                select
                    c.country_id, cm.note_1, cm.now_price, cm.comp_ship_charge, cm.ext_sku, c.competitor_name
                from competitor_map cm
                inner join competitor c on cm.competitor_id = c.id and cm.status = 1 and cm.match = 1
                order by cm.now_price
            ) cm on cm.ext_sku = sm.ext_sku and cm.country_id = pbv.platform_country_id
        ";

        if ($where == "") $where = $_SESSION["where"];
        $tmp = "1 and (so.biz_type = 'ONLINE' or so.biz_type = 'OFFLINE' or so.biz_type = 'MOBILE' or biz_type = 'QOO10' or biz_type = 'EBAY') $where";

        // echo "<pre>"; var_dump($query); var_dump($tmp); die();

        $objGrid->where($tmp);
        $objGrid->groupby("so.platform_id, prod_sku");

        $objGrid->sqlstatement($query);
    }

    function create_criteria_from_post()
    {
        $query = "";
        foreach ($_POST as $k=>$v)
        {
            if (!empty($v))
            {
                $kk = substr($k, 4);
                switch ($kk)
                {
                    // platforms
                    case 1:
                        $tmp = null;
                        foreach ($v as $values)
                            if ($values != "") $tmp[] = " so.platform_id = '$values' ";

                        $joined = implode(" or ", $tmp);
                        if ($joined != "") $query .= " and ($joined)";
                        break;

                    // local sku
                    case 6:     $query .= " and si.prod_sku = '$v' ";               break;

                    case 8:     $v = str_replace(" ", "%", $v);
                                $query .= " and si.prod_name like '%$v%' ";         break;
                    case 9:     $query .= " and pp.clearance = '$v' ";              break;

                    // master sku
                    case 39:    $query .= " and sm.ext_sku = '$v' ";                break;
                    case 51:
                                $d = date_parse($v);
                                $dd = "{$d["year"]}-{$d["month"]}-{$d["day"]}";
                                $query .= " and so.order_create_date >= '$dd' ";                break;
                    case 52:
                                $d = date_parse($v);
                                $dd = "{$d["year"]}-{$d["month"]}-{$d["day"]}";
                                $query .= " and so.order_create_date <= '$dd' ";                break;
                }
            }
        }

        return $query;
    }

    function ajax_handler()
    {
        extract($this->var);

        $param = explode(";", $objGrid->getAjaxID());
        switch ($param[0])
        {
            case "clone":
                #echo "<script>alert('$table')</script>";
                // $strSQL = sprintf("INSERT INTO $table (skugroupid) values ($parentid)");
                #mysql_query_teik("inserting to $table", $strSQL, true, true);
                // $arrData = $objGrid->SQL_query($strSQL);
                #$skugroupid = mysql_insert_id();
                #$strSQL = sprintf("INSERT INTO cps_sku_individual (skugroupid, versionid, colorid, preferredsuppplierid, isactive) values ('$skugroupid',-1,-1,-1,2)");
                #$strSQL = "INSERT INTO cps_sku_individual (skugroupid, versionid, colorid, isactive) values ('$skugroupid',-1,-1,2)";
                #mysql_query_teik("inserting to cps_sku_individual", $strSQL, true, true);
                // echo "<script>alert('" . $objGrid->getAjaxID() . "')</script>";
                break;
        }

        $this->var["executeaftergrid"] = $executeaftergrid;

        switch ($objGrid->getAjaxID())
        {
            case DG_IsDelete: // case 3:    // Delete Rowa / Borrar Registro
                break;

            case 4: // updated
                // echo "<script>alert('$query');</script>";
                // echo "<script>DG_Do('','&e_id={$objGrid->gridid}');</script>";

                $data                           = $objGrid->getEditedData();

                $t1 = floatval($data["data"]);
                $t2 = floatval(substr($data["data"], 3));

                if ($t1 != 0)
                {
                    $_SESSION["supplier_cost"]     = $t1;
                    $_SESSION["supplier_currency"] = "HKD";
                }
                else
                    if ($t2 != 0)
                    {
                        $_SESSION["supplier_cost"]     = $t2;
                        $_SESSION["supplier_currency"] = substr($data["data"], 0, 3);
                    }




                // list($value, $keyValue) = explode(".-.", $objGrid->dgrtd);
                // $idLen  = strlen($objGrid->dgGridID);
                // $keyLen = strlen($value);
                // $value  = substr($value, 0, ($keyLen - $idLen));

                // echo "<script>DG_Do('');</script>";
                // echo $objGrid->getFieldData($value, $keyValue, "-");
                // echo "
                // <script>
                //  obj = document.getElementById('{$objGrid->dgrtd}').parentNode;
                //  cls = obj.className.split(' ');
                //  obj.className = cls[0]+' {$claux}';
                // </script>";
                // die();
                echo "<script>DG_Do('', '{$objGrid->dgrtd}');</script>";
                $objGrid->bypass_ajax_error = true;

                break;
        }
    }

    function process_row_data($arrData = array())
    {
        extract($this->var);

        // http://admincentre.valuebasket.com/marketing/pricing_tool_website/get_profit_margin_json/WEBGB/11774-AA-BK/189.08/120
        include_once APPPATH."libraries/service/price_website_service.php";
        include_once APPPATH."libraries/service/price_ebay_service.php";
        include_once APPPATH."libraries/service/price_qoo10_service.php";
        include_once APPPATH."libraries/service/exchange_rate_service.php";

        $ps["website"] = new price_website_service();
        $ps["ebay"] = new price_ebay_service();
        $ps["qoo10"] = new price_qoo10_service();
        $e = new exchange_rate_service();

        #$jj = $this->price_service->get_profit_margin_json($platform_id, $sku, 0, -1,false);

        $supplier_cost = -1;
        if ($_SESSION["supplier_cost"]) $supplier_cost = $_SESSION["supplier_cost"];

        foreach($arrData as $key=>$row)
        {
            $sc = -1;
            if ($supplier_cost != -1)
            {
                // $r = $e->get_exchange_rate($_SESSION["supplier_currency"], $row["currency_id"]);
                $r = $e->get_exchange_rate($_SESSION["supplier_currency"], $row["currency_id"]);
                $sc = $supplier_cost * $r->get_rate();
            }

            #en: Prepare new field value
            // $row['created_at'] .= " · " . time_since(strtotime($row['created_at']));
            // $row['updated_at'] .= " · " . time_since(strtotime($row['updated_at']));
            // $row['updated_at'] = time_since(strtotime($row['updated_at']));
            // $row['utccorrectasof'] = time_since(strtotime($row['utccorrectasof']));
            if (substr($row["platform_id"], 0, 3) == "WEB")
                $json = $ps["website"]->get_profit_margin_json($row["platform_id"], $parentid, $row["OurPrice"], $sc);
            if (substr($row["platform_id"], 0, 4) == "EBAY")
                $json = $ps["ebay"]->get_profit_margin_json($row["platform_id"], $parentid, $row["OurPrice"], $sc);
            if (substr($row["platform_id"], 0, 5) == "QOO10")
                $json = $ps["qoo10"]->get_profit_margin_json($row["platform_id"], $parentid, $row["OurPrice"], $sc);

            $d                     = json_decode($json, true);
            $row["_margin"]        = $d["get_margin"];
            // $row["_shipping_cost"] = $d["get_logistic_cost"];
            $row["_supplier_cost"] = $d["get_supplier_cost"];
            // var_dump($_SESSION["supplier_currency"]); die();

            $arrTmpData[$key] = $row;
        };
        return $arrTmpData;
    }
}