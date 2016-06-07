<?php
namespace ESG\Panther\Service;

use PHPMailer;
use ESG\Panther\Service\SellingPlatformService;
use ESG\Panther\Dao\ProductDao;
use ESG\Panther\Dao\DeliveryTimeDao;

class DeliveryTimeService extends BaseService
{
    private $productDao;

    public function __construct()
    {
        parent::__construct();
        $this->sellingPlatformService = new SellingPlatformService;
        $this->setDao(new DeliveryTimeDao);
        $this->setOroductDao(new ProductDao);
    }

    private function setOroductDao($value)
    {
        $this->productDao = $value;
    }

    public function getDeliverytimeList()
    {
        return $this->getDao('DeliveryTime')->getDeliverytimeList();
    }

    public function getDeliveryScenarioList()
    {
        return $this->getDao('DeliveryTime')->getDeliveryScenarioList();
    }

    public function getDeliverytimeObj($ctry_id, $scenarioid)
    {
        return $this->getDao('DeliveryTime')->getDeliverytimeObj($ctry_id, $scenarioid);
    }

    public function bulkUpdateDeliveryScenario($platform_id, $update_list)
    {
        $error_msg = "";
        if ($update_list && $platform_id) {
            foreach ($update_list as $scenarioid => $sku_list) {
                $sku_list = trim($sku_list, ',');
                $result = $this->getDao('DeliveryTime')->bulkUpdateDeliveryScenarioByPlatform($platform_id, $scenarioid, $sku_list);
                if ($result === false) {
                    $error_msg .= __FILE__ . " LINE: " . __LINE__ . " DB error: " . $this->db->_error_message() . "\n Unable to update platform_id<$platform_id>, scenarioid<$scenarioid> for SKU LIST: \n$sku_list <hr></hr>\n";
                }
            }
        }
        if ($error_msg == "") {
            return TRUE;
        } else {
            $this->sendNotificationEmail("update_fail", $error_msg);
            $this->error_msg = $error_msg;
            return FALSE;
        }
    }

    private function getOroductDao()
    {
        return $this->productDao;
    }

    public function checkEmptyFields($value = [])
    {
        if (is_array($value) && !empty($value)) {
            foreach ($value as $scenarioid => $data) {
                if (
                    trim($data["ship_min_day"]) !== "" ||
                    trim($data["ship_max_day"]) !== "" ||
                    trim($data["del_min_day"]) !== "" ||
                    trim($data["del_max_day"]) !== "" ||
                    trim($data["margin"]) !== ""
                ) {
                    $data_exists = TRUE;
                }
            }

            $success = true;
            if ($data_exists) {
                foreach ($value as $scenarioid => $data) {
                    if (array_search('', $data) !== false) {
                        $success = false;
                    }
                }
            }
            return $success;
        }
        return false;
    }

    public function sendNotificationEmail($type, $msg = "")
    {
        $phpmail = new PHPMailer;
        $phpmail->CharSet = "UTF-8";
        $phpmail->IsSMTP();
        if ($smtphost = $this->getDao('Config')->valueOf("smtp_host")) {
            $phpmail->Host = $smtphost;
            $phpmail->SMTPAuth = $this->getDao('Config')->valueOf("smtp_auth");
            $phpmail->Username = $this->getDao('Config')->valueOf("smtp_user");
            $phpmail->Password = $this->getDao('Config')->valueOf("smtp_pass");
        }
        $phpmail->From = "admin@digitaldiscount.co.uk";
        $phpmail->FromName = "Admin";
        $phpmail->AddAddress("csmanager@eservicesgroup.net");
        $phpmail->AddAddress("itsupport@eservicesgroup.net");
        $phpmail->IsHTML(false);
        switch ($type) {
            case "CHG":
                $message = $msg;
                $title = "NOTICE - Delivery time frames have been changed.";
                break;
        }
        $phpmail->Subject = $title;
        $phpmail->Body = $message;

        if (strpos($_SERVER['HTTP_HOST'], 'dev') === FALSE) {
            $result = $phpmail->Send();
        }
    }
}
