<?php defined('BASEPATH') OR exit('No direct script access allowed');

load_class('MY_Datagrid', FALSE);

class Sales_trend_report_1 extends gridpage
{
	function __construct()
	{
		global $_dbprefix;
		parent::__construct("Competitors for this product", "so_item");
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

		$this->create_criteria_from_post();

		$parentid = $this->link_parent("sm.sku", "Group ID", true);

		// override with search handler
		$this->config("Competitors for SKU#$parentid", $table);
		$this->execute(true);
	}

	function setup_columns()
	{
		extract($this->var);

		$objGrid->keyfield("prod_sku");
        $objGrid->searchby("name");
        $objGrid->orderby("items", "desc");

		#sbf 3762 remove buttons
		$objGrid->buttons(false,false,false,false,-1);

		$link = array(
			"1 == 1" => "<a href='/managesku?f=vendorid&match=exact&s=['id']'>['items']</a>"
		);

		// $objGrid->FormatColumn("prod_sku","Local SKU", 				"0", "50", 0, "1", "right", "text");
		$objGrid->FormatColumn("platform_id","Platform",				"0", "50", 1, "1", "center", "text");
		$objGrid->FormatColumn("Competitor","Competitor", 				"0", "50", 1, "5", "right", "text");
		$objGrid->FormatColumn("_shipping_cost","Comp. Shipping Cost", 	"0", "50", 1, "1", "right", "text");
		$objGrid->FormatColumn("CompetitorPrice","Theirs", 				"0", "50", 1, "1", "left", "text");
		$objGrid->FormatColumn("OurPrice","Ours", 						"0", "50", 1, "1", "left", "text");
		$objGrid->FormatColumn("Difference","Diff.", 					"0", "50", 1, "1", "left", "text");
		$objGrid->FormatColumn("_margin","Margin", 						"0", "50", 1, "1", "center", "text");
		$objGrid->FormatColumn("_supplier_cost","Supplier Cost1",		"0", "50", 0, "1", "center", "text");
		$objGrid->FormatColumn("listing_status","Status", 				"0", "50", 1, "1", "center", "text");
		$objGrid->FormatColumn("OnlineOrders","Online", 				"0", "50", 1, "1", "center", "text");
		$objGrid->FormatColumn("OfflineOrders","Offline", 				"0", "50", 1, "200", "center", "text");
		#$objGrid->FormatColumn("prod_name","Name", 					"0", "50", 0, "600", "left", "text");

		// $objGrid->FormatColumn("listing_status","Status", 			"0", "50", 0, "1", "right", "text");
		// $objGrid->FormatColumn("ext_sku","Master SKU", 				"0", "50", 0, "1", "right", "text");
		// $objGrid->FormatColumn("name","Name", 						"0", "50", 0, "60", "left", "text");
		// $objGrid->FormatColumn("clearance","Clearance", 			"0", "50", 0, "100", "center", "text");
		$objGrid->chField("_margin", "R", true);
		$objGrid->chField("_shipping_cost", "R", true);
		$objGrid->chField("_supplier_cost", "R", true);
		$objGrid->chField("_cost", "R", true);

		$objGrid->total("OnlineOrders,OfflineOrders");
	}

	function execute_custom_sql()
	{
 		extract($this->var);

 		// sbf #3762
 		// needs a huge nested subquery for competitors to get the lowest price AND not mess up the so count
 		// - otherwise it will mess with the group by clause unintentionally (no. of competitors x qty = inflated so count)
		$query =
		"
			select
				sum(if(so.biz_type IN ('ONLINE', 'MOBILE', 'QOO10', 'EBAY'), IFNULL(qty,0), 0)) OnlineOrders,
				sum(if(so.biz_type = 'OFFLINE', IFNULL(qty,0), 0)) OfflineOrders,
				so.so_no,
				SI.qty,
				pbv.selling_platform_id AS platform_id,
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
			FROM platform_biz_var pbv

			left join sku_mapping sm 	on sm.status = 1 and sm.ext_sys = 'wms'
			left join price px 			on px.sku = sm.sku and px.platform_id = pbv.selling_platform_id
			left join product pp 		on pp.sku = sm.sku

			left join so_item si 		on si.prod_sku = sm.sku
			left join so 				on pbv.selling_platform_id = so.platform_id and si.so_no = so.so_no
			and so.status > 2
			and so.biz_type IN ('ONLINE', 'OFFLINE', 'MOBILE', 'QOO10', 'EBAY') $on

			left join (
				select cm.comp_ship_charge, cm.note_1, cm.now_price, sm.sku, c.country_id, c.competitor_name from

				competitor_map cm
				inner join competitor c on cm.competitor_id = c.id and cm.status = 1 and cm.match = 1
				inner join (
					SELECT
					cm.ext_sku,
					cm.competitor_id,
					Min(cm.now_price) price,
					c.*
					FROM
					competitor_map cm
					INNER JOIN competitor c ON cm.competitor_id = c.id
					inner join sku_mapping sm on sm.ext_sku = cm.ext_sku and cm.status = 1 and cm.match = 1 $sku
					GROUP BY
					c.country_id,
					cm.ext_sku
				) test on test.price = cm.now_price and test.country_id = c.country_id
				inner join sku_mapping sm on sm.ext_sku = cm.ext_sku $sku
				group by country_id
			) cm on cm.country_id = pbv.platform_country_id
        ";

		// echo "<pre>"; var_dump($query, $where, $on); die();

		$count = "select count(*) from platform_biz_var $where";

		$objGrid->where('1 ' . $where);
		$objGrid->groupby("pbv.selling_platform_id");
		$objGrid->orderby("pbv.selling_platform_id");
		$objGrid->sqlstatement($query, $count);
	}

	function create_criteria_from_post()
    {
    	$where = $on = $sku = "";
    	foreach ($_POST as $k=>$v)
		{
        	if (!empty($v))
        	{
				$kk = substr($k, 4);
	            switch ($kk)
	            {
	            	// platforms
					case 1:
						if (is_array($v) &&count($v) > 0) {
							$where .= " AND pbv.selling_platform_id IN ('" . implode("','", $v) . "')";
						}
						break;

	            	// local sku
					case 6:		$on .= " and sm.sku = '$v' ";
								$sku .= " and sm.sku = '$v' ";				break;

					case 8:		$v = str_replace(" ", "%", $v);
								$on .= " and si.prod_name like '%$v%' ";			break;
					case 9:		$on .= " and pp.clearance = '$v' ";				break;

					// master sku
					case 39: 	$on .= " and sm.ext_sku = '$v' ";				break;
					case 51:
								$d = date_parse($v);
								$dd = "{$d["year"]}-{$d["month"]}-{$d["day"]}";
								$on .= " and so.order_create_date >= '$dd' ";				break;
					case 52:
								$d = date_parse($v);
								$dd = "{$d["year"]}-{$d["month"]}-{$d["day"]}";
								$on .= " and so.order_create_date <= '$dd' ";				break;
	            }
	        }
		}

		$this->var['where'] = $where;
		$this->var['on'] = $on;
		$this->var['sku'] = $sku;
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
			case DG_IsDelete: // case 3:	// Delete Rowa / Borrar Registro
				break;

			case 4: // updated
				// echo "<script>alert('$query');</script>";
				// echo "<script>DG_Do('','&e_id={$objGrid->gridid}');</script>";

				$data                      		= $objGrid->getEditedData();

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
				// 	obj = document.getElementById('{$objGrid->dgrtd}').parentNode;
				// 	cls = obj.className.split(' ');
				// 	obj.className = cls[0]+' {$claux}';
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