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
                    $product_obj = $this->getDao()->get(array('sku'=>$prod_sku));
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
                $product_obj = $this->getDao()->get(array('sku'=>$prod_sku));

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
                    $error = $this->getDao()->db->last_query();
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
        $result = $this->getDao()->update($product_obj);
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
        $sup_prod_obj->set_pricehkd((double)$sync_obj->getPricehkd());
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
        $sup_prod_vo->set_supplier_id($supplier_id);
        $sup_prod_vo->setProdSku((string)$sku_obj->getProdSku());
        $sup_prod_vo->setCurrencyId((string)$sku_obj->getCurrencyId());
        $sup_prod_vo->setCost((double)$sku_obj->getPrice());
        $sup_prod_vo->set_pricehkd((double)$sku_obj->getPricehkd());
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