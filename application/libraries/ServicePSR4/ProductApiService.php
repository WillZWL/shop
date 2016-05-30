<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\BatchService;
use ESG\Panther\Dao\InterfaceSkuInfoDao;
use ESG\Panther\Dao\SkuMappingDao;
use ESG\Panther\Dao\ProductDao;
use ESG\Panther\Dao\ProductHistorySyncDao;
use ESG\Panther\Dao\SupplierProdDao;
use ESG\Panther\Dao\SupplierDao;

class ProductApiService extends BaseService
{
    private $interface_sku_dao;
    private $sku_mapping_dao;
    private $history_sync_dao;
    private $supplier_prod_dao;
    private $supplier_dao;

    const SCHEDULE_ID= "PUSH_SKU_MAPPING_TO_CPS";
    const PUSH_CPS_URL = 'http://wms.eservicesgroup.net/cron/sync/panther_sku_mapping';

    public function __construct()
    {
        parent::__construct();
        $this->batchService = new BaseService;
        $this->setInterfaceSkuDao(new InterfaceSkuInfoDao);
        $this->setSkuMappingDao(new SkuMappingDao);
        $this->setDao(new ProductDao);
        $this->setHistorySyncDao(new ProductHistorySyncDao);
        $this->setSupplierProdDao(new SupplierProdDao);
        $this->setSupplierDao(new SupplierDao);
    }

    public function batchInsertInterfaceSkuInfo($xml)
    {
        $xmlobj = simplexml_load_string($xml, 'SimpleXMLElement');
        $cps_status = $xmlobj->status;
        if ($cps_status) {
            if (!empty($xmlobj->skus)) {
                $batch_remark = "cps_sku_sync_".date("YmdHis");
                $func_name = 'cps_sku_sync';
                $batch_obj = $this->getNewBatchObj($func_name, $batch_remark);
                if ($batch_obj) {
                    $batch_id = $batch_obj->getId();
                    $sku_list = $xmlobj->skus->sku;
                    $cps_batch_id = $xmlobj->batch_id;
                    foreach ($sku_list as $sku) {
                        $res = $this->insertInterfaceSkuInfo($sku, $batch_id, $cps_batch_id);
                        if (!$res) {
                            $res_arr = array('result'=>false,'reason'=>"[Panther] Sync Data to Interface table failed, Batch_id :$batch_id");
                        }
                    }
                    $res_arr = array('result'=>ture, 'batch_id'=>$batch_id);
                } else {
                    $res_arr = array('result'=>false, 'reason'=>'Get new batch failed');
                }
            }
        } else {
            $res_arr = array('result'=>false, 'reason'=>'CPS return FALSE');
        }
        return $res_arr;
    }

    public function getNewBatchObj($func_name, $remark)
    {
        $batch = $this->batchService->getDao('Batch')->get(array("remark"=>$remark));
        if (empty($batch)) {
            $batch_obj = $this->batchService->getDao('Batch')->get();
            $batch_obj->setFuncName($func_name);
            $batch_obj->setStatus("N");
            $batch_obj->setListed("1");
            $batch_obj->setRemark($remark);
            $this->batchService->getDao('Batch')->insert($batch_obj);
            return $batch_obj;
        } else {
            return false;
        }
    }

    public function checkInterfaceSkuInfoData($batch_id)
    {
        $sku_list = $this->getInterfaceSkuDao()->getList(array('batch_id'=>$batch_id),array('limit'=>-1));
        if ($sku_list) {
            foreach ($sku_list as $sku) {
                $mastersku_cached = $sku->getMasterskuCached();
                $sku_mapping_info = $this->getSkuMappingDao()->get(array("LPAD(ext_sku,12,'0')"=>$mastersku_cached, "ext_sys"=>'WMS', "status"=>1));
                if ($sku_mapping_info === false or empty($sku_mapping_info)) {
                    $sku->setStatus('F');
                    $reason = $mastersku_cached." not in Panther sku_mapping table";
                    $sku->setFailedReason((string)$reason);
                } else {
                    $prod_sku = $sku_mapping_info->getSku();
                    $master_sku = $sku_mapping_info->getExtSku();
                    $sku->setProdSku((string)$prod_sku);
                    $sku->setMasterSku((string)$master_sku);
                    $product_obj = $this->getDao('Product')->get(array('sku'=>$prod_sku));
                    if ($product_obj === false) {
                        $sku->setStatus('F');
                        $reason = $master_sku." not in Panther product table";
                        $sku->setFailedReason($reason);
                    } else {
                        $sku->setStatus('R');
                    }
                }
                $res = $this->getInterfaceSkuDao()->update($sku, array('mastersku_cached'=>$mastersku_cached));
                if ($res === false) {
                    $error = $this->getInterfaceSkuDao()->db->last_query();
                    mail("will.zhang@eservicesgroup.com", "Sync Sku update interface table failed", "ERROR:\r\n $error");
                }
            }
        }
    }

