<?php defined('BASEPATH') OR exit('No direct script access allowed');

class FreightManagementReport  extends MY_Controller
{
    public $appId = "RPT0049";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($rec="")
    {
        $data = [];
        $data['lang'] = $this->loadParentLang();

        if ($rec) {
            $this->echo_data = false;
            $report = [];
            $objlist = $tempObjlist = [];
            ini_set("memory_limit", "500M");

            $where["so.status >"] = "2";
            $where["so.status <"] = "5";

            $where["so.hold_status"] = "0";
            $where["so.refund_status"] = "0";

            if ($rec == "no_rec") {
                $where["(so.rec_courier = '')"] = null;
            } else if ($rec == "rec") {
                $where["(so.rec_courier != '')"] = null;
            }

            $option["warehouse_id"] = "ES_HK";  #
            $option["orderby"] = "so_no, expect_delivery_date ASC";
            $option["notes"] = 1;
            $option["product_related"] = $renoOption["product_related"] = 1;
            $option["show_so"] = $renoOption["show_so"] = 1;
            $option["limit"] = $this->sc['So']->getDao('So')->db->rows_limit = 1000;
            $data = [];

            // retrieving all results at one go will cause time out/ insufficient memory, so we calculate total count of orders
            $totalOrderRow = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, ["num_rows" => 1]);
            $loop = ceil($totalOrderRow / 1000);
            $i = 0;
            for ($i = 0; $i < $loop; $i++) {
                $option["offset"] = $i * 1000;
                $objlist = $this->sc['So']->getDao('So')->getIntegratedFulfillmentListWithName($where, $option);
                $listArr = (array) $objlist;

                if (empty($data))
                    $data = $listArr;
                else
                    $data = array_merge($data, $listArr);
            }

            if ($data) {
                $report = $this->formatData($data, TRUE);
                $this->outputData($report);
            } else {
                Redirect(base_url()."report/freightManagementReport");
            }

        } else {
            $this->load->view("report/freight_management_report", $data);
        }

    }

    private function loadParentLang()
    {
        $subAppId = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $subAppId . "_" . $this->getLangId() . ".php");

        return $lang;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    private function formatData($objlist = [], $show_header = false)
    {
        $csv = "";
        $reportData = $header = [];
        if (!empty($objlist)) {
            $i = 0;
            foreach ($objlist as $obj) {
                $amount_usd = number_format($obj->getAmount() * $obj->getRate(), 2, '.', '');
                $obj->setAmountUsd($amount_usd);

                $soItemAmountUsd = number_format($obj->getSoItemAmount() * $obj->getRate(), 2, '.', '');
                $obj->setSoItemAmount($soItemAmountUsd);

                $classname = get_class($obj);
                if (!$methods = get_class_methods($classname)) {
                    $this->error = "freight_management_report " . __LINE__ . " Unable to get methods from classname <$classname>";
                    return FALSE;
                }

                // fields that you want to add to report (take variable name in soListWithNameDto)
                // arrange it in order you want your columns
                $wanted = array("SoNo", "Sku", "MasterSku", "ProductName", "CatName", "SubCatName", "Qty", "AmountUsd", "SoItemAmount", "Weight", "DeliveryPostcode", "DeliveryCountryId", "DeliveryState", "RecCourier", "Note");

                foreach ($methods as $method) {
                    if (strpos($method, "get") !== false) {
                        $headername = str_replace("get", "", $method);
                        if (in_array($headername, $wanted)) {
                            $key = array_search($headername, $wanted);
                            if ($i == 0 && $show_header) {
                                $header[$key] = $headername;
                            }

                            $reportData[$i][$key] = str_replace(",", " ", $obj->$method());
                        }
                    }
                }

                if ($header)
                    ksort($header);

                ksort($reportData[$i]);
                $i++;
            }

            array_unshift($reportData, $header);
        }

        return $reportData;

    }

    private function outputData($reportData)
    {
        if (is_array($reportData)) {
            $fp = fopen('php://output', 'w');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename=freight_management_report.csv');

            if ($reportData) {
                foreach ($reportData as $fields) {
                    fputcsv($fp, $fields);
                }
            }
            fclose($fp);
        }
    }
}


