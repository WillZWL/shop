<?php
namespace ESG\Panther\Service;

class ManualOrderService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processDataForOnHold($post_data)
    {
        if ($so_obj = $this->getDao('So')->get(["so_no"=>$post_data['so_no']])) {
            if ($post_data['status'] == 2) {
                $platform_obj = $this->getDao('PlatformBizVar')->get(["selling_platform_id"=>$so_obj->getPlatformId()]);
                $so_obj->setExpectDeliveryDate(date("Y-m-d H:i:s", time()+$platform_obj->getLatencyInStock()*86400));
            }
            $so_obj->setStatus($post_data['status']);
            if (!$this->getDao('So')->update($so_obj)) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->getDao('So')->db->display_error();
            } else {
                redirect($_SESSION["LISTPAGE"]);
            }
        } else {
            $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->getDao('So')->db->display_error();
        }
    }

    public function processDataForPending($post_data)
    {
        if ($post_data['type']) {
            if ($so_obj = $this->getDao('So')->get(["so_no"=>$post_data['so_no']])) {
                switch ($post_data['type']) {
                    case "b":
                        $so_obj->setStatus('0');
                        break;
                    case "c":
                        $so_obj->setHoldStatus('1');
                        break;
                    case "p":
                        $so_obj->setStatus('3');
                        break;
                }
                if (!$this->getDao('So')->update($so_obj)) {
                    $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->display_error();
                } else {
                    redirect($_SESSION["LISTPAGE"]);
                }
            } else {
                $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->display_error();
            }
        }
    }
}