    public function insertInterfaceSkuInfo($sku, $batch_id, $cps_batch_id)
    {
        $interface_sku_obj = $this->getInterfaceSkuDao()->get();
        $interface_sku_vo = clone $interface_sku_obj;
        $interface_sku_vo->setBatchId($batch_id);
        $interface_sku_vo->setCpsBatchId((int)$cps_batch_id);
        $interface_sku_vo->setMasterskuCached((string)$sku->mastersku_cached);
        $interface_sku_vo->setPricehkd((double)$sku->pricehkd);
        $interface_sku_vo->setPrice((double)$sku->price);
        $interface_sku_vo->setCurrencyId((string)$sku->currencyid);
        $interface_sku_vo->setRegion((string)$sku->region);
        $interface_sku_vo->setLocation((string)$sku->location);
        $interface_sku_vo->setMoq((int)$sku->moq);
        $interface_sku_vo->setLeadDays((int)$sku->lead_days);
        $interface_sku_vo->setLangRestricted((string)$sku->lang_restricted);
        $interface_sku_vo->setComments((string)$sku->comments);
        $interface_sku_vo->setSurplusQty((int)$sku->surplus_qty);
        $interface_sku_vo->setSupplyStatus((int)$sku->supply_status);
        $interface_sku_vo->setStatus('N');
        $result = $this->getInterfaceSkuDao()->insert($interface_sku_vo);
        return $result;
    }

    public function updateSkuInfo($batch_id ='')
    {
        $sku_list = $this->getInterfaceSkuDao()->getList(array('batch_id'=>$batch_id, 'status'=>'R'), array('limit'=>-1));
        if ($sku_list) {
            $i = $j = 0;
            foreach ($sku_list as $sku_obj) {
                $prod_sku = $sku_obj->getProdSku();
                $origin_country = $sku_obj->getRegion();
                $supplier_id = $this->getSupplierId($origin_country);
                $product_obj = $this->getDao('Product')->get(array('sku'=>$prod_sku));

                $sup_prod_obj = $this->getSupplierProdDao()->get(array('supplier_id'=>$supplier_id, 'prod_sku'=>$prod_sku));
                $sup_prod_arr = (array)$sup_prod_obj;
                if ($sup_prod_obj === false or empty($sup_prod_arr)) {
                    $sup_prod_obj = $this->insertSupplierProd($supplier_id, $sku_obj);
                }
                $this->saveHistorySync($product_obj, $sup_prod_obj, $batch_id);
                $res_sup_prod = $this->syncDataToSuppierProd($sku_obj, $sup_prod_obj);
                $res_prod = $this->syncDataToProduct($sku_obj, $product_obj);
                if ($res_prod === false or $res_sup_prod === false) {
                    $j++;
                    $error = $this->getDao('Product')->db->last_query();
                    mail('will.zhang@eservicesgroup.com', '[Panther] Sync Sku Data failed', "Batch ID: $batch_id\r\n Prod Sku: $prod_sku\r\n ERROR:\r\n $error");
                    $sku_obj->setStatus('F');
                } else {
                    $sku_obj->setStatus('S');
                }
                $this->getInterfaceSkuDao()->update($sku_obj, array('prod_sku'=>$prod_sku,'batch_id'=>$batch_id));
                $i++;
            }
            $batch_obj = $this->batchService->getDao('Batch')->get(array('id'=>$batch_id));
            if (($j<$i && $j>0)) {
                $batch_obj->setStatus('CE');
            } elseif ($j=$i && $j>0) {
                $batch_obj->setStatus('F');
            } else {
                $batch_obj->setStatus('C');
            }
            $end_time = date('Y-m-d H:i:s');
            $batch_obj->setEndTime($end_time);
            $this->batchService->getDao('Batch')->update($batch_obj);
        }
    }

    public function saveHistorySync($product_obj, $sup_prod_obj, $batch_id)
    {
        $history_sync_obj = $this->getHistorySyncDao()->get();
        $history_sync_vo = clone $history_sync_obj;
        $history_sync_vo->setBatchId($batch_id);
        $history_sync_vo->setSku($product_obj->getSku());
        $history_sync_vo->setQuantity($product_obj->getQuantity());
        $history_sync_vo->setLangRestricted($product_obj->getLangRestricted());
        $history_sync_vo->setCurrencyId((string)$sup_prod_obj->getCurrencyId());
        $history_sync_vo->setCost((double)$sup_prod_obj->getCost());
        $history_sync_vo->setLeadDay((int)$sup_prod_obj->getLeadDay());
        $history_sync_vo->setMoq((int)$sup_prod_obj->getMoq());
        $history_sync_vo->setSupplyStatus((string)$product_obj->getSourcingStatus());
        $history_sync_vo->setWebsiteStatus((string)$product_obj->getWebsiteStatus());
        return $this->getHistorySyncDao()->insert($history_sync_vo);
    }

    public function syncDataToProduct($sync_obj, $product_obj)
    {
        $lang_restricted = $sync_obj->getLangRestricted();
        $lang_restricted = (array)json_decode($lang_restricted);
        $lang_restricted = $this->langArrToBit($lang_restricted);
        $product_obj->setLangRestricted($lang_restricted);
        $quantity = $sync_obj->getSurplusQty();
        $product_obj->setSurplusQuantity($quantity);
        $sourcing_status = $this->statusIntToStr((int)$sync_obj->getSupplyStatus());
        $product_obj->setSourcingStatus((string)$sourcing_status);
        $origin_website_status = $product_obj->getWebsiteStatus();
        //9847 Surplus Qty Website Status
        if ($quantity > 0) {
            $product_obj->setDisplayQuantity($quantity);
            $product_obj->setWebsiteQuantity($quantity);
        }

        //Website Status Automation
        $website_status = $this->autoWebsiteStatus($sourcing_status, $quantity, $origin_website_status);
        if (!empty($website_status) && ($website_status != $origin_website_status)) {
            $product_obj->setWebsiteStatus($website_status);
        }

        $result = $this->getDao('Product')->update($product_obj);
        return $result;
    }

    public function syncDataToSuppierProd($sync_obj, $sup_prod_obj)
    {
        $prod_sku = $sync_obj->getProdSku();
        $prodlist =$this->getSupplierProdDao()->getList(array('prod_sku'=>$prod_sku), array('limit'=>-1));
        foreach ($prodlist as $prod_obj) {
            $prod_obj->setOrderDefault(0);
            $this->getSupplierProdDao()->update($prod_obj, array('prod_sku'=>$prod_sku));
        }
        $sup_prod_obj->setCurrencyId((string)$sync_obj->getCurrencyId());
        $sup_prod_obj->setCost((double)$sync_obj->getPrice());
        $sup_prod_obj->setPricehkd((double)$sync_obj->getPricehkd());
        $sup_prod_obj->setLeadDay((int)$sync_obj->getLeadDays());
        $sup_prod_obj->setMoq((int)$sync_obj->getMoq());
        $sup_prod_obj->setRegion((string)$sync_obj->getRegion());
        $sup_prod_obj->setLocation((string)$sync_obj->getLocation());
        $sup_prod_obj->setComments((string)$sync_obj->getComments());
        $supply_status = $this->statusIntToStr((int)$sync_obj->getSupplyStatus());
        $sup_prod_obj->setSupplierStatus((string)$supply_status);
        $sup_prod_obj->setOrderDefault(1);
        $result = $this->getSupplierProdDao()->update($sup_prod_obj);
        return $result;
    }

