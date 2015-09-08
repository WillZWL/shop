<?php
namespace AtomV2\Service;

use PHPMailer;
use AtomV2\Service\SellingPlatformService;
use AtomV2\Dao\ProductDao;
use AtomV2\Dao\DeliveryTimeDao;

class DeliverytimeService extends BaseService
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
        return $this->getDao()->getDeliverytimeList();
    }

    public function getDeliveryScenarioList()
    {
        return $this->getDao()->getDeliveryScenarioList();
    }

    public function getDeliverytimeObj($ctry_id, $scenarioid)
    {
        return $this->getDao()->getDeliverytimeObj($ctry_id, $scenarioid);
    }

    public function bulkUpdateDeliveryScenario($platform_id, $update_list)
    {
        $error_msg = "";
        if ($update_list && $platform_id) {
            // $update_list must be in format of 'sku1','sku2','sku3',...
            foreach ($update_list as $scenarioid => $sku_list) {
                $sku_list = trim($sku_list, ',');
                $result = $this->getDao()->bulkUpdateDeliveryScenarioByPlatform($platform_id, $scenarioid, $sku_list);

                if ($result === false) {
                    $error_msg .= __FILE__ . " LINE: " . __LINE__ . " DB error: " . $this->db->_error_message() . "\n Unable to update platform_id<$platform_id>, scenarioid<$scenarioid> for SKU LIST: \n$sku_list <hr></hr>\n";
                }
            }

        }

        if ($error_msg == "") {
            return TRUE;
        } else {
            $this->send_notification_email("update_fail", $error_msg);
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

        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";

        switch ($type) {
            case "CHG":
                $message = $msg;
                $title = "NOTICE - Delivery time frames have been changed.";
                break;
        }

        $phpmail->AddAddress("csmanager@eservicesgroup.net");
        $phpmail->AddAddress("itsupport@eservicesgroup.net");
        $phpmail->Subject = "$title";
        $phpmail->IsHTML(false);
        $phpmail->Body = $message;

        if (strpos($_SERVER['HTTP_HOST'], 'dev') === FALSE) {
            $result = $phpmail->Send();
        }
    }
}


