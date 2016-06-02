<?php
namespace ESG\Panther\Service;
class FlexService extends BaseService
{
    const ROLLING_RESERVE_REPORT_FILE_NAME = "rolling_reserve.csv";

    public $contact_email = 'will.zhang@eservicesgroup.com';
    public $platform_arr = array('QOO10SG', 'RAKUES', 'FNACES', 'LAMY', 'LAZTH', 'LAZPH', 'NEUS');
    public $order_reason_category = array(
                '1' => array('6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18'),
                '2' => array('19', '20', '21', '22'),
                '3' => array('23', '32')
            );
    public $refund_list = array("R", "A_RF", "A_RO", "A_RF_C", "A_RF_RC", "M_MRF_W", "M_MRF_U");
    public $ria_list = array("RIA", "A_COMM", "A_FBA_H", "A_FBA_P", "A_FBA_W", "A_SC", "A_CBF", "A_SO_FEE", "A_OO", "A_OP", "M_PTF",
        "M_F", "M_RMC_PTF", "M_RMC_F", "A_FPUFF", "A_FPOFF", "A_FWBF", "A_OOS","A_OPS", "A_OP");
    public $chargeback_list = array("M_CBF");
    public $fee_list = array("BTU", "A_O_FIR", "A_O", "A_SFSF", "A_SS", "A_SFRF", "A_SFDF", "A_SFLTSF", "A_O_O_DC", "A_O_O_FD", "A_O_O");
    public $ps_list = array("PS");
    public $pr_list = array("PR", "PR_R", "PR_C");
    public $rr_list = array("RRH");
    public $mf_list = array("T_MF");
    public $tr_list = array("P_TF");

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        $this->dataProcessService = new DataProcessService;
        $this->salesInvoiceDto = new \SalesInvoiceDto();
        $this->supplierInvoiceDto = new \SupplierInvoiceDto();
        $this->refundInvoiceDto = new \RefundInvoiceDto();
        $this->soFeeInvoiceDto = new \SoFeeInvoiceDto();
        $this->gatewayFeeInvoiceDto = new \GatewayFeeInvoiceDto();
        $this->RollingReserveReportDto = new \RollingReserveReportDto();
        $this->contextConfigService = new ContextConfigService;
        $this->zipClass = new \ZipArchive();
    }

    public function processReport($pmgw, $filename)
    {
        $pmgw_service = $pmgw."PmgwReport";
        return $this->getService($pmgw_service)->processReport($filename);
    }

    public function generateFeedbackReport($where, $option)
    {
        $feedback_report = [];
        $fri_obj_list = $this->getDao('FlexRia')->getList($where, $option);
        $fre_obj_list = $this->getDao('FlexRefund')->getList($where, $option);
        $fgf_obj_list = $this->getDao('FlexGatewayFee')->getList($where, $option);
        $fsf_obj_list = $this->getDao('FlexSoFee')->getList($where, $option);
        $frr_obj_list = $this->getDao('FlexRollingReserve')->getList($where, $option);

        if (!empty($fri_obj_list)) {
            $feedback_report['flex_ria']['name'] = 'flex_ria_feedback_report.csv';
            $feedback_report['flex_ria']['content'] = $this->flexFeedbackConvert($fri_obj_list, 'flex_ria');
        }
        if (!empty($fre_obj_list)) {
            $feedback_report['flex_refund']['name'] = 'flex_refund_feedback_report.csv';
            $feedback_report['flex_refund']['content'] = $this->flexFeedbackConvert($fre_obj_list, 'flex_refund');
        }
        if (!empty($fgf_obj_list)) {
            $feedback_report['flex_gateway_fee']['name'] = 'flex_gateway_fee_feedback_report.csv';
            $feedback_report['flex_gateway_fee']['content'] = $this->flexFeedbackConvert($fgf_obj_list, 'flex_gateway_fee');
        }
        if (!empty($fsf_obj_list)) {
            $feedback_report['flex_so_fee']['name'] = 'flex_so_fee_feedback_report.csv';
            $feedback_report['flex_so_fee']['content'] = $this->flexFeedbackConvert($fsf_obj_list, 'flex_so_fee');
        }
        if (!empty($frr_obj_list)) {
            $feedback_report['flex_rolling_reserve']['name'] = 'flex_rolling_reserve_feedback_report.csv';
            $feedback_report['flex_rolling_reserve']['content'] = $this->flexFeedbackConvert($frr_obj_list, 'flex_rolling_reserve');
        }
        $data['result'] = $feedback_report;

        $this->writeFile($data, 'report', 'feedback_report', false);
        DEFINE('REPORT_PATH', $this->contextConfigService->valueOf("flex_report_path"));
        $this->generateZipFile(REPORT_PATH . 'feedback_report/report/', 'feedback_report.zip');
        return array('filename' => 'feedback_report.zip', 'file_path' => REPORT_PATH . 'feedback_report/report/feedback_report.zip');
    }

    public function flexFeedbackConvert($list, $flex_type)
    {
        $map_file = APPPATH . 'data/flex/'.$flex_type.'_feedback_report.php';
        return $this->dataProcessService->ObjlistToCsv($list, $map_file);
    }

    public function writeFile($data, $type, $folder_name, $gen_exception_only = TRUE)
    {
        DEFINE('REPORT_PATH', $this->contextConfigService->valueOf("flex_report_path"));
        $file_path = REPORT_PATH . $folder_name;
        $this->makeDir($file_path, $type);
        if ($data) {
            foreach ($data as $pmgw => $si_list_w_currency) {
                foreach ($si_list_w_currency as $currency_id => $list) {
                    if ($gen_exception_only) {
                        if ($pmgw != "Exception") {
                            continue;
                        }
                    }
                    $fp = fopen($file_path . "/" . $type . "/" . $list["name"], 'w');
                    if (!fwrite($fp, $list["content"])) {
                        $subject = "<DO NOT REPLY>Fail to write file - " . $path;
                        $message = "CONTENT: " . $list['content'] . "<br>LINE: " . __LINE__;
                        $this->errorHandler($subject, $message);
                        return FALSE;
                    }
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    public function makeDir($file_path, $type)
    {
        if (!is_dir($file_path . "/" . $type)) {
            if (!is_dir($file_path)) {
                mkdir($file_path, 0775);
                mkdir($file_path . "/" . $type, 0775);
            } else {
                mkdir($file_path . "/" . $type, 0775);
            }
        }
    }

    public function errorHandler($subject = '', $msg = '', $email = '', $is_dead = false)
    {
        if ($subject) {
            if (empty($email)) {
                $email = $this->contact_email;
            }
            mail($email, $subject, $msg, 'From: itsupport@eservicesgroup.net');
        }
        if ($is_dead) {
            exit;
        }
    }

    public function generateZipFile($file_path, $zip_name)
    {
        $files_to_zip = [];
        if (is_dir($file_path) && ($handle = opendir($file_path))) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $ext = PATHINFO($entry, PATHINFO_EXTENSION);
                    if ($ext != "zip") {
                        $file_to_delete[] = $entry;
                        if ($ext != "txt") {
                            $files_to_zip[] = $entry;
                        }
                    }
                }
            }
            closedir($handle);
        }
        if (file_exists($file_path.$zip_name)) {
            $overwrite = true;
        } else {
            $overwrite = false;
        }
        $result = $this->createZip($files_to_zip, $zip_name, $file_path, $overwrite);
        if (isset($file_to_delete)) {
            foreach ($file_to_delete as $file) {
                @unlink($file_path . $file);
            }
        }
    }

    public function createZip($files = [], $destination = '', $location = '', $overwrite = true)
    {
        if (file_exists($location . $destination) && !$overwrite) {
            return false;
        }
        $valid_files = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                if (file_exists($location . $file)) {
                    $valid_files[] = $file;
                }
            }
        }
        if (count($valid_files)) {
            $zip = $this->zipClass;
            if ($zip->open($location . $destination, $overwrite ? \ZipArchive::OVERWRITE : \ZipArchive::CREATE) !== true) {
                return false;
            }
            foreach ($valid_files as $file) {
                $zip->addFile($location . $file, $file);
            }
            $zip->close();
            return file_exists($location . $destination);
        } else {
            return false;
        }
    }

    public function getSalesInvoice($date, $folder_name, $gen_exception_only = TRUE, $ignore_status = FALSE)
    {
        $gen_exception_only = FALSE;
        $where = [];
        $date = date("Y-m-d", strtotime($date));
        $where["so.dispatch_date >= "] = $date. ' 00:00:00';
        $where["so.dispatch_date <= "] = $date. ' 23:59:59';
        $si_list = $this->getDao('So')->getFlexSalesInvoice($where);
        if ($si_list) {
            $flex_sales_invoice_data = $this->getFlexSalesInvoiceData($si_list);
            $data = $flex_sales_invoice_data['data'];
            $exception = $flex_sales_invoice_data['exception'];
            $special_order = $flex_sales_invoice_data['special_order'];
            $flex_invoice_list = [];
            if ($data) {
                $group_content = [];
                foreach ($data AS $pmgw => $si_list_w_currency) {
                    foreach ($si_list_w_currency AS $currency_id => $list) {
                        $group_content = array_merge($group_content, $list);
                        $flex_list = $this->setFlexFormat($list);
                        $csv_file = $this->convert($flex_list, TRUE);
                        $file_name = $pmgw . "_" . $currency_id . ".csv";
                        $file = $this->assembleSaleReport($file_name, $csv_file);
                        $result[$pmgw][$currency_id] = $file;
                        if (is_array($flex_invoice_list["OK"])) {
                            $flex_invoice_list["OK"] = array_merge($flex_invoice_list["OK"], $flex_list);
                        } else {
                            $flex_invoice_list["OK"] = $flex_list;
                        }
                    }
                }
            }

            if ($exception) {
                $exception_list = $this->setFlexFormat($exception);
                $csv_file = $this->convert($exception_list, TRUE, TRUE);
                $file["name"] = "Exception.csv";
                $file["content"] = $csv_file;
                $result["Exception"]["ALL"] = $file;
                if (!$gen_exception_only) {
                    $flex_invoice_list["EXCEPTION"] = $exception_list;
                }
            }

            if ($special_order) {
                $special_order_list = $this->setFlexFormat($special_order);
                $temp_h = [];
                foreach ($special_order_list as $temp_order) {
                    $t_tran_type = $temp_order->getTranType();
                    $temp_h[$t_tran_type][] = $temp_order;
                }
                unset($special_order_list);
                $assemble_speical_order = [];
                foreach ($temp_h as $item) {
                    $assemble_speical_order = array_merge($assemble_speical_order, $item);
                }
                unset($temp_h);
                $temp_h = [];
                $index_num = 1;
                $product_line = 1;
                $last_tran_type = '';
                foreach ($assemble_speical_order as $obj) {
                    if ($last_tran_type != $obj->getTranType()) {
                        $product_line = 1;
                        $last_tran_type = $obj->getTranType();
                    }
                    $obj->setIndexNo($index_num);
                    $obj->setProductLine($product_line);
                    $row_no = ($product_line - 1) ? ($product_line - 1) : "";
                    $obj->setRowNo($row_no);
                    $index_num++;
                    $product_line++;
                }
                $csv_file = $this->convert($assemble_speical_order, TRUE, true);
                $file_name = "special_order.csv";
                $file = $this->assembleSaleReport($file_name, $csv_file, 2);
                $result["SPECIAL_ORDER"]["ALL"] = $file;
                if (!$gen_exception_only) {
                    $flex_invoice_list['SPECIAL_ORDER'] = $assemble_speical_order;
                }
            }

            if ($exception && $gen_exception_only) {
                $this->writeFile($result, "sales", $folder_name, TRUE);
                return false;
            } else {
                if ($this->writeFile($result, "sales", $folder_name, FALSE)) {
                    $err = 0;
                    foreach ($flex_invoice_list as $status => $fi_list) {
                        if ($fi_list) {
                            foreach ($fi_list as $obj) {
                                if ($flex_ria_obj_list = $this->getDao('FlexRia')->getList(array("flex_batch_id" => $obj->getFlexBatchId(), "so_no" => $obj->getSoNo()), ["limit" => -1])) {
                                    foreach ($flex_ria_obj_list as $flex_ria_obj) {
                                        if ($flex_ria_obj->getStatus() == "SALES") {
                                            $err = 1;
                                            $message = $obj->getFlexBatchId() . "," . $obj->getSoNo() . "\r\n";
                                        } else {
                                            $flex_ria_obj->setStatus('SALES');
                                            $this->getDao('FlexRia')->update($flex_ria_obj);
                                        }
                                    }
                                }
                                if ($obj->getMasterSku() == "") {
                                    $inventory[$status]["NIL"] = $inventory[$status]["NIL"] + $obj->getQty();
                                } else {
                                    $inventory[$status][$obj->getMasterSku()] = $inventory[$status][$obj->getMasterSku()] + $obj->getQty();
                                }
                            }
                        }
                    }

                    if ($group_content) {
                        $index = 0;
                        $flex_list = $this->setFlexFormat($group_content);
                        $segement_array = array_chunk($flex_list, 900);
                        foreach ($segement_array as $s) {
                            $temp_h = [];
                            $index_num = 1;
                            $product_line = 1;
                            $last_pmgw = "";
                            foreach ($s as $t) {
                                if ($last_pmgw != $t->getReportPmgw()) {
                                    $product_line = 1;
                                    $last_pmgw = $t->getReportPmgw();
                                }
                                $t->setIndexNo($index_num);
                                $t->setProductLine($product_line);
                                $row_no = ($product_line - 1) ? ($product_line - 1) : "";
                                $t->setRowNo($row_no);
                                $index_num++;
                                $product_line++;
                                $temp_h[] = $t;
                            }

                            $csv_file = $this->convert($temp_h, FALSE);
                            $index++;
                            $file_name = "grouping_file_" . $index . ".csv";
                            $file = $this->assembleSaleReport($file_name, $csv_file);
                            $result['G']['G'] = $file;
                            $this->writeFile($result, 'sales', $folder_name, $gen_exception_only = FALSE);
                        }
                    }
                    $this->getInventoryReport($inventory, $date, $folder_name);
                    return true;
                }
            }
        }
        return false;
    }

    public function getFlexSalesInvoiceData($si_list)
    {
        $platform_arr = $this->platform_arr;
        $order_reason_category = $this->order_reason_category;
        foreach ($si_list AS $key => $obj) {
            $so_no = $obj->getSoNo();
            $is_split_order = false;
            if ($origin_so_no = $obj->getSplitSoGroup()) {
                $is_split_order = true;
                $obj->setSoNo($origin_so_no);
                if ($fr_obj = $this->getDao('FlexRia')->getFlexRiaWithGatewayMapping(["so_no" => $origin_so_no])) {
                    $obj->setReportPmgw($fr_obj->getReportPmgw());
                    $obj->setTranType($fr_obj->getTranType());
                    $obj->setFlexBatchId($fr_obj->getFlexBatchId());
                    $obj->setTxnTime($fr_obj->getTxnTime());
                    $obj->setTxnId($fr_obj->getTxnId());
                }
            } else {
                $fr_obj = $this->getDao('FlexRia')->get(["so_no" => $so_no, "flex_batch_id" => $obj->getFlexBatchId()]);
            }

            $so_obj = $this->getDao('So')->get(array("so_no" => $so_no));
            $platform_id = $obj->getPlatformId();
            $is_platform = in_array($platform_id, $platform_arr);

            if ($so_obj->getBizType() == "SPECIAL") {
                $order_reason = $this->getDao('So')->getSoWithReason(['so.so_no' => $so_no], ['limit' => 1]);
                $obj->setReason($order_reason->getReasonDisplayName());
                $obj->setRemark('Speical Order');
                $reason_id = $obj->getOrderReason();
                if (in_array($reason_id, $order_reason_category['1'])) {
                    $this->reformatSpecialOrderData($obj, $reason_id);
                    $special_order[] = $obj;
                } elseif (in_array($reason_id, $order_reason_category['2'])) {
                    if ($fr_obj = $this->getDao('FlexRia')->getFlexRiaWithGatewayMapping(["so_no"=>$obj->get_parent_so_no()])) {
                        $obj->setReportPmgw($fr_obj->getReportPmgw());
                        $obj->setTranType($fr_obj->getTranType());
                    }
                    $special_order[] = $obj;
                } else {
                    $exception[] = $obj;
                }
            } elseif ($fr_obj && ($fr_obj->getCurrencyId() <> $so_obj->getCurrencyId()) && !$is_platform) {
                $obj->setRemark("Inconsistent Currency");
                $exception[] = $obj;
            } elseif (!$is_split_order && $fr_obj && ($fr_obj->getAmount() <> $so_obj->getAmount()) && !$is_platform) {
                $obj->setRemark("Inconsistent Amount");
                $exception[] = $obj;
            } elseif ($fr_obj && ($fr_obj->getStatus() <> 'RIA') && !$ignore_status && !$is_platform) {
                $obj->setRemark("Not RIA Status");
                $exception[] = $obj;
            } elseif (!$fr_obj && !$is_platform) {
                $obj->setRemark("RIA NOT FOUND");
                $exception[] = $obj;
            } elseif (!$obj->getReportPmgw() && !$is_platform) {
                $obj->setRemark("Missing Payment Gateway Code");
                $exception[] = $obj;
            } else {
                $this->platformReformData($obj);
                $data[$obj->getReportPmgw()][$obj->getCurrencyId()][] = $obj;
            }
        }
        $result['exception'] = $exception;
        $result['data'] = $data;
        $result['special_order'] = $special_order;
        return $result;
    }


    public function reformatSpecialOrderData($dto, $reason_id)
    {
        if (empty($reason_id)) {
            return;
        }
        switch ($reason_id) {
            case '6':
            case '10':
                $dto->setTranType("REPLACEI");
                $dto->setReportPmgw("REPLACE");
                break;
            case '7':
            case '8':
                $dto->setTranType("REPLASHI");
                $dto->setReportPmgw("REPLASH");
                break;
            case '9':
            case '11':
            case '12':
            case '13':
            case '14':
                $dto->setTranType("REPLAOLI");
                $dto->setReportPmgw("REPLAOL");
                break;
            case '15':
            case '16':
            case '17':
            case '18':
                $dto->setTranType("CSI");
                $dto->setReportPmgw("COMP&GIFT");
                break;
            default:
                break;
        }
    }

    public function platformReformData($dto)
    {
        $platform_id = $dto->getPlatformId();
        switch ($platform_id) {
            case 'QOO10SG':
                $dto->setTranType("QOO1" . $dto->getCurrencyId() . "I");
                $dto->setReportPmgw("QOO1" . $dto->getCurrencyId());
                $dto->setTxnTime($dto->getOrderCreateDate());
                break;
            case 'RAKUES':
                $dto->setTranType("RAKU" . $dto->getCurrencyId() . "I");
                $dto->setReportPmgw("RAKU" . $dto->getCurrencyId());
                $dto->setTxnTime($dto->getOrderCreateDate());
                break;
            case 'FNACES':
                $dto->setTranType("FNES" . $dto->getCurrencyId() . "I");
                $dto->setReportPmgw("FNES" . $dto->getCurrencyId());
                $dto->setTxnTime($dto->getOrderCreateDate());
                break;
            case 'LAMY':
            case 'LAZTH':
            case 'LAZPH':
                $dto->setTranType("LZMY" . $dto->getCurrencyId() . "I");
                $dto->setReportPmgw("LZMY" . $dto->getCurrencyId());
                $dto->setTxnTime($dto->getOrderCreateDate());
                break;
            case 'NEUS':
                $dto->setTranType("NEUS" . $dto->getCurrencyId() . "I");
                $dto->setReportPmgw("NEUS" . $dto->getCurrencyId());
                $dto->setTxnTime($dto->getOrderCreateDate());
                break;
            default:
                break;
        }
    }

    public function setFlexFormat($list)
    {
        $index_no = $product_line = $i = 1;
        $sku = "";
        $delivery_list = [];
        $re_arrange_container = [];
        foreach ($list AS $obj) {
            $obj->setRowNo($product_line - 1);
            if ($product_line - 1 == 0) {
                $obj->setRowNo("");
            } else {
                $obj->setRowNo($product_line - 1);
            }
            $obj->setProductLine($product_line);
            $obj->setIndexNo($index_no);
            $txn_time = date("Y-m", strtotime($obj->getTxnTime()));
            $obj->setUnitPrice($obj->getAmount() / $obj->getQty());
            $product_line++;
            $index_no++;
            $re_arrange_container[] = $obj;
            if ($obj->getDeliveryCharge() > 0 && (!in_array($obj->getSoNo(), $delivery_list))) {
                $si_dto = clone $this->salesInvoiceDto;
                $si_dto->setMasterSku("20971-AA-NA");
                $si_dto->setDispatchDate($obj->getDispatchDate());
                $si_dto->setCurrencyId($obj->getCurrencyId());
                $si_dto->setReportPmgw($obj->getReportPmgw());
                $si_dto->setTranType($obj->getReportPmgw() . "I");
                $si_dto->setFlexBatchId($obj->getFlexBatchId());
                $si_dto->setProductCode("20971-AA-NA");
                $si_dto->setQty(1);
                $si_dto->setUnitPrice($obj->getDeliveryCharge());
                $si_dto->setTxnTime($obj->getTxnTime());
                $si_dto->setSoNo($obj->getSoNo());
                $si_dto->setTxnId($obj->getTxnId());
                $si_dto->setCustomerEmail($obj->getCustomerEmail());
                $si_dto->setSmCode($obj->getSmCode());
                $si_dto->setContainSize($obj->getContainSize());
                $si_dto->setIndexNo($index_no);
                $si_dto->setProductLine($product_line);
                $si_dto->setRowNo($product_line - 1);
                $product_line++;
                $index_no++;
                $re_arrange_container[] = $si_dto;
                $list[] = $si_dto;
                $delivery_list[] = $obj->getSoNo();
            }
        }
        return $re_arrange_container;
    }

    public function convert($obj_list = [], $is_exception = FALSE)
    {
        if ($is_exception) {
            $mapping_file = APPPATH . 'data/flex/flex_sales_exception.php';
        } else {
            $mapping_file = APPPATH . 'data/flex/flex_sales_invoice.php';
        }
        return $this->dataProcessService->ObjlistToCsv($obj_list, $mapping_file);
    }

    private function assembleSaleReport($file_name, $csv_file, $format = 1)
    {
        $file["name"] = $file_name;

        switch ($format) {
            case '1':
                $file["content"] = "-\r\n";
                $file["content"] .= "IndexNum,Line,Row,ProductCodeCopy,Header_TranType,Header_Date,Header_CurrCode,Header_CustCode,CIVNum,ProductCode,BaseQty,BGUnitPrice,InnType,CustRefNo,ShipLocCode,ContainNo,OutType,CartonNo,ContainSize,SMCode\r\n";
                $file["content"] .= "-\r\n";
                $file["content"] .= $csv_file;
                break;
            case '2':  // special order format
                $file["content"] = "-\r\n";
                $file["content"] .= "IndexNum,Line,Row,ProductCodeCopy,Header_TranType,Header_Date,Header_CurrCode,Header_CustCode,CIVNum,ProductCode,BaseQty,BGUnitPrice,InnType,CustRefNo,ShipLocCode,ContainNo,OutType,Gateway_ID,CartonNo,ContainSize,SMCode,Remark,APS,Reason\r\n";
                $file["content"] .= "-\r\n";
                $file["content"] .= $csv_file;
                break;

            default:
                $file["content"] = "-\r\n";
                $file["content"] .= "IndexNum,Line,Row,ProductCodeCopy,Header_TranType,Header_Date,Header_CurrCode,Header_CustCode,CIVNum,ProductCode,BaseQty,BGUnitPrice,InnType,CustRefNo,ShipLocCode,ContainNo,OutType,CartonNo,ContainSize,SMCode\r\n";
                $file["content"] .= "-\r\n";
                $file["content"] .= $csv_file;
                break;
        }

        return $file;
    }

    public function getInventoryReport($inv_list, $dispatch_date, $folder_name)
    {
        $index_no = $product_line = 1;
        foreach ($inv_list as $status => $list) {
            $report_list = [];
            foreach ($list as $sku => $qty) {
                if ($sku <> 'NIL') {
                    if (!$ret = $this->getDao('SupplierProd')->getSupplierCostBySkuDate($sku, $dispatch_date)) {
                        $ret = $this->getDao('SupplierProd')->getCurrentSupplierCost($sku);
                    }
                    $siv_dto = clone $this->supplierInvoiceDto;
                    $siv_dto->setIndexNo($index_no);
                    $siv_dto->setProductLine($product_line);
                    $siv_dto->setRowNo(($product_line - 1) == 0 ? "" : $product_line - 1);
                    $siv_dto->setMasterSku($sku);
                    $siv_dto->setDispatchDate($dispatch_date);
                    $siv_dto->setCurrencyId($ret["currency_id"]);
                    $siv_dto->setSiv($dispatch_date);
                    $siv_dto->setProductCode($sku);
                    $siv_dto->setQty($qty);
                    $siv_dto->setUnitPrice($ret['cost']);
                    $report_list[] = $siv_dto;
                }
                $index_no++;
                $product_line++;
            }
            $this->getInventoryReportFile($report_list, $folder_name, $status);
        }
    }

    public function getInventoryReportFile($report_list, $folder_name, $status)
    {
        DEFINE('REPORT_PATH', $this->contextConfigService->valueOf("flex_report_path"));
        $file_path = REPORT_PATH . $folder_name;
        $csv_file = "-\r\n";
        $csv_file .= "IndexNum,Line,Row,ProductCodeCopy,Header_TranType,Header_Date,Header_CurrCode,Header_SupCode,SivNum,ProductCode,BaseQty,BGUnitPrice,ShipLocCode\r\n";
        $csv_file .= "-\r\n";
        foreach ($report_list as $row) {
            $csv_file .=  $row->getIndexNo() .','. $row->getProductLine() .','. $row->getRowNo() .','. $row->getMasterSku() .','. 'SIV' .','. $row->getDispatchDate() .','. $row->getCurrencyId() .','. 'E0100' .','. $row->getSiv() .','. $row->getProductCode() .','. $row->getQty() .','. $row->getUnitPrice() .','. "HK\r\n";
        }
        if ($csv_file) {
            if (!is_dir($file_path . "/sales")) {
                if (!is_dir($file_path)) {
                    mkdir($file_path, 0775);
                    mkdir($file_path . "/sales", 0775);
                } else {
                    mkdir($file_path . "/sales", 0775);
                }
            }
        }

        if ($status == "OK") {
            $filename = "inventory.csv";
        } elseif ($status == "SPECIAL_ORDER") {
            $filename = 'special_order_inventory.csv';
        } else {
            $filename = "inventory_exception.csv";
        }
        $fp = fopen($file_path . "/sales/" . $filename, 'w');
        if (!fwrite($fp, $csv_file)) {
            $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/sales/" . $filename;
            $message = "CONTENT: " . $csv_file . "<br> LINE: " . __LINE__;
            $this->errorHandler($subject, $message);
            return FALSE;
        }
    }

    public function getRefundInvoice($start_date, $end_date, $type = "R", $folder_name)
    {
        $where = [];
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));
        $where["status"] = $type;
        $where["txn_time >="] = $start_date . ' 00:00:00';
        $where["txn_time <="] = $end_date . ' 23:59:59';
        //$type = $where['status'];
        $refund_data = $this->getFlexRefundData($where);
        $data = $refund_data['data'];
        $exception = $refund_data['exception'];
        if ($data) {
            $refund_report_list = $this->getRefundReportList($data, $type);
            if ($refund_report_list) {
                $this->genRefundReport($report_list, $folder_name, $type);
            }
        }

        if ($exception) {
            $report_list = [];
            $where = [];
            $where["frf.status"] = $type;
            $where["txn_time >="] = $start_date . ' 00:00:00';
            $where["txn_time <="] = $end_date . ' 23:59:59';
            $exception_report_list = $this->getExceptionReportList($exception, $where);
            if ($report_list) {
                $this->genExceptionReport($report_list, $folder_name, $type);
            }
        }
    }

    public function getRefundReportList($data, $type)
    {
        foreach ($data as $obj) {
            $index_no = $product_line = 1;
            $item_list = $this->getDao('So')->getFlexRefundInvoice(["frf.so_no" => $obj->getSoNo(), "frf.status" => $type]);
            if ($item_list) {
                $fr_obj = $this->getDao('FlexRia')->get(["so_no" => $obj->getSoNo()]);
                foreach ($item_list as $obj) {
                    $gen_refund_report = TRUE;
                    $riv_dto = clone $this->refundInvoiceDto;
                    if ($txn_id <> $obj->getTxnId()) {
                        $product_line = 1;
                    }
                    $riv_status = "";
                    if ($type == 'CB') {
                        $riv_status = 'C';
                    } else {
                        $riv_status = $type;
                    }
                    $riv_dto->setIndexNo($index_no);
                    $riv_dto->setProductLine($product_line);
                    $riv_dto->setRowNo(($product_line - 1) == 0 ? "" : $product_line - 1);
                    $riv_dto->setMasterSku($obj->getMasterSku());
                    $riv_dto->setProductCode($obj->getMasterSku());
                    $riv_dto->setFlexBatchId($obj->getFlexBatchId());
                    $riv_dto->setTxnTime(date("Y-m-d", strtotime($obj->getTxnTime())));
                    $riv_dto->setCurrencyId($obj->getCurrencyId());
                    $riv_dto->setReportPmgw($obj->getReportPmgw());
                    $riv_dto->setUnitPrice($obj->getUnitPrice());
                    $riv_dto->setQty($obj->getQty());
                    $riv_dto->setTxnId($obj->getTxnId());
                    $riv_dto->setSoNo($obj->getSoNo());
                    $riv_dto->setTranType($obj->getReportPmgw() . $riv_status);
                    $riv_dto->setRiaTxnTime($fr_obj->getTxnTime());
                    $riv_dto->setSrNum("SR" . date("Ymd", strtotime($obj->getTxnTime())) . "01");
                    $report_list[] = $riv_dto;
                    $txn_id = $obj->getTxnId();
                    $index_no++;
                    $product_line++;
                }
            }
        }
        if (empty($report_list)) {
            return false;
        } else {
            return $report_list;
        }
    }

    public function getExceptionReportList($exception, $where)
    {
        foreach ($exception as $ex) {
            $obj = $ex["obj"];
            $index_no = $product_line = 1;
            $where["frf.so_no"] = $obj->getSoNo();
            if ($item_list = $this->getDao('So')->getFlexRefundInvoice($where)) {
                $fr_obj = $this->getDao('FlexRia')->get(["so_no" => $obj->getSoNo()]);
                foreach ($item_list as $obj) {
                    $riv_dto = clone $this->RefundInvoiceDto;
                    if ($txn_id <> $obj->getTxnId()) {
                        $product_line = 1;
                    }

                    $riv_dto->setIndexNo($index_no);
                    $riv_dto->setProductLine($product_line);
                    $riv_dto->setRowNo(($product_line - 1) == 0 ? "" : $product_line - 1);
                    $riv_dto->setMasterSku($obj->getMasterSku());
                    $riv_dto->setProductCode($obj->getMasterSku());
                    $riv_dto->setFlexBatchId($obj->getFlexBatchId());
                    $riv_dto->setTxnTime($obj->getTxnTime());
                    $riv_dto->setCurrencyId($obj->getCurrencyId());
                    $riv_dto->setReportPmgw($obj->getReportPmgw());
                    $riv_dto->setUnitPrice($obj->getUnitPrice());
                    $riv_dto->setQty($obj->getQty());
                    $riv_dto->setTxnId($obj->getTxnId());
                    $riv_dto->setSoNo($obj->getSoNo());
                    $riv_dto->setTranType($obj->getTranType());
                    $riv_dto->setSrNum("SR" . date("Ymd", $obj->getTxnTime()) . "01");
                    $riv_dto->setFailedReason($ex["failed_reason"]);
                    $riv_dto->setGatewayId($obj->getGatewayId());
                    if ($fr_obj) {
                        $riv_dto->setRiaTxnTime($fr_obj->getTxnTime());
                    } else {
                        $riv_dto->setRiaTxnTime("");
                    }
                    $report_list[] = $riv_dto;
                    $txn_id = $obj->getTxnId();
                    $index_no++;
                    $product_line++;
                }
            }
        }
        if (empty($report_list)) {
            return false;
        } else {
            return $report_list;
        }
    }

    public function genExceptionReport($report_list, $folder_name, $type)
    {
        DEFINE('REPORT_PATH', $this->contextConfigService->valueOf("flex_report_path"));
        $file_path = REPORT_PATH . $folder_name;
        $mapping_file = APPPATH . 'data/flex/flex_refund_exception.php';
        $csv_file = $this->dataProcessService->ObjlistToCsv($report_list, $mapping_file);
        if ($csv_file) {
            if (!is_dir($file_path . "/refund")) {
                if (!is_dir($file_path)) {
                    mkdir($file_path, 0775);
                    mkdir($file_path . "/refund", 0775);
                } else {
                    mkdir($file_path . "/refund", 0775);
                }
            }
        }
        switch ($type) {
            case "R":
                $filename = "refund_exception.csv";
                break;
            case "CB":
                $filename = "chargeback_exception.csv";
                break;
        }
        $fp = fopen($file_path . "/refund/" . $filename, 'w');
        if (!fwrite($fp, $csv_file)) {
            $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/refund/" . $filename;
            $message = "CONTENT: " . $csv_file . "<br> LINE: " . __LINE__;
            $this->errorHandler($subject, $message);
        }
    }

    public function genRefundReport($report_list, $folder_name, $type)
    {
        DEFINE('REPORT_PATH', $this->contextConfigService->valueOf("flex_report_path"));
        $file_path = REPORT_PATH . $folder_name;
        $out_xml = new VoToXml($report_list, APPPATH . 'data/flex/flex_refund_invoice_vo2xml.txt');
        $out_csv = new XmlToCsv("", APPPATH . 'data/flex/flex_refund_invoice_xml2csv.txt', FALSE, ',');
        $csv_file = "-\r\n";
        $csv_file .= "IndexNum,Line,Row,ProductCodeCopy,Header_TranType,Header_CDNTranType,Header_Date,Header_CIVNum,Header_CurrCode,Header_CustCode,SRNum,ProductCode,BaseQty,BGUnitPrice,InnType,CustRefNo,ShipLocCode,PlatformRefNo,CartonNo\r\n";
        $csv_file .= "-\r\n";
        $csv_file .= $this->dataExchangeService->convert($out_xml, $out_csv);

        if ($csv_file) {
            if (!is_dir($file_path . "/refund")) {
                if (!is_dir($file_path)) {
                    mkdir($file_path, 0775);
                    mkdir($file_path . "/refund", 0775);
                } else {
                    mkdir($file_path . "/refund", 0775);
                }
            }
        }
        switch ($type) {
            case "R":
                $filename = "refund_invoice.csv";
                break;
            case "CB":
                $filename = "chargeback_invoice.csv";
                break;
        }

        $fp = fopen($file_path . "/refund/" . $filename, 'w');
        if (!fwrite($fp, $csv_file)) {
            $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/refund/" . $filename;
            $message = "CONTENT: " . $csv_file . "<br> LINE: " . __LINE__;
            $this->errorHandler($subject, $message);
        }
    }

    public function getFlexRefundData($where)
    {
        $ri_list = $this->getDao('FlexRefund')->getRefunds($where, array('limit'=>'-1'));
        if ($ri_list) {
            foreach ($ri_list AS $obj) {
                $so_obj = $this->getDao('So')->get(["so_no" => $obj->getSoNo()]);
                $fr_obj = $this->getDao('FlexRia')->get(["so_no" => $obj->getSoNo()]);
                $obj->setAmount(abs($obj->getAmount()));
                if (!$fr_obj) {
                    $exception[$obj->getSoNo()]["obj"] = $obj;
                    $exception[$obj->getSoNo()]["failed_reason"] = "RIA record not found";
                } elseif ($fr_obj->getStatus() <> 'RIA') {
                    $exception[$obj->getSoNo()]["obj"] = $obj;
                    if (strtolower($fr_obj->getStatus()) == "sales") {
                        $reason = "orders already sales and shipped";
                    } elseif (strtolower($fr_obj->getStatus()) == "refunded") {
                        $reason = "orders already refunded";
                    } else {
                        $reason = "status not RIA";
                    }
                    $exception[$obj->getSoNo()]["failed_reason"] = $reason;
                } elseif ($so_obj->getStatus() == 6) {
                    $exception[$obj->getSoNo()]["obj"] = $obj;
                    $exception[$obj->getSoNo()]["failed_reason"] = "orders already shipped but not sales";
                } elseif (!($so_obj->getAmount() == $obj->getAmount() && $so_obj->getCurrencyId() == $obj->getCurrencyId())) {
                    $exception[$obj->getSoNo()]["obj"] = $obj;
                    $exception[$obj->getSoNo()]["failed_reason"] = "gateway amount mismatch";
                } elseif ($this->getDao('FlexRefund')->getNoOfRefundStatus($obj->getSoNo()) > 1) {
                    $exception[$obj->getSoNo()]["obj"] = $obj;
                    $exception[$obj->getSoNo()]["failed_reason"] = "more than one refund status";
                } else {
                    $data[] = $obj;
                }
                if ($fr_obj) {
                    $status = $fr_obj->getStatus();
                    if (($status != "SALES") && ($status != "REFUNDED")) {
                        $fr_obj->setStatus("REFUNDED");
                        $this->getDao('FlexRia')->update($fr_obj);
                    }
                }
            }
        }
        $result['data'] = $data;
        $result['exception'] = $exception;
        return $result;
    }

    public function getSoFeeInvoice($start_date, $end_date, $gateway_id)
    {
        DEFINE('REPORT_PATH', $this->contextConfigService->valueOf("flex_report_path"));
        if (is_file((REPORT_PATH . "so_fee.csv"))) {
            @unlink(REPORT_PATH . "so_fee.csv");
        }
        $where = [];
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));
        $where["fsf.txn_time >="] = $start_date . ' 00:00:00';
        $where["fsf.txn_time <="] = $end_date . ' 23:59:59';
        if ($gateway_id) {
            $where["fsf.gateway_id"] = $gateway_id;
        }
        $sfi_list = $this->getDao('FlexSoFee')->getSoFeeInvoice($where);
        if ($sfi_list) {
            foreach ($sfi_list as $sfi_dto) {
                $type = $sfi_dto->getType();

                $refund_list = $this->refund_list;
                $ria_list = $this->ria_list;
                $charge_back_list = array("Chargeback");
                $rolling_reserve_list = array("TH");

                if (in_array($type, $refund_list)) {
                    $sfi_dto->setType("Refund");
                } elseif (in_array($type, $ria_list)) {
                    $sfi_dto->setType("RIA");
                } elseif (in_array($type, $charge_back_list)) {
                    $sfi_dto->setType("Chargeback");
                } elseif (in_array($type, $rolling_reserve_list)) {
                    $sfi_dto->setType("Rolling_reserve_fee");
                }
                $percentage = abs(number_format($sfi_dto->getFee() / $sfi_dto->getOrderAmount() * 100, 2, '.', ''));
                $sfi_dto->setPercentage($percentage);
            }

            $file_path = REPORT_PATH;
            $mapping_file =  APPPATH . 'data/flex/flex_so_fee_invoice.txt';
            $csv_file .= $this->dataProcessService->ObjlistToCsv($sfi_list, $out_csv);
            $filename = "so_fee.csv";

            $fp = fopen($file_path . $filename, 'w');
            if (!fwrite($fp, $csv_file)) {
                $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/fee/" . $filename;
                $message = "CONTENT: " . $csv_file . "<br> LINE: " . __LINE__;
                $this->errorHandler($subject, $message);
            }
        }
    }

    public function getGatewayFeeInvoice($start_date, $end_date, $gateway_id)
    {
        DEFINE('REPORT_PATH', $this->contextConfigService->valueOf("flex_report_path"));
        if (is_file((REPORT_PATH . "gateway_fee.csv"))) {
            @unlink(REPORT_PATH . "gateway_fee.csv");
        }
        $where = [];
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));
        $where["txn_time >="] = $start_date . ' 00:00:00';
        $where["txn_time <="] = $end_date . ' 23:59:59';
        $option['limit'] = -1;
        if ($gateway_id) {
            $where["gateway_id"] = $gateway_id;
        }
        $fgf_list = $this->getDao('FlexGatewayFee')->getList(array_merge((array)$where, array("status IN ('FXI','FXO')" => NULL)), $option);
        if ($fgf_list) {
            $txn_array = [];
            foreach ($fgf_list AS $fgf_obj) {
                $txn_array[$fgf_obj->getTxnId()][$fgf_obj->getStatus()] = $fgf_obj;
            }
            foreach ($txn_array AS $txn_ref => $record_array) {
                $from_obj = $record_array["FXO"];
                $to_obj = $record_array["FXI"];
                $gfi_dto = clone $this->gatewayFeeInvoiceDto;
                $flex_rate = $this->getFlexRate($from_obj->getCurrencyId(), $to_obj->getCurrencyId());
                $diff = ($from_obj->getAmount() * $flex_rate) + $to_obj->getAmount();
                $diff_percent = number_format($diff / $to_obj->getAmount() * 100, 2, '.', '');
                $flex_gateway_code = $this->getFlexGatewayMapping($from_obj->getGatewayId(), $from_obj->getCurrencyId());
                $gfi_dto->setType("Exchange Diff.");
                $gfi_dto->setTxnTime($from_obj->getTxnTime());
                $gfi_dto->setFromCurrency($from_obj->getCurrencyId());
                $gfi_dto->setFromAmount($from_obj->getAmount());
                $gfi_dto->setGatewayId($flex_gateway_code);
                $gfi_dto->setBatchId($from_obj->getFlexBatchId());
                $gfi_dto->setToCurrency($to_obj->getCurrencyId());
                $gfi_dto->setToAmount($to_obj->getAmount());
                $gfi_dto->setDifference($diff);
                $gfi_dto->setPercentage($diff_percent);
                $gfi_dto->setTxnRef($from_obj->getTxnId());
                $report_list[] = $gfi_dto;
            }
        }

        $ria_list = $this->ria_list;
        $chargeback_list = $this->chargeback_list;
        $fee_list = $this->fee_list;
        $ps_list = $this->ps_list;
        $pr_list = $this->pr_list;
        $rr_list = $this->rr_list;
        $mf_list = $this->mf_list;
        $tr_list = $this->tr_list;

        $status_list = array_merge($ria_list, $chargeback_list, $fee_list, $ps_list, $pr_list, $rr_list, $mf_list, $tr_list);
        $status_list_str = "'" . implode("','", $status_list) . "'";
        $tfr_fgf_list = $this->getDao('FlexGatewayFee')->getList(array_merge((array)$where, array("status IN ({$status_list_str})" => NULL)), $option);
        if ($tfr_fgf_list) {
            $txn_array = [];
            foreach ($tfr_fgf_list AS $fgf_obj) {
                $gfi_dto = clone $this->get_gfi_dto();
                $flex_gateway_code = $this->getFlexGatewayMapping($fgf_obj->getGatewayId(), $fgf_obj->getCurrencyId());
                $type = $fgf_obj->getStatus();
                if (in_array($type, $ria_list)) {
                    $report_type = "RIA";
                } elseif (in_array($type, $chargeback_list)) {
                    $report_type = "Chargeback";
                } elseif (in_array($type, $fee_list)) {
                    $report_type = "Fee";
                } elseif (in_array($type, $ps_list)) {
                    $report_type = "Payment Sent";
                } elseif (in_array($type, $pr_list)) {
                    $report_type = "Payment Receive";
                } elseif (in_array($type, $rr_list)) {
                    $report_type = "Rolling Reserve";
                } elseif (in_array($type, $mf_list)) {
                    $report_type = "Monthly Transaction Fee";
                } elseif (in_array($type, $tr_list)) {
                    $report_type = "Transfer";
                } else {
                    $report_type = "Un-sorted Fee";
                }
                $gfi_dto->setType($report_type);
                $gfi_dto->setTxnTime($fgf_obj->getTxnTime());
                $gfi_dto->setFromCurrency($fgf_obj->getCurrencyId());
                $gfi_dto->setFromAmount($fgf_obj->getAmount());
                $gfi_dto->setGatewayId($flex_gateway_code);
                $gfi_dto->setBatchId($fgf_obj->getFlexBatchId());
                $gfi_dto->setTxnRef($fgf_obj->getTxnId());
                $report_list[] = $gfi_dto;
            }
        }

        if ($report_list) {
            $file_path = REPORT_PATH;
            $this->genGatewayFeeReport($report_list, $file_path);
        }
    }

    public function genGatewayFeeReport($report_list, $file_path)
    {
        $mapping_file = APPPATH . 'data/flex/flex_gateway_fee_invoice.php';
        $csv_file = $this->dataProcessService->ObjlistToCsv($report_list, $mapping_file);
        $filename = "gateway_fee.csv";
        $fp = fopen($file_path . $filename, 'w');
        if (!fwrite($fp, $csv_file)) {
            $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/fee/" . $filename;
            $message = "CONTENT: " . $csv_file . "<br> LINE: " . __LINE__;
            $this->errorHandler($subject, $message);
        } else {
            fclose($fp);
        }
    }

    public function getFlexRate($from_currency, $to_currency)
    {
        if ($obj = $this->getDao('ExchangeRateFlex')->get(array("from_currency_id" => $from_currency, "to_currency_id" => $to_currency))) {
            return $obj->getRate();
        } else {
            return 1;
        }
    }

    public function getFlexGatewayMapping($gateway_id, $currency_id)
    {
        if ($obj = $this->getDao('FlexGatewayMapping')->get(array("gateway_id" => $gateway_id, "currency_id" => $currency_id))) {
            return $obj->getGatewayCode();
        } else {
            return "";
        }
    }

    public function getRollingReserveReport($start_date, $end_date, $gateway_id)
    {
        DEFINE('REPORT_PATH', $this->contextConfigService->valueOf("flex_report_path"));
        if (is_file((REPORT_PATH . self::ROLLING_RESERVE_REPORT_FILE_NAME))) {
            @unlink(REPORT_PATH . self::ROLLING_RESERVE_REPORT_FILE_NAME);
        }
        $where = [];
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));
        $where["txn_time >="] = $start_date . ' 00:00:00';
        $where["txn_time <="] = $end_date . ' 23:59:59';
        $option['limit'] = -1;
        if ($gateway_id) {
            $where["gateway_id"] = $gateway_id;
        }
        $frr_list = $this->getDao('FlexRollingReserve')->getList($where, $option);
        $rrrList = [];
        foreach ($frr_list as $frr) {
            $rrrObj = $this->RollingReserveReportDto;
            $rrrObj->setSoNo($frr->getSoNo());
            $rrrObj->setBatchId($frr->getFlexBatchId());
            $flex_gateway_code = $this->getFlexGatewayMapping($frr->getGatewayId(), $frr->getCurrencyId());
            $rrrObj->setGatewayId($flex_gateway_code);
            $rrrObj->setTxnId($frr->getTxnId());
            $rrrObj->set_txn_date(date("Y-m-d", strtotime($frr->getTxnTime())));
            $rrrObj->setCurrencyId($frr->getCurrencyId());
            $rrrObj->setAmount($frr->getAmount());
            $rrrObj->setStatus($frr->getStatus());
            $so_obj = $this->getDao('So')->get(array("so_no" => $frr->getSoNo()));
            if ($frr->getStatus() == "RRR") {
                $rrObj_hold = $this->getDao('FlexRollingReserve')->get(array("so_no" => $frr->getSoNo(), "status" => "RRH"));
                if ($rrObj_hold)
                    $rrrObj->setHoldTime($rrObj_hold->getTxnTime());
            }
            $rrrObj->setOrderAmount($so_obj->getAmount());
            $rrrObj->setPercentage($frr->getAmount() / $so_obj->getAmount() * 100);
            array_push($rrrList, $rrrObj);
        }

        if ($rrrList) {
            $file_path = REPORT_PATH;
            $mapping_file = APPPATH . 'data/flex/flex_rolling_reserve.php';
            $csv_file = $this->dataProcessService->ObjlistToCsv($rrrList, $mapping_file);
            $filename = self::ROLLING_RESERVE_REPORT_FILE_NAME;
            $fp = fopen($file_path . $filename, 'w');
            if (!fwrite($fp, $csv_file)) {
                $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/fee/" . $filename;
                $message = "CONTENT: " . $csv_file . "<br> LINE: " . __LINE__;
                $this->errorHandler($subject, $message);
            } else
                fclose($fp);
        }
    }

    public function getPendingOrderReport($ship_date)
    {
        $where = [];
        if ($ship_date != '') {
            $where["(so.dispatch_date > '" . ($ship_date . ' 23:59:59') . "' or so.dispatch_date is null)"] = NULL;
            $where["ria.txn_time <="] = $ship_date . ' 23:59:59';
        }
        $fr_list = $this->getDao('FlexRia')->getPendingOrderReportList($where, array("orderby" => "ria.txn_time", "limit" => -1));
        if ($fr_list) {
            $where = [];
            if ($ship_date != '') {
                $where["txn_time <="] = $ship_date . ' 23:59:59';
            }
            $total = 0;
            foreach ($fr_list as $fr_obj) {
                $where["so_no"] = $fr_obj->getSoNo();
                $num_rows = $this->getDao('FlexRefund')->getNumRows($where);
                if ($num_rows == 0) {
                    $pending_list = $this->getDao('So')->getPendingOrderInfo(array("flex_batch_id" => $fr_obj->getFlexBatchId(), "fr.so_no" => $fr_obj->getSoNo()));
                    foreach ($pending_list as $obj) {
                        $report_list[date("Ymd", strtotime($obj->getTxnTime()))][$obj->getCurrencyId()][$obj->getGatewayId()][] = $obj;
                        $report_total[date("Ymd", strtotime($obj->getTxnTime()))][$obj->getCurrencyId()][$obj->getGatewayId()] += $obj->getAmount();
                    }
                }
            }

            if ($report_list) {
                $total = 0;
                foreach ($report_list as $date => $cur_list) {
                    foreach ($cur_list as $curr => $gateway_list) {
                        foreach ($gateway_list as $gateway_id => $obj_list) {
                            $obj_list[count($obj_list) - 1]->setTotal($report_total[$date][$curr][$gateway_id]);
                            foreach ($obj_list as $obj) {
                                $list[] = $obj;
                            }
                        }
                    }
                }
            }

            if ($list) {
                DEFINE('REPORT_PATH', $this->contextConfigService->valueOf("flex_report_path"));
                $file_path = REPORT_PATH;
                $mapping_file = APPPATH . 'data/flex/flex_pending_order_report.php';
                $csv_file = $this->dataProcessService->ObjlistToCsv($list, $mapping_file);
                $filename = "pending_order_report.csv";

                $fp = fopen($file_path . $filename, 'w');
                if (!fwrite($fp, $csv_file)) {
                    $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/pending_order_report/" . $filename;
                    $message = "CONTENT: " . $csv_file . "<br> LINE: " . __LINE__;
                    $this->errorHandler($subject, $message);
                }
            }
        }
    }

    public function reverseSalesInvoiceStatus($date)
    {
        $date = date("Y-m-d", strtotime($date));
        if (preg_match("/\d{4}-\d{2}-\d{2}/", trim($date))) {
            $where = [];
            $where["so.dispatch_date >= "] = $date. ' 00:00:00';
            $where["so.dispatch_date <= "] = $date. ' 23:59:59';
            $dispatched_order_list = $this->getDao('So')->getFlexSalesInvoice($where);
            foreach ($dispatched_order_list as $dispatched_order_obj) {
                if ($ria_obj = $this->getDao('FlexRia')->get(["so_no" => $dispatched_order_obj->getSoNo()])) {
                    $ria_obj->setStatus("RIA");
                    $this->getDao('FlexRia')->update($ria_obj);
                }
            }
        }
    }

    public function reverseRefundInvoiceStatus($date)
    {
        $date = date("Y-m-d", strtotime($date));
        if (preg_match("/\d{4}-\d{2}-\d{2}/", trim($date))) {
            $where = [];
            $date = date("Y-m-d", strtotime($date));
            $where["txn_time >="] = $date . ' 00:00:00';
            $where["txn_time <="] = $date . ' 23:59:59';
            if ($ri_list = $this->getDao('FlexRefund')->getList($where, ["limit" => -1])) {
                foreach ($ri_list as $refund_obj) {
                    if ($ria_obj = $this->getDao('FlexRia')->get(["so_no" => $refund_obj->getSoNo(), "status" => "REFUNDED"])) {
                        $ria_obj->setStatus("RIA");
                        $this->getDao('FlexRia')->update($ria_obj);
                    }
                }
            }
        }
    }

    public function platfromOrderInsertInterfaceFlexRia($gateway_id, $so_no_list)
    {
        $so_no_collect = '(' . implode(',', $so_no_list) . ')';
        $where = array("so_no IN {$so_no_collect}" => null);
        $option = ['limit' => -1];

        if (($so_obj_list = $this->getDao('So')->getList($where, $option))
            && ($flex_batch_obj = $this->getDao('FlexBatch')->get(['gateway_id' => $gateway_id]))
        ) {
            foreach ($so_obj_list as $so_obj) {
                $ifr_vo = $this->getDao('InterfaceFlexRia')->get();
                $ifr_obj = clone $ifr_vo;
                $ifr_obj->setSoNo($so_obj->getSoNo());
                $ifr_obj->setFlexBatchId($flex_batch_obj->getId());
                $ifr_obj->setGatewayId($flex_batch_obj->getGatewayId());
                $ifr_obj->setTxnId($so_obj->getTxnId());
                $ifr_obj->setTxnTime($so_obj->getOrderCreateDate());
                $ifr_obj->setAmount($so_obj->getAmount());
                $ifr_obj->setCurrencyId($so_obj->getCurrencyId());
                $ifr_obj->setStatus('RIA');
                $ifr_obj->set_batch_status('S');
                $this->getDao('InterfaceFlexRia')->insert($ifr_obj);
            }
        }
    }

    public function platformOrderDeleteInterfaceFlexRia($gateway_id, $so_no_list)
    {
        $so_no_collect = '(' . implode(',', $so_no_list) . ')';
        $where = ["so_no IN {$so_no_collect}" => null];
        $option = ['limit' => -1];
        if ($ifr_obj_list = $this->getDao('InterfaceFlexRia')->getList($where, $option)) {
            foreach ($ifr_obj_list as $ifr_obj) {
                $this->getDao('InterfaceFlexRia')->delete($ifr_obj);
            }
        }
    }

    public function WithBankTransferToFlexRia($sobt_obj)
    {
        if (($so_obj = $this->getDao('So')->get(['so_no' => $sobt_obj->getSoNo()]))
            && ($flex_batch_obj = $this->getDao('FlexBatch')->get(['gateway_id' => 'w_bank_transfer']))
        ) {

            if ($flex_ria_obj = $this->getDao('FlexRia')->get(['so_no' => $sobt_obj->getSoNo(), 'flex_batch_id' => $flex_batch_obj->getId()])) {
                $action = 'update';
            } else {
                $action = 'insert';
                $flex_ria_vo = $this->getDao('FlexRia')->get();
                $flex_ria_obj = clone $flex_ria_vo;
            }

            $flex_ria_obj->setSoNo($so_obj->getSoNo());
            $flex_ria_obj->setFlexBatchId($flex_batch_obj->getId());
            $flex_ria_obj->setGatewayId($flex_batch_obj->getGatewayId());
            $flex_ria_obj->setTxnId($sobt_obj->get_ext_ref_no());
            //
            $flex_ria_obj->setTxnTime(date("Y-m-d H:i:s"));
            if ($flex_ria_obj->getAmount()) {
                $flex_ria_obj->setAmount($sobt_obj->get_received_amt_localcurr() + $flex_ria_obj->getAmount());
            } else {
                $flex_ria_obj->setAmount($sobt_obj->get_received_amt_localcurr());
            }
            $flex_ria_obj->setCurrencyId($so_obj->getCurrencyId());
            $flex_ria_obj->setStatus('RIA');

            return $this->getDao('FlexRia')->$action($flex_ria_obj);
        }
    }

    public function platfromOrderInsert_flex_ria($gateway_id, $so_no)
    {
        if (($so_obj = $this->getDao('So')->get(['so_no' => $so_no]))
            && ($flex_batch_obj = $this->getDao('FlexBatch')->get(['gateway_id' => $gateway_id]))
        ) {

            if ($flex_ria_obj = $this->getDao('FlexRia')->get(['so_no' => $so_no, 'flex_batch_id' => $flex_batch_obj->getId()])) {
                $action = 'update';
            } else {
                $action = 'insert';
                $flex_ria_vo = $this->getDao('FlexRia')->get();
                $flex_ria_obj = clone $flex_ria_vo;
            }

            $flex_ria_obj->setSoNo($so_obj->getSoNo());
            $flex_ria_obj->setFlexBatchId($flex_batch_obj->getId());
            $flex_ria_obj->setGatewayId($flex_batch_obj->getGatewayId());
            $flex_ria_obj->setTxnId($so_obj->getTxnId());
            $flex_ria_obj->setTxnTime($so_obj->getOrderCreateDate());
            $flex_ria_obj->setAmount($so_obj->getAmount());
            $flex_ria_obj->setCurrencyId($so_obj->getCurrencyId());
            $flex_ria_obj->setStatus('RIA');

            return $this->getDao('FlexRia')->$action($flex_ria_obj);
        }
    }

    public function platfromOrderInsertFlexRefund($gateway_id, $refund_obj)
    {
        $so_no = $refund_obj->getSoNo();
        if (($so_obj = $this->getDao('So')->get(['so_no' => $so_no]))
            && ($flex_batch_obj = $this->getDao('FlexBatch')->get(['gateway_id' => $gateway_id]))
        ) {
            $flex_refund_vo = $this->getDao('FlexRefund')->get();
            $flex_refund_obj = clone $flex_refund_vo;
            $flex_refund_obj->setSoNo($so_no);
            $flex_refund_obj->setFlexBatchId($flex_batch_obj->getId());
            $flex_refund_obj->setGatewayId($flex_batch_obj->getGatewayId());
            $flex_refund_obj->set_internal_txn_id($so_obj->getTxnId());
            $flex_refund_obj->setTxnId($so_obj->getTxnId());
            $flex_refund_obj->setTxnTime($so_obj->getOrderCreateDate());
            $flex_refund_obj->setAmount($refund_obj->getTotalRefundAmount());
            $flex_refund_obj->setCurrencyId($so_obj->getCurrencyId());
            $flex_refund_obj->setStatus('R');
            return $this->getDao('FlexRefund')->insert($flex_refund_obj);
        }
    }

    public function getRakutenShippedOrder($platform_order_id)
    {
        $where = array(
            'so.status' => 6,
            'so.platform_id' => 'RAKUES',
            'ifr.so_no IS NULL' => null,
            "so.dispatch_date > '2014-10-31 23:59:59'" => null,
            "so.platform_order_id like '%{$platform_order_id}%'" => null
        );
        $so_list = $this->getDao('So')->getRakutenShippedOrder($where, ['limit' => -1]);
        return $so_list;
    }

    public function getRakutenShippedOrderFromInterface()
    {
        $where = array('ifr.gateway_id' => 'rakuten','fr.so_no IS NULL' => null);
        return $this->getDao('So')->getRakutenShippedOrderFromInterface($where, ['limit' => -1]);
    }
}