    public function insertSupplierProd($supplier_id, $sku_obj)
    {
        $prod_sku = $sku_obj->getProdSku();
        $prodlist =$this->getSupplierProdDao()->getList(array('prod_sku'=>$prod_sku), array('limit'=>-1));
        foreach ($prodlist as $prod_obj) {
            $prod_obj->setOrderDefault(0);
            $this->getSupplierProdDao()->update($prod_obj, array('prod_sku'=>$prod_sku));
        }
        $sup_prod_obj = $this->getSupplierProdDao()->get();
        $sup_prod_vo = clone $sup_prod_obj;
        $sup_prod_vo->setSupplierId($supplier_id);
        $sup_prod_vo->setProdSku((string)$sku_obj->getProdSku());
        $sup_prod_vo->setCurrencyId((string)$sku_obj->getCurrencyId());
        $sup_prod_vo->setCost((double)$sku_obj->getPrice());
        $sup_prod_vo->setPricehkd((double)$sku_obj->getPricehkd());
        $sup_prod_vo->setLeadDay((int)$sku_obj->getLeadDays());
        $sup_prod_vo->setMoq((int)$sku_obj->getMoq());
        $sup_prod_vo->setOrderDefault('1');
        $supply_status = $this->statusIntToStr((int)$sku_obj->getSupplyStatus());
        $sup_prod_vo->setSupplierStatus((string)$supply_status);
        return $this->getSupplierProdDao()->insert($sup_prod_vo);
    }

    public function getSupplierId($origin_country, $supplierprefix = 'ALN (Do not rename)', $currencyid = 'HKD')
    {
        $suppliername = "$supplierprefix ($origin_country)";
        $sup_obj1 = $this->getSupplierDao()->get(array("name like '$suppliername%'"=>null, "origin_country"=>$origin_country, "status"=>1));
        if ($sup_obj1) {
            $supplier_id = $sup_obj1->getId();
        } else {
            $sup_obj2 = $this->getSupplierDao()->get(array("name like '$suppliername%'"=>null, "status"=>1));
            if ($sup_obj2) {
                $supplier_id = $sup_obj2->getId();
            } else {
                $sup_obj3 = $this->getSupplierDao()->get();
                $sup_vo = clone $sup_obj3;
                $sup_vo->setName((string)$suppliername);
                $sup_vo->setOriginCountry($origin_country);
                $sup_vo->setCurrencyId($currencyid);
                $sup_vo->setSupplierReg('1');
                $sup_vo->setStatus('1');
                $ins_obj = $this->getSupplierDao()->insert($sup_vo);
                $supplier_id = $ins_obj->getId();
            }
        }
        return $supplier_id;
    }

    public function statusIntToStr($status)
    {
        if (in_array($status, array(1, 3, 4, 5, 6))) {
            $status_arr = array(1=>'D', 3=>'O', 4=>'C', 5=>'L', 6=>'A');
            return $status_arr["$status"];
        } else {
            return 'A';
        }
    }

    public function langArrToBit($lang_restricted)
    {
        $lang_osd = 0;
        //0 NA / 1 FR / 2 ES / 3 RU / 4 PL / 5 IT / 6 Nl / 7 PT / 8 SV
        if (in_array(2, $lang_restricted)) {
            $lang_osd = 1;
         } else {
            if ($lang_restricted['fr'] == 1) {
                $lang_osd = $lang_osd | (1 << 1);
            }
            if ($lang_restricted['es'] == 1) {
                $lang_osd = $lang_osd | (1 << 2);
            }
            if ($lang_restricted['ru'] == 1) {
                $lang_osd = $lang_osd | (1 << 3);
            }
            if ($lang_restricted['pl'] == 1) {
                $lang_osd = $lang_osd | (1 << 4);
            }
            if ($lang_restricted['it'] == 1) {
                $lang_osd = $lang_osd | (1 << 5);
            }
            if ($lang_restricted['nl'] == 1) {
                $lang_osd = $lang_osd | (1 << 6);
            }
            if ($lang_restricted['pt'] == 1) {
                $lang_osd = $lang_osd | (1 << 7);
            }
            if ($lang_restricted['sv'] == 1) {
                $lang_osd = $lang_osd | (1 << 8);
            }
         }
         return $lang_osd;
    }

    public function stopSyncArrToBit($stop_sync_array)
    {
        $stop_sync = 0;
        //product content
        //0 NA / 1 = prod_name / 2 = contents / 3 = keyworks / 4 = detail_desc
        //product content extend
        //0 NA / 1 = feature / 2 = specification / 3 = enhanced_listing
        if (count($stop_sync_array) == 0) {
            $stop_sync = 1;
         } else {
            foreach($stop_sync_array as $check) {
                $stop_sync += $check;
            }
         }
         return $stop_sync;
    }

