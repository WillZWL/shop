<?php
namespace ESG\Panther\Service;
use PHPMailer;

class ProductAutomationService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function updateProductQty()
    {
        $where['(p.website_quantity = 0 or p.display_quantity = 0)'] = NULL;
        $where['p.clearance'] = 0;
        $where['p.auto_restock'] = 1;
        $where['p.sourcing_status'] = A;
        $option['limit'] = -1;
        $list = $this->getDao('Product')->getUpdateProductQtyList($where, $option);
        if ($list) {
            $batch_remark = "auto_website_qty_".date("YmdHis");
            $func_name = 'auto_website_qty';
            $batch_obj = $this->_getNewBatchObj($func_name, $batch_remark);
            $batch_id = $batch_obj->getId();
            foreach ($list as $key => $value) {
                $prod_obj = $this->getDao('Product')->get(array('sku' => $value->getSku()));
                $item_cost = $value->getItemCost();
                if ($value->getWebsiteQuantity() === 0) {
                    $website_qty = $this->_getAutoWebsiteQtyByItemCost($item_cost);
                    $prod_obj->setWebsiteQuantity($website_qty);
                }
                if ($value->getDisplayQuantity() === 0) {
                    $display_qty = $this->_getAutoDisplayQtyByItemCost($item_cost);
                    $prod_obj->setDisplayQuantity($display_qty);
                }
                $result = $this->getDao('Product')->update($prod_obj);
                if ($result) {
                    $logObj = $this->getDao('AutoRestockLog')->get();
                    $logVo = clone $logObj;
                    $logVo->setBatchId($batch_id);
                    $logVo->setSku($value->getSku());
                    $logVo->setVbSku($value->getVbSku());
                    $logVo->setMasterSku($value->getMasterSku());
                    $logVo->setProdName((String) $value->getProdName());
                    $logVo->setItemCost($item_cost);
                    $logVo->setWebsiteQuantity($website_qty);
                    $logVo->setDisplayQuantity($display_qty);
                    $supply_status_str = $this->_getSourcingStatusStr($value->getSupplyStatus());
                    $logVo->setSupplyStatus((String) $supply_status_str);
                    $this->getDao('AutoRestockLog')->insert($logVo);
                }
            }
            $batch_obj->setStatus('C');
            $this->sendAutoChangeEmail(array('batch_id'=>$batch_id));
            $this->getDao('Batch')->update($batch_obj);
        }
    }

    public function sendAutoChangeEmail($where)
    {
        $option['limit'] = -1;
        $list = $this->getDao('AutoRestockLog')->getList($where, $option);
        if ($list) {
            $csv = $this->genMsg($list);
            $title = "Panther Auto Restock Record ".date("Y-m-d");
            $msg = "This email is for Panther Auto Restock Record, You can view Panther Auto Restock Record by attachment";
            $filename = 'Panther Auto Restock Record'.date("Y-m-d").'.csv';
            $email = 'bd@eservicesgroup.net';
            $this->_sendEmail($email, $title, $msg, $csv, $filename);
        }
    }

    public function genMsg($list)
    {
        $csv = "Master Sku, Sku, Prod Name, Item Cost (HKD), Supply Status, Website Qty\r\n";
        foreach ($list as $key => $value) {
            $prod_name = "\"" .$value->getProdName() ."\"";
            $csv .= $value->getMasterSku() .','. $value->getSku() .','. $prod_name .','. $value->getItemCost() .','. $value->getSupplyStatus() .','. $value->getWebsiteQuantity(). "\r\n";
        }
        return $csv;
    }

    public function _sendEmail($email, $title, $msg, $csv_content, $filename)
    {
        $phpmail = new PHPMailer;
        $phpmail->isMail();
        $phpmail->From = "do_not_reply@eservicesgroup.com";
        $phpmail->FromName = "Panther";
        $phpmail->AddAddress($email);
        $phpmail->AddAddress('will.zhang@eservicesgroup.com');
        $phpmail->Subject = $title;
        $phpmail->IsHTML(false);
        $phpmail->Body = $msg;
        $phpmail->AddStringAttachment($csv_content, $filename);
        $result = $phpmail->Send();
    }

    private function _getNewBatchObj($func_name = 'auto_website_qty', $remark = '')
    {
        $batch = $this->getDao('Batch')->get(array('remark'=>$remark));
        if (empty($batch)) {
            $batch_obj = $this->getDao('Batch')->get();
            $batch_obj->setFuncName($func_name);
            $batch_obj->setStatus('N');
            $batch_obj->setListed('1');
            $batch_obj->setRemark($remark);
            $res = $this->getDao('Batch')->insert($batch_obj);
            if ($res) {
                return $batch_obj;
            } else {
                return false;
                mail('will.zhang@eservicesgroup.com', 'Inset Batch failed', 'Last Sql:'.$this->getDao('Batch')->db->last_query());
            }
        } else {
            return false;
        }
    }

    private function _getAutoWebsiteQtyByItemCost($item_cost_hkd = 0)
    {
        if ($item_cost_hkd < 1400) {
            $qty = 30;
        } elseif ($item_cost_hkd >= 1400 && $item_cost_hkd < 2800) {
            $qty = 20;
        } elseif ($item_cost_hkd >= 2800 && $item_cost_hkd < 5600) {
            $qty = 15;
        } elseif ($item_cost_hkd >= 5600 && $item_cost_hkd < 8400) {
            $qty = 10;
        } elseif ($item_cost_hkd >= 8400) {
            $qty = 5;
        }
        return $qty;
    }

    private function _getAutoDisplayQtyByItemCost($item_cost_hkd = 0)
    {
        if ($item_cost_hkd < 1400) {
            $qty = 24;
        } elseif ($item_cost_hkd >= 1400 && $item_cost_hkd < 2800) {
            $qty = 12;
        } elseif ($item_cost_hkd >= 2800 && $item_cost_hkd < 5600) {
            $qty = 8;
        } elseif ($item_cost_hkd >= 5600 && $item_cost_hkd < 8400) {
            $qty = 4;
        } elseif ($item_cost_hkd >= 8400) {
            $qty = 1;
        }
        $random = mt_rand(0, 5);
        $qty = $qty + $random;
        return $qty;
    }

    private function _getSourcingStatusStr($supply_status)
    {
        $supply_status_arr = array(
                'A' => 'Readily Available',
                'D' => 'Discontinued',
                'O' => 'Temp Out of stock',
                'C' => 'Stock Constraint',
                'L' => 'Last Lot',
                'pr' => 'Pre Order',
            );
        if (array_key_exists($supply_status, $supply_status_arr)) {
            return $supply_status_arr[$supply_status];
        } else {
            return 'Readily Available';
        }
    }
}