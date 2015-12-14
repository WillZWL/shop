<?php
namespace ESG\Panther\Service;

class AdminProductFeedService extends DataFeedService
{
    protected $id = "Admin Product Feed";
    private $filename;
    private $filepath;

    public function __construct()
    {
        parent::__construct();
        $this->setOutputDelimiter(',');
    }

    public function genDataFeed($platform_type = NULL)
    {
        set_time_limit(900);
        define('DATAPATH', $this->getDao('Config')->valueOf("data_path"));
        $this->filename = "admin_product_feed.csv";
        $this->filepath = DATAPATH . "feeds/admin/";
        $data_feed = $this->getDataFeed(TRUE, $platform_type);
        if ($data_feed === TRUE) {
            $this->sendReport();
        } else {
            $subject = "Failed to create Admin Product Feed File";
            $message = "FILE: " . __FILE__ . "\r\nLINE: " . __LINE__;
            $this->sendNotificationEmail($subject, $message);
        }
    }

    public function getDataFeed($first_line_headling = TRUE, $platform_type = NULL)
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        $list = $this->genDataList();
        if (!isset($platform_type)) {
            $this->delDir($this->filepath);
        }
        $fp = fopen($this->filepath . $this->filename, 'w');
        $new_list = $platformlist = [];
        $report_data = $content = "";
        if ($allow_sell_list = $this->getService('SellingPlatform')->getPlatformListWithAllowSellCountry("WEBSITE")) {
            foreach ($allow_sell_list as $key => $value) {
                if (strpos($value->getSellingPlatformId(), "WEB") !== FALSE) {
                    $platformlist[] = $value->getSellingPlatformId();
                }
            }
        } else {
            $message = __LINE__ . " Could not retrieve selling platform list.";
            $this->sendNotificationEmail("DB Error", $message);
            echo "ERROR $message";
            return;
        }
        $j = 0;
        $totalrow = count($list);
        foreach ($list as $res) {
            $report_header = "";
            $price_margin_arr = [];
            if ($res) {
                ob_end_clean();
                $price = explode(',', $res["price"]);
                $platform_id_arr = explode(',', $res["platform_id"]);
                $ship_day = explode(',', $res["ship_day"]);
                $delivery_day = explode(',', $res["delivery_day"]);
                $price_country_arr = [];
                unset($res['price']);
                unset($res['platform_id']);
                unset($res['ship_day']);
                unset($res['delivery_day']);
                // this loop below proccesses price, margin, ship and delivery days at single SKU level
                for ($i = 0; $i < count($price); $i++) {
                    if ($platform_id = $platform_id_arr[$i]) {
                        $price_country_arr = array('price' => $price[$i], 'platform_id' => $platform_id);
                        if ($profit_margin = $this->getMargin($platform_id, $res["sku"], $price[$i])) {
                            $price_margin_arr[$platform_id]["margin"] = $profit_margin;
                            $price_margin_arr[$platform_id]["price"] = $price_country_arr['price'];
                            $price_margin_arr[$platform_id]["ship_day"] = $ship_day[$i];
                            $price_margin_arr[$platform_id]["delivery_day"] = $delivery_day[$i];
                        } else {
                            $price_margin_arr[$platform_id]["margin"] = " ";
                            $price_margin_arr[$platform_id]["price"] = " ";
                            $price_margin_arr[$platform_id]["ship_day"] = " ";
                            $price_margin_arr[$platform_id]["delivery_day"] = " ";
                        }
                    }
                }
                $website_status = $res["website_status"];
                $res['website_status'] = $this->getWebsiteStatus($website_status);
                $status = $res["status"];
                $res['status'] = $this->getPordStatus($status);

                foreach ($res as $key => $value) {
                    if ($j == 0) {
                        $report_header .= "$key,";
                    }
                    if ($value == "" || $value == NULL) {
                        $value = " ";
                    }
                    $report_data .= str_replace(",", " ", $value) . ",";
                }
                foreach ($platformlist as $id) {
                    if ($j == 0) {
                        $report_header .= "{$id}_price,{$id}_margin,{$id}_ship_day,{$id}_delivery_day,";
                    }
                    if ($price_margin_arr[$id]) {
                        $report_data .= "{$price_margin_arr[$id]["price"]},{$price_margin_arr[$id]["margin"]},{$price_margin_arr[$id]["ship_day"]},{$price_margin_arr[$id]["delivery_day"]},";
                    } else {
                        $report_data .= ",,,,";
                    }
                }
                if ($report_header !== "") {
                    $report_header = trim($report_header, ',') . "\r\n";
                }
                $report_data = trim($report_data, ',') . "\r\n";
                $content .= $report_header . $report_data;
                fwrite($fp, $content);
                $j++;
                $report_header = $report_data = $content = $price_margin_arr = null;
            }
        }
        $this->timestamp = date('Y-m-d H:i:s');
        $summary = "Total Rows, $totalrow \r\nGenerated on,{$this->timestamp} \r\n";
        fwrite($fp, $summary);
        fclose($fp);
        return TRUE;
    }

    protected function genDataList($where = [], $option = [])
    {
        return $this->getDao('Product')->getAdminProductFeedDto(["p.status" => 2], array('limit' => -1, 'platform_type' => $option['platform_type']));
    }

    public function getWebsiteStatus($website_status)
    {
        $website_status_arr = ['I' => 'Instock','O' => 'Outstock','P' => 'Pre-order', 'A' => 'Arriving'];
        if (in_array($website_status, array_keys($website_status_arr))) {
            return $website_status_arr[$website_status];
        } else {
            return '';
        }
    }

    public function getPordStatus($status)
    {
        $prod_status_arr = [0 => 'Inactive', 1 => 'SKU Created', 2 => 'Product Listed'];
        if (in_array($status, array_keys($prod_status_arr))) {
            return $prod_status_arr[$status];
        } else {
            return '';
        }
    }

    private function sendReport()
    {
        $email = [
            'from' => 'Admin <admin@digitaldiscount.net>',
            'to' => ['will.zhang@eservicesgroup.com'],
            'cc' => [''],
            'body' => "Report accurate as of {$this->timestamp}. [admin_product_feed.php]",
            'subject' => '[Export All Product Feed] Feed Attached',
            'attachment' => ''
        ];
        if (strpos($_SERVER["HTTP_HOST"], "dev") === false) {
            return $this->getService('Email')->sendEmail($email);
        } else {
            echo "DONE AT {$this->timestamp}. View file at $report_filepath";
            echo "<pre> $message";
        }
    }

    private function getMargin($platform_id, $sku, $price)
    {
        try {
            if ($json = $this->getService('Price')->getProfitMarginJson($platform_id, $sku, $price)) {
                $arr = json_decode($json, true);
                return $arr["get_margin"];
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            $this->error .= "Cannot retrieve price margin for <$platform_id> - SKU <$sku> - Selling Price <$price> \r\n";
        }
    }

    private function sendNotificationEmail($subject, $error_msg = "")
    {
        mail($this->getContactEmail(), $subject, $error_msg, 'From: itsupport@eservicesgroup.net');
        return;
    }

    protected function getDefaultVo2XmlMapping()
    {
        return '';
    }

    protected function getDefaultXml2CsvMapping()
    {
        return '';
    }
}