    //Website Status Auto
    public function autoWebsiteStatus($sourcing_status, $quantity, $origin_website_status)
    {
        if ($sourcing_status == 'A' and $origin_website_status == 'O') {
            $website_status = 'I';
        }
        if ($quantity == 0 and $sourcing_status == 'A' and $origin_website_status != 'P') {
            $website_status = 'I';
        } elseif ($quantity == 0 and ($sourcing_status == 'O' or $sourcing_status == 'L' or $sourcing_status == 'D') and $origin_website_status != 'P') {
            $website_status = 'O';
        }
        if ($quantity > 0 and $sourcing_status != 'pr' and $origin_website_status != 'P') {
            $website_status = 'I';
        }
        if ($sourcing_status == 'D' and ($origin_website_status == 'I' or $origin_website_status == 'A') and $quantity == 0) {
            $website_status = 'O';
        }
        if ($sourcing_status == 'pr' and $origin_website_status != 'P') {
            $website_status = 'P';
        }
        return $website_status;
    }

    public function sendWebStatusChangeEmail($batch_id)
    {
        $this->websiteStatusChangeList($batch_id);
        $this->websiteStatusManuallyList($batch_id);
    }

    public function websiteStatusChangeList($batch_id)
    {
        $where['phs.batch_id'] = $batch_id;
        $where['`phs`.`website_status` != `p`.`website_status`'] = NULL;
        $option['limit'] = -1;
        $result = $this->getHistorySyncDao()->getAlertSkuList($where, $option);
        if ($result) {
            $subject = '[CV2] Website Status Automation: Auto';
            $msg = '<html><body>This is Sku List that Website Status Changed <br /><br />';
            $msg .= "<table  border='0' cellspacing='0' cellpadding='0'><tr><th style='border:1px solid #000'>Sku</th><th style='border:1px solid #000'>Master Sku</th><th style='border:1px solid #000'>Name</th><th style='border:1px solid #000'>Origin Website Status</th><th style='border:1px solid #000'>Website Status</th><th style='border:1px solid #000'>Surplus Quantity</th><th style='border:1px solid #000'>Supplier Status</th><tr>";
            $i = 0;
            foreach ($result as $value) {
                $origin_status = $this->getWebsiteStatusStr($value->getOriginWebsiteStatus());
                $now_status = $this->getWebsiteStatusStr($value->getWebsiteStatus());
                $sourcing_status_str = $this->getSourcingStatusStr($value->getSourcingStatus());
                $msg .= "<tr><td style='border:1px solid #000'>".$value->getSku()."</td><td style='border:1px solid #000'>".$value->getMasterSku()."</td><td style='border:1px solid #000'>".$value->getName()."</td><td style='border:1px solid #000'>".$origin_status."</td><td style='border:1px solid #000'>".$now_status."</td><td style='border:1px solid #000'>".$value->getSurplusQty()."</td><td style='border:1px solid #000'>".$sourcing_status_str."</td><tr>";
                $i++;
            }
            $msg .= '</table></body></html>';
            if ($i > 0) {
                $this->sendAlertEmail($subject, $msg);
            }
        }
    }

    public function websiteStatusManuallyList($batch_id)
    {
        $where['phs.batch_id'] = $batch_id;
        $where['p.sourcing_status'] = 'C';
        $where['phs.website_status'] = 'I';
        $option['limit'] = -1;
        $result = $this->getHistorySyncDao()->getAlertSkuList($where, $option);
        if ($result) {
            $subject = '[CV2] Website Status Automation: Manual';
            $msg = '<html><body>The latter is for Stock contraint SKU.<br /><br />';
            $msg .= "<table  border='0' cellspacing='0' cellpadding='0'><tr><th style='border:1px solid #000'>Sku</th><th style='border:1px solid #000'>Master Sku</th><th style='border:1px solid #000'>Name</th><th style='border:1px solid #000'>Sourcing Status</th><th style='border:1px solid #000'>Website Status</th><tr>";
            $i =0;
            foreach ($result as $value) {
                $msg .= "<tr><td style='border:1px solid #000'>".$value->getSku()."</td><td style='border:1px solid #000'>".$value->getMasterSku()."</td><td style='border:1px solid #000'>".$value->getName()."</td><td style='border:1px solid #000'>Stock Constraint</td><td style='border:1px solid #000'>In Stock</td><tr>";
                $i++;
            }
            $msg .= '</table>';
            if ($i > 0) {
                $this->sendAlertEmail($subject, $msg);
            }
        }
    }

    public function pushSkuMappingToCPS()
    {
        $id = self::SCHEDULE_ID;
        $url = self::PUSH_CPS_URL;
        $current_time = date("Y-m-d H:i:s");
        $last_time = $this->getLastTime($id);
        $where['create_on >='] = $last_time;
        $objlist = $this->getDao('SkuMapping')->getList($where, array('limit'=>-1));
        $arr = array();
        foreach ($objlist as $row) {
            $data['sku'] = $row->getSku();
            $data['master_sku'] = $row->getExtSku();
            $arr[] = $data;
        }
        if (count($arr) > 0) {
            $data = json_encode($arr);
            $res = $this->curlPost($data, $url);
            if ($res['error']) {
                mail("will.zhang@eservicesgroup.com", "Push SKU Mapping to CPS Wrong", "ERROR:\r\n".$res['error']);
            }
        }
        $this->updatLastTime($id, $current_time);
    }

    public function curlPost($data, $url = '')
    {
        $header = array('Content-Type: application/json; charset=utf-8', 'Content-Length: ' . strlen($data));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERPWD, 'demo:demo888');
        $server_result = curl_exec($ch);
        $server_error = curl_error($ch);
        $server_info = curl_getinfo($ch);
        curl_close($ch);
        return array("xml" => $server_result, "error" => $server_error, "info" => $server_info);
    }

    private function getLastTime($id)
    {
        if ($obj = $this->getDao('ScheduleJob')->get(["schedule_job_id" => $id, "status" => 1])) {
            return $obj->getLastAccessTime();
        }
    }

    private function updatLastTime($id, $current_time)
    {
        if ($obj = $this->getDao('ScheduleJob')->get(["schedule_job_id" => $id, "status" => 1])) {
            $obj->setLastAccessTime($current_time);
            return $this->getDao('ScheduleJob')->update($obj);
        }
    }

    public function getWebsiteStatusStr($website_status)
    {
        $website_status_arr = [
            'I' => 'In Stock',
            'O' => 'Out Of Stock',
            'P' => 'Pre-Order',
            'A' => 'Arriving',
        ];
        if (array_key_exists($website_status, $website_status_arr)) {
            return $website_status_arr[$website_status];
        } else {
            return 'Un Know';
        }
    }

    public function getSourcingStatusStr($sourcing_status)
    {
        $sourcing_status_arr = [
            'A' => 'Readily Available',
            'D' => 'Discontinued',
            'O' => 'Temp Out of stock',
            'C' => 'Stock Constraint',
            'L' => 'Last Lot',
            'pr' => 'Pre Order',
        ];
        if (array_key_exists($sourcing_status, $sourcing_status_arr)) {
            return $sourcing_status_arr[$sourcing_status];
        } else {
            return 'Un Know';
        }
    }

    public function sendAlertEmail($subj, $msg)
    {
        $headers .= 'From: Admin <admin@digitaldiscount.com>' . "\r\n";
        $headers .= 'Cc: will.zhang@eservicesgroup.com' . "\r\n";
        $headers .= "MIME-Version: 1.0"."\r\n";
        $headers .= "Content-type: text/html; charset=utf-8". "\r\n";
        if ($subj && $msg) {
            $msg = str_replace("\n.", "\n..", $msg);
            mail ("bd@eservicesgroup.net, purchase@aln.hk", $subj, $msg, $headers);
        }
    }

    public function getSkuMappingDao()
    {
        return $this->sku_mapping_dao;
    }

    public function setSkuMappingDao($value)
    {
        $this->sku_mapping_dao = $value;
    }

    public function getInterfaceSkuDao()
    {
        return $this->interface_sku_dao;
    }

    public function setInterfaceSkuDao($value)
    {
        $this->interface_sku_dao = $value;
    }

    public function getHistorySyncDao()
    {
        return $this->history_sync_dao;
    }

    public function setHistorySyncDao($value)
    {
        $this->history_sync_dao = $value;
    }

    public function getSupplierProdDao()
    {
        return $this->supplier_prod_dao;
    }

    public function setSupplierProdDao($value)
    {
        $this->supplier_prod_dao = $value;
    }

    public function getSupplierDao()
    {
        return $this->supplier_dao;
    }

    public function setSupplierDao($value)
    {
        $this->supplier_dao = $value;
    }
}