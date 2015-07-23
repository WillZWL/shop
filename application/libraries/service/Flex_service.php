<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Flex_service extends Base_service
{
    const ROLLING_RESERVE_REPORT_FILE_NAME = "rolling_reserve.csv";

    private $tlog_dao;
    private $batch_dao;

    public function __construct()
    {
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $this->set_dex_service(new Data_exchange_service());
        include_once(APPPATH . "libraries/dto/Sales_invoice_dto.php");
        $this->set_si_dto(new Sales_invoice_dto());
        include_once(APPPATH . "libraries/dto/Supplier_invoice_dto.php");
        $this->set_siv_dto(new Supplier_invoice_dto());
        include_once(APPPATH . "libraries/dto/Refund_invoice_dto.php");
        $this->set_riv_dto(new Refund_invoice_dto());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config_srv(new Context_config_service());
        include_once(APPPATH . "libraries/dao/Flex_batch_dao.php");
        $this->set_flex_batch_dao(new Flex_batch_dao());
        include_once(APPPATH . "libraries/dao/Flex_ria_dao.php");
        $this->set_flex_ria_dao(new Flex_ria_dao());
        include_once(APPPATH . "libraries/dao/Flex_refund_dao.php");
        $this->set_flex_refund_dao(new Flex_refund_dao());
        include_once(APPPATH . "libraries/dao/Supplier_prod_dao.php");
        $this->set_supplier_prod_dao(new Supplier_prod_dao());
        include_once(APPPATH . "libraries/dao/So_item_detail_dao.php");
        $this->set_so_item_detail_dao(new So_item_detail_dao());
        include_once(APPPATH . "libraries/dao/Flex_so_fee_dao.php");
        $this->set_fsf_dao(new Flex_so_fee_dao());
        include_once(APPPATH . "libraries/dao/Flex_gateway_fee_dao.php");
        $this->set_fgf_dao(new Flex_gateway_fee_dao());
        include_once(APPPATH . "libraries/dto/So_fee_invoice_dto.php");
        $this->set_sfi_dto(new So_fee_invoice_dto());
        include_once(APPPATH . "libraries/dto/Gateway_fee_invoice_dto.php");
        $this->set_gfi_dto(new Gateway_fee_invoice_dto());
        include_once(APPPATH . "libraries/dao/Exchange_rate_flex_dao.php");
        $this->set_erf_dao(new Exchange_rate_flex_dao());
        include_once(APPPATH . "libraries/dao/Flex_gateway_mapping_dao.php");
        $this->set_fgm_dao(new Flex_gateway_mapping_dao());
        include_once(APPPATH . "libraries/dao/Flex_rolling_reserve_dao.php");
        $this->set_frr_dao(new Flex_rolling_reserve_dao());
        include_once(APPPATH . "libraries/dao/Interface_flex_ria_dao.php");
        $this->set_ifr_dao(new Interface_flex_ria_dao());
    }

    public function set_so_dao(Base_dao $dao)
    {
        $this->so_dao = $dao;
    }

    public function set_dex_service($srv)
    {
        $this->dex_service = $srv;
    }

    public function set_si_dto(Base_dto $dto)
    {
        $this->si_dto = $dto;
    }

    public function set_siv_dto(Base_dto $dto)
    {
        $this->siv_dto = $dto;
    }

    public function set_riv_dto(Base_dto $dto)
    {
        $this->riv_dto = $dto;
    }

    public function set_config_srv($srv)
    {
        $this->config_srv = $srv;
    }

    public function set_flex_batch_dao(Base_dao $dao)
    {
        $this->flex_batch_dao = $dao;
    }

    public function set_flex_ria_dao(Base_dao $dao)
    {
        $this->flex_ria_dao = $dao;
    }

    public function set_flex_refund_dao(Base_dao $dao)
    {
        $this->flex_refund_dao = $dao;
    }

    public function set_supplier_prod_dao(Base_dao $dao)
    {
        $this->supplier_prod_dao = $dao;
    }

    public function set_so_item_detail_dao(Base_dao $dao)
    {
        $this->so_item_detail_dao = $dao;
    }

    public function set_fsf_dao(Base_dao $dao)
    {
        $this->fsf_dao = $dao;
    }

    public function set_fgf_dao(Base_dao $dao)
    {
        $this->fgf_dao = $dao;
    }

    public function set_sfi_dto(Base_dto $dto)
    {
        $this->sfi_dto = $dto;
    }

    public function set_gfi_dto(Base_dto $dto)
    {
        $this->gfi_dto = $dto;
    }

    public function set_erf_dao(Base_dao $dao)
    {
        $this->erf_dao = $dao;
    }

    public function set_fgm_dao(Base_dao $dao)
    {
        $this->fgm_dao = $dao;
    }

    public function set_frr_dao(Base_dao $dao)
    {
        $this->frr_dao = $dao;
    }

    public function set_ifr_dao(Base_dao $dao)
    {
        $this->ifr_dao = $dao;
    }

    public function generate_feedback_report($where, $option)
    {
        $feedback_report = array();

        $fri_obj_list = $this->get_flex_ria_dao()->get_list($where, $option);
        $fre_obj_list = $this->get_flex_refund_dao()->get_list($where, $option);
        $fgf_obj_list = $this->get_fgf_dao()->get_list($where, $option);
        $fsf_obj_list = $this->get_fsf_dao()->get_list($where, $option);
        $frr_obj_list = $this->get_frr_dao()->get_list($where, $option);

        if (!empty($fri_obj_list)) {
            $feedback_report['flex_ria']['name'] = 'flex_ria_feedback_report.csv';
            $feedback_report['flex_ria']['content'] = $this->flex_feedback_convert($fri_obj_list, 'flex_ria');
        }

        if (!empty($fre_obj_list)) {
            $feedback_report['flex_refund']['name'] = 'flex_refund_feedback_report.csv';
            $feedback_report['flex_refund']['content'] = $this->flex_feedback_convert($fre_obj_list, 'flex_refund');
        }

        if (!empty($fgf_obj_list)) {
            $feedback_report['flex_gateway_fee']['name'] = 'flex_gateway_fee_feedback_report.csv';
            $feedback_report['flex_gateway_fee']['content'] = $this->flex_feedback_convert($fgf_obj_list, 'flex_gateway_fee');
        }

        if (!empty($fsf_obj_list)) {
            $feedback_report['flex_so_fee']['name'] = 'flex_so_fee_feedback_report.csv';
            $feedback_report['flex_so_fee']['content'] = $this->flex_feedback_convert($fsf_obj_list, 'flex_so_fee');
        }

        if (!empty($frr_obj_list)) {
            $feedback_report['flex_rolling_reserve']['name'] = 'flex_rolling_reserve_feedback_report.csv';
            $feedback_report['flex_rolling_reserve']['content'] = $this->flex_feedback_convert($frr_obj_list, 'flex_rolling_reserve');
        }

        $data['result'] = $feedback_report;
        $this->write_file($data, 'report', 'feedback_report', false);

        DEFINE('REPORT_PATH', $this->get_config_srv()->value_of("flex_report_path"));
        $this->generate_zip_file(REPORT_PATH . 'feedback_report/report/', 'feedback_report.zip');

        return array('filename' => 'feedback_report.zip', 'file_path' => REPORT_PATH . 'feedback_report/report/feedback_report.zip');
    }

    public function get_flex_ria_dao()
    {
        return $this->flex_ria_dao;
    }

    public function get_flex_refund_dao()
    {
        return $this->flex_refund_dao;
    }

    public function get_fgf_dao()
    {
        return $this->fgf_dao;
    }

    public function get_fsf_dao()
    {
        return $this->fsf_dao;
    }

    public function get_frr_dao()
    {
        return $this->frr_dao;
    }

    public function flex_feedback_convert($list, $flex_type)
    {
        switch ($flex_type) {
            case 'flex_ria':
                $out_xml = new Vo_to_xml($list, APPPATH . 'data/flex_ria_feedback_report_vo2xml.txt');
                $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_ria_feedback_report_xml2csv.txt');
                break;
            case 'flex_refund':
                $out_xml = new Vo_to_xml($list, APPPATH . 'data/flex_refund_feedback_report_vo2xml.txt');
                $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_refund_feedback_report_xml2csv.txt');
                break;
            case 'flex_gateway_fee':
                $out_xml = new Vo_to_xml($list, APPPATH . 'data/flex_gateway_fee_feedback_report_vo2xml.txt');
                $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_gateway_fee_feedback_report_xml2csv.txt');
                break;
            case 'flex_so_fee':
                $out_xml = new Vo_to_xml($list, APPPATH . 'data/flex_so_fee_feedback_report_vo2xml.txt');
                $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_so_fee_feedback_report_xml2csv.txt');
                break;
            case 'flex_rolling_reserve':
                $out_xml = new Vo_to_xml($list, APPPATH . 'data/flex_rolling_reserve_feedback_report_vo2xml.txt');
                $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_rolling_reserve_feedback_report_xml2csv.txt');
                break;

            default:
                $out_xml = new Vo_to_xml();
                $out_csv = new Xml_to_csv();
                break;
        }

        return $this->get_dex_service()->convert($out_xml, $out_csv);
    }

    public function get_dex_service()
    {
        return $this->dex_service;
    }

    public function write_file($data, $type, $folder_name, $gen_exception_only = TRUE)
    {
        DEFINE('REPORT_PATH', $this->get_config_srv()->value_of("flex_report_path"));
        $file_path = REPORT_PATH . $folder_name;

        if ($data) {
            foreach ($data as $pmgw => $si_list_w_currency) {
                foreach ($si_list_w_currency as $currency_id => $list) {
                    if ($gen_exception_only) {
                        if ($pmgw != "Exception") {
                            continue;
                        }
                    }

                    if (!is_dir($file_path . "/" . $type)) {
                        if (!is_dir($file_path)) {
                            mkdir($file_path, 0775);
                            mkdir($file_path . "/" . $type, 0775);
                        } else {
                            mkdir($file_path . "/" . $type, 0775);
                        }
                    }

                    $fp = fopen($file_path . "/" . $type . "/" . $list["name"], 'w');
                    if (!fwrite($fp, $list["content"])) {
                        $subject = "<DO NOT REPLY>Fail to write file - " . $path;
                        $message = "CONTENT: " . $content . "<br>
                                     LINE: " . __LINE__;
                        $this->error_handler($subject, $message);
                        return FALSE;
                    }
                }
            }

            return TRUE;
        }

        return FALSE;
    }

    public function get_config_srv()
    {
        return $this->config_srv;
    }

    public function error_handler($subject = '', $msg = '', $is_dead = false)
    {
        //echo $msg;
        if ($subject) {
            mail($this->get_contact_email(), $subject,
                $msg, 'From: itsupport@eservicesgroup.net');
        }

        if ($is_dead) {
            exit;
        }
    }

    public function get_contact_email()
    {
        return 'handy.hon@eservicesgroup.com';
    }

    public function generate_zip_file($file_path, $zip_name)
    {
        $files_to_zip = array();
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
        $result = $this->create_zip($files_to_zip, $zip_name, $file_path);
        //after create the zip file, delete the original ones
        if (isset($file_to_delete)) {
            foreach ($file_to_delete as $file) {
                @unlink($file_path . $file);
            }
        }
    }

    function create_zip($files = array(), $destination = '', $location = '', $overwrite = true)
    {
        if (file_exists($location . $destination) && !$overwrite) {
            return false;
        }
        $valid_files = array();
        if (is_array($files)) {
            foreach ($files as $file) {
                if (file_exists($location . $file)) {
                    $valid_files[] = $file;
                }
            }
        }
        if (count($valid_files)) {
            $zip = new ZipArchive();
            if ($zip->open($location . $destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
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

    public function get_sales_invoice($start_date, $end_date, $folder_name, $gen_exception_only = TRUE, $ignore_status = FALSE)
    {
        $gen_exception_only = FALSE;

        if (!$folder_name) {
            $folder_name = $start_date;
        }

        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));

        $where = array();

        //using so.finance_dispatch_date to replace this. but in test, using the so.dispatch_date

        $where["start_date"] = $start_date;
        $where["end_date"] = $end_date;

        $si_list = $this->get_so_dao()->get_flex_sales_invoice($where);
        //$si_list = $this->get_so_dao()->get_flex_sales_invoice($start_date, $end_date);
        // var_dump($this->get_so_dao()->db->last_query());die();

        if ($si_list) {
            $platform_arr = array('QOO10SG', 'RAKUES', 'FNACES', 'LAMY', 'LAZTH', 'LAZPH', 'NEUS');

            // SBF 4292, aps order need classified into three categories
            // 1, change tran_code and cust_code
            // 2, tran_code and cust_code follow origin order
            // 3, as exception order
            $order_reason_category = array(
                '1' => array('6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18'),
                '2' => array('19', '20', '21', '22'),
                '3' => array('23', '32')
            );

            foreach ($si_list AS $key => $obj) {
                $so_no = $obj->get_so_no();
                $is_split_order = false;

                if ($origin_so_no = $obj->get_split_so_group()) {
                    // SBF 5533 split order
                    $is_split_order = true;
                    // CustRefNo display origin so_no
                    $obj->set_so_no($origin_so_no);
                    if ($fr_obj = $this->get_flex_ria_dao()->get_flex_ria_w_gateway_mapping(array("so_no" => $origin_so_no))) {
                        $obj->set_report_pmgw($fr_obj->get_report_pmgw());
                        $obj->set_tran_type($fr_obj->get_tran_type());
                        $obj->set_flex_batch_id($fr_obj->get_flex_batch_id());
                        $obj->set_txn_time($fr_obj->get_txn_time());
                        $obj->set_txn_id($fr_obj->get_txn_id());
                    }
                } else {
                    $fr_obj = $this->get_flex_ria_dao()->get(array("so_no" => $so_no, "flex_batch_id" => $obj->get_flex_batch_id()));
                }

                $so_obj = $this->get_so_dao()->get(array("so_no" => $so_no));

                $platform_id = $obj->get_platform_id();
                $is_platform = in_array($platform_id, $platform_arr);

                if ($so_obj->get_biz_type() == "SPECIAL") {
                    $order_reason = $this->get_so_dao()->get_so_w_reason(array('so.so_no' => $so_no), array('limit' => 1));
                    $obj->set_reason($order_reason->get_reason_display_name());
                    $obj->set_remark('Speical Order');

                    $reason_id = $obj->get_order_reason();
                    if (in_array($reason_id, $order_reason_category['1'])) {
                        $this->reformat_special_order_data($obj, $reason_id);
                        $special_order[] = $obj;
                    } elseif (in_array($reason_id, $order_reason_category['2'])) {
                        if ($fr_obj = $this->get_flex_ria_dao()->get_flex_ria_w_gateway_mapping(array("so_no" => $obj->get_parent_so_no()))) {
                            $obj->set_report_pmgw($fr_obj->get_report_pmgw());
                            $obj->set_tran_type($fr_obj->get_tran_type());
                        }
                        $special_order[] = $obj;
                    } else {
                        $exception[] = $obj;
                    }
                } elseif ($fr_obj && ($fr_obj->get_currency_id() <> $so_obj->get_currency_id()) && !$is_platform) {
                    $obj->set_remark("Inconsistent Currency");
                    $exception[] = $obj;
                } elseif (!$is_split_order && $fr_obj && ($fr_obj->get_amount() <> $so_obj->get_amount()) && !$is_platform) {
                    $obj->set_remark("Inconsistent Amount");
                    $exception[] = $obj;
                } elseif ($fr_obj && ($fr_obj->get_status() <> 'RIA') && !$ignore_status && !$is_platform) {
                    $obj->set_remark("Not RIA Status");
                    $exception[] = $obj;
                } elseif (!$fr_obj && !$is_platform) {
                    $obj->set_remark("RIA NOT FOUND");
                    $exception[] = $obj;
                } elseif (!$obj->get_report_pmgw() && !$is_platform) {
                    // if($so_obj->get_biz_type() == "SPECIAL") {
                    //  //may be APS order, add the order note for clarification
                    //  $order_notes_obj_list = $this->get_order_notes_service()->get_list(array("so_no"=>$so_obj->get_so_no()));

                    //  $order_note = "";
                    //  foreach($order_notes_obj_list as $order_note_obj)
                    //  {
                    //      $order_note .= "::".$order_note_obj->get_note();
                    //  }
                    //  $obj->set_remark("Is Special Order".$order_note);
                    // } else {
                    $obj->set_remark("Missing Payment Gateway Code");
                    // }

                    $exception[] = $obj;
                } else {
                    $this->platform_reform_data($obj);
                    $data[$obj->get_report_pmgw()][$obj->get_currency_id()][] = $obj;
                }
            }

            $flex_invoice_list = array();

            if ($data) {
                $group_content = array();
                foreach ($data AS $pmgw => $si_list_w_currency) {
                    foreach ($si_list_w_currency AS $currency_id => $list) {
                        $group_content = array_merge($group_content, $list);

                        $flex_list = $this->set_flex_format($list);
                        $csv_file = $this->convert($flex_list, FALSE);

                        $file_name = $pmgw . "_" . $currency_id . ".csv";

                        $file = $this->assemble_sale_report($file_name, $csv_file);

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
                $exception_list = $this->set_flex_format($exception);
                // var_dump($exception_list);die();
                $csv_file = $this->convert($exception_list, TRUE, TRUE);
                $file["name"] = "Exception.csv";
                $file["content"] = $csv_file;
                $result["Exception"]["ALL"] = $file;

                if (!$gen_exception_only) {
                    $flex_invoice_list["EXCEPTION"] = $exception_list;
                }
            }

            if ($special_order) {
                $special_order_list = $this->set_flex_format($special_order);
                // var_dump($special_order_list);die();

                $temp_h = array();
                foreach ($special_order_list as $temp_order) {
                    $t_tran_type = $temp_order->get_tran_type();
                    $temp_h[$t_tran_type][] = $temp_order;
                }
                unset($special_order_list);

                $assemble_speical_order = array();
                foreach ($temp_h as $item) {
                    $assemble_speical_order = array_merge($assemble_speical_order, $item);
                }
                unset($temp_h);

                $temp_h = array();
                $index_num = 1;
                $product_line = 1;
                $last_tran_type = '';

                foreach ($assemble_speical_order as $obj) {
                    if ($last_tran_type != $obj->get_tran_type()) {
                        $product_line = 1;
                        $last_tran_type = $obj->get_tran_type();
                    }

                    $obj->set_index_no($index_num);
                    $obj->set_product_line($product_line);

                    $row_no = ($product_line - 1) ? ($product_line - 1) : "";
                    $obj->set_row_no($row_no);

                    $index_num++;
                    $product_line++;
                    // $temp_h[] = $t;
                }

                $csv_file = $this->convert($assemble_speical_order, false, true);
                $file_name = "special_order.csv";
                $file = $this->assemble_sale_report($file_name, $csv_file, 2);
                $result["SPECIAL_ORDER"]["ALL"] = $file;
                if (!$gen_exception_only) {
                    $flex_invoice_list['SPECIAL_ORDER'] = $assemble_speical_order;
                }
            }

            if ($exception && $gen_exception_only) {
                $this->write_file($result, "sales", $folder_name, TRUE);
                return false;
            } else {
                if ($this->write_file($result, "sales", $folder_name, FALSE)) {
                    $err = 0;

                    foreach ($flex_invoice_list as $status => $fi_list) {
                        if ($fi_list) {
                            foreach ($fi_list as $obj) {
                                if ($flex_ria_obj_list = $this->get_flex_ria_dao()->get_list(array("flex_batch_id" => $obj->get_flex_batch_id(), "so_no" => $obj->get_so_no()), array("limit" => -1))) {
                                    foreach ($flex_ria_obj_list as $flex_ria_obj) {
                                        if ($flex_ria_obj->get_status() == "SALES") {
                                            $err = 1;
                                            $message = $obj->get_flex_batch_id() . "," . $obj->get_so_no() . "\r\n";
                                        } else {
                                            $flex_ria_obj->set_status('SALES');
                                            $this->get_flex_ria_dao()->update($flex_ria_obj);
                                        }
                                    }

                                }
                                if ($obj->get_master_sku() == "") {
                                    $inventory[$status]["NIL"] = $inventory[$status]["NIL"] + $obj->get_qty();
                                } else {
                                    $inventory[$status][$obj->get_master_sku()] = $inventory[$status][$obj->get_master_sku()] + $obj->get_qty();
                                }
                            }
                        }
                    }

                    //grouping file, 900 line per report
                    if ($group_content) {
                        $index = 0;
                        $flex_list = $this->set_flex_format($group_content);
                        $segement_array = array_chunk($flex_list, 900);
                        foreach ($segement_array as $s) {
                            $temp_h = array();
                            $index_num = 1;
                            $product_line = 1;
                            $last_pmgw = "";

                            foreach ($s as $t) {
                                if ($last_pmgw != $t->get_report_pmgw()) {
                                    $product_line = 1;
                                    $last_pmgw = $t->get_report_pmgw();
                                }
                                $t->set_index_no($index_num);
                                $t->set_product_line($product_line);

                                $row_no = ($product_line - 1) ? ($product_line - 1) : "";
                                $t->set_row_no($row_no);

                                $index_num++;
                                $product_line++;
                                $temp_h[] = $t;
                            }

                            $csv_file = $this->convert($temp_h, FALSE);

                            $index++;
                            $file_name = "grouping_file_" . $index . ".csv";

                            $file = $this->assemble_sale_report($file_name, $csv_file);
                            //in order to use the write_file function, define a variable name $result['G']['G']
                            $result['G']['G'] = $file;
                            $this->write_file($result, 'sales', $folder_name, $gen_exception_only = FALSE);
                        }
                    }

                    $this->get_inventory_report($inventory, $end_date, $folder_name);
                    return true;
                }
            }
        }

        return false;
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function reformat_special_order_data($dto, $reason_id)
    {
        if (empty($reason_id)) {
            return;
        }

        switch ($reason_id) {
            case '6':
            case '10':
                $dto->set_tran_type("REPLACEI");
                $dto->set_report_pmgw("REPLACE");
                break;
            case '7':
            case '8':
                $dto->set_tran_type("REPLASHI");
                $dto->set_report_pmgw("REPLASH");
                break;
            case '9':
            case '11':
            case '12':
            case '13':
            case '14':
                $dto->set_tran_type("REPLAOLI");
                $dto->set_report_pmgw("REPLAOL");
                break;
            case '15':
            case '16':
            case '17':
            case '18':
                $dto->set_tran_type("CSI");
                $dto->set_report_pmgw("COMP&GIFT");
                break;

            default:
                break;
        }
    }

    public function platform_reform_data($dto)
    {
        $platform_id = $dto->get_platform_id();

        switch ($platform_id) {
            case 'QOO10SG':
                $dto->set_tran_type("QOO1" . $dto->get_currency_id() . "I");
                $dto->set_report_pmgw("QOO1" . $dto->get_currency_id());
                //use order create time as the txn_time
                $dto->set_txn_time($dto->get_order_create_date());
                break;
            case 'RAKUES':
                $dto->set_tran_type("RAKU" . $dto->get_currency_id() . "I");
                $dto->set_report_pmgw("RAKU" . $dto->get_currency_id());
                $dto->set_txn_time($dto->get_order_create_date());
                break;
            case 'FNACES':
                $dto->set_tran_type("FNES" . $dto->get_currency_id() . "I");
                $dto->set_report_pmgw("FNES" . $dto->get_currency_id());
                $dto->set_txn_time($dto->get_order_create_date());
                break;
            case 'LAMY':
            case 'LAZTH':
            case 'LAZPH':
                $dto->set_tran_type("LZMY" . $dto->get_currency_id() . "I");
                $dto->set_report_pmgw("LZMY" . $dto->get_currency_id());
                $dto->set_txn_time($dto->get_order_create_date());
                break;
            case 'NEUS':
                $dto->set_tran_type("NEUS" . $dto->get_currency_id() . "I");
                $dto->set_report_pmgw("NEUS" . $dto->get_currency_id());
                $dto->set_txn_time($dto->get_order_create_date());
                break;
            default:
                break;
        }
    }

    public function set_flex_format($list)
    {
        $index_no = $product_line = $i = 1;
        $sku = "";
        $delivery_list = array();

        $re_arrange_container = array();

        foreach ($list AS $obj) {
            $obj->set_row_no($product_line - 1);

            if ($product_line - 1 == 0) {
                $obj->set_row_no("");
            } else {
                $obj->set_row_no($product_line - 1);
            }

            $obj->set_product_line($product_line);


            $obj->set_index_no($index_no);
            $txn_time = date("Y-m", strtotime($obj->get_txn_time()));
            //by nero, change the unit price. Not directly get from unit_price field from so_item_detail
            //but calculated by divide amount to qty. reason: sometime when promotion is involved, the
            //unit_price * qty do not match the order amount.
            $obj->set_unit_price($obj->get_amount() / $obj->get_qty());
            $product_line++;
            $index_no++;

            $re_arrange_container[] = $obj;

            if ($obj->get_delivery_charge() > 0 && (!in_array($obj->get_so_no(), $delivery_list))) {
                $si_dto = clone $this->get_si_dto();
                //Purchasing provide a master sku for delivery charge, 20971-AA-NA
                $si_dto->set_master_sku("20971-AA-NA");
                $si_dto->set_dispatch_date($obj->get_dispatch_date());
                $si_dto->set_currency_id($obj->get_currency_id());
                $si_dto->set_report_pmgw($obj->get_report_pmgw());
                $si_dto->set_tran_type($obj->get_report_pmgw() . "I");
                $si_dto->set_flex_batch_id($obj->get_flex_batch_id());
                $si_dto->set_product_code("20971-AA-NA");
                $si_dto->set_qty(1);
                $si_dto->set_unit_price($obj->get_delivery_charge());
                $si_dto->set_txn_time($obj->get_txn_time());
                $si_dto->set_so_no($obj->get_so_no());
                $si_dto->set_txn_id($obj->get_txn_id());
                $si_dto->set_customer_email($obj->get_customer_email());
                $si_dto->set_sm_code($obj->get_sm_code());
                $si_dto->set_contain_size($obj->get_contain_size());

                $si_dto->set_index_no($index_no);
                $si_dto->set_product_line($product_line);
                $si_dto->set_row_no($product_line - 1);

                $product_line++;
                $index_no++;
                $re_arrange_container[] = $si_dto;
                $list[] = $si_dto;
                $delivery_list[] = $obj->get_so_no();
            }
        }

        return $re_arrange_container;
    }

    public function get_si_dto()
    {
        return $this->si_dto;
    }

    public function convert($list = array(), $first_line_headling = TRUE, $is_exception = FALSE)
    {
        $out_xml = new Vo_to_xml($list, APPPATH . 'data/flex_sales_invoice_vo2xml.txt');
        if ($is_exception) {
            $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_sales_exception_xml2csv.txt', $first_line_headling, ',');
        } else {
            $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_sales_invoice_xml2csv.txt', $first_line_headling, ',');
        }
        return $this->get_dex_service()->convert($out_xml, $out_csv);
    }

    /**
     * assemble report head line
     *
     * @param  string $file_name
     * @param  string $csv_file csv content
     * @param  int $format head line format. 1, normal; 2, special order
     *
     * @return string               comma format string
     */
    private function assemble_sale_report($file_name, $csv_file, $format = 1)
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

    public function get_inventory_report($inv_list, $dispatch_date, $folder_name)
    {
        $index_no = $product_line = 1;
        foreach ($inv_list as $status => $list) {
            $report_list = array();
            foreach ($list as $sku => $qty) {
                if ($sku <> 'NIL') {
                    if (!$ret = $this->get_supplier_prod_dao()->get_supplier_cost_by_sku_date($sku, $dispatch_date)) {
                        $ret = $this->get_supplier_prod_dao()->get_current_supplier_cost($sku);
                    }

                    $siv_dto = clone $this->get_siv_dto();

                    $siv_dto->set_index_no($index_no);
                    $siv_dto->set_product_line($product_line);
                    $siv_dto->set_row_no(($product_line - 1) == 0 ? "" : $product_line - 1);
                    $siv_dto->set_master_sku($sku);
                    $siv_dto->set_dispatch_date($dispatch_date);
                    $siv_dto->set_currency_id($ret["currency_id"]);
                    //$siv_dto->set_currency_id("HKD");
                    $siv_dto->set_siv($dispatch_date);
                    $siv_dto->set_product_code($sku);
                    $siv_dto->set_qty($qty);
                    $siv_dto->set_unit_price($ret['cost']);
                    //$siv_dto->set_unit_price(0);
                    $report_list[] = $siv_dto;
                }
                $index_no++;
                $product_line++;
            }

            DEFINE('REPORT_PATH', $this->get_config_srv()->value_of("flex_report_path"));
            $file_path = REPORT_PATH . $folder_name;

            $out_xml = new Vo_to_xml($report_list, APPPATH . 'data/flex_supplier_invoice_vo2xml.txt');
            $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_supplier_invoice_xml2csv.txt', FALSE, ',');
            $csv_file = "-\r\n";
            $csv_file .= "IndexNum,Line,Row,ProductCodeCopy,Header_TranType,Header_Date,Header_CurrCode,Header_SupCode,SivNum,ProductCode,BaseQty,BGUnitPrice,ShipLocCode\r\n";
            $csv_file .= "-\r\n";
            $csv_file .= $this->get_dex_service()->convert($out_xml, $out_csv);

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
                $message = "CONTENT: " . $csv_file . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
                return FALSE;
            }
        }
    }

    public function get_supplier_prod_dao()
    {
        return $this->supplier_prod_dao;
    }

    public function get_siv_dto()
    {
        return $this->siv_dto;
    }

    public function get_refund_invoice($start_date, $end_date, $type = "R", $folder_name)
    {
        if (!$folder_name) {
            $folder_name = $start_date;
        }

        $where = array();
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));
        $where["status"] = $type;
        $where["txn_time >="] = $start_date . ' 00:00:00';
        $where["txn_time <="] = $end_date . ' 23:59:59';

        //$ri_list = $this->get_flex_refund_dao()->get_list($where, array("orderby"=>"flex_batch_id", "limit"=>-1));
        $ri_list = $this->get_flex_refund_dao()->get_refunds($where, array("orderby" => "flex_batch_id", "limit" => -1));
        //var_dump($ri_list);die();
        //var_dump($this->get_flex_refund_dao()->db->last_query());die();
        if ($ri_list) {
            foreach ($ri_list AS $obj) {
                $so_obj = $this->get_so_dao()->get(array("so_no" => $obj->get_so_no()));
                $fr_obj = $this->get_flex_ria_dao()->get(array("so_no" => $obj->get_so_no()));

                //if($obj->get_gateway_id() == "moneybookers")
                //{
                $obj->set_amount(abs($obj->get_amount()));
                //}

                if (!$fr_obj) {
                    $exception[$obj->get_so_no()]["obj"] = $obj;
                    $exception[$obj->get_so_no()]["failed_reason"] = "RIA record not found";
                } elseif ($fr_obj->get_status() <> 'RIA') {
                    $exception[$obj->get_so_no()]["obj"] = $obj;
                    if (strtolower($fr_obj->get_status()) == "sales") {
                        $reason = "orders already sales and shipped";
                    } elseif (strtolower($fr_obj->get_status()) == "refunded") {
                        $reason = "orders already refunded";
                    } else {
                        $reason = "status not RIA";
                    }
                    $exception[$obj->get_so_no()]["failed_reason"] = $reason;
                } elseif ($so_obj->get_status() == 6) {
                    $exception[$obj->get_so_no()]["obj"] = $obj;
                    $exception[$obj->get_so_no()]["failed_reason"] = "orders already shipped but not sales";
                } elseif (!($so_obj->get_amount() == $obj->get_amount() && $so_obj->get_currency_id() == $obj->get_currency_id())) // system total refund amount diff from gateway amount
                {
                    $exception[$obj->get_so_no()]["obj"] = $obj;
                    $exception[$obj->get_so_no()]["failed_reason"] = "gateway amount mismatch";
                } //following is actually checking if the same order be refunded and chargeback multiple time
                elseif ($this->get_flex_refund_dao()->get_no_of_refund_status($obj->get_so_no()) > 1) // there are more than one refund status for this order
                {
                    $exception[$obj->get_so_no()]["obj"] = $obj;
                    $exception[$obj->get_so_no()]["failed_reason"] = "more than one refund status";
                } else {
                    $data[] = $obj;
                }

                //by nero, update the flex_ria table if those record is found in the flex_refund table
                //but keep the success "sales" unchanged

                if ($fr_obj) {
                    $status = $fr_obj->get_status();
                    if (($status != "SALES") && ($status != "REFUNDED")) {
                        $fr_obj->set_status("REFUNDED");
                        $this->get_flex_ria_dao()->update($fr_obj);
                    }
                }
            }
        }

        if ($data) {
            $index_no = $product_line = 1;
            $gen_refund_report = FALSE;

            foreach ($data as $obj) {
                if ($item_list = $this->get_so_dao()->get_flex_refund_invoice(array("frf.so_no" => $obj->get_so_no(), "frf.status" => $type))) {
                    $fr_obj = $this->get_flex_ria_dao()->get(array("so_no" => $obj->get_so_no()));

                    foreach ($item_list as $obj) {
                        $gen_refund_report = TRUE;
                        $riv_dto = clone $this->get_riv_dto();
                        if ($txn_id <> $obj->get_txn_id()) {
                            $product_line = 1;
                        }
                        $riv_status = "";
                        if ($type == 'CB') {
                            $riv_status = 'C';
                        } else {
                            $riv_status = $type;
                        }
                        $riv_dto->set_index_no($index_no);
                        $riv_dto->set_product_line($product_line);
                        $riv_dto->set_row_no(($product_line - 1) == 0 ? "" : $product_line - 1);
                        $riv_dto->set_master_sku($obj->get_master_sku());
                        $riv_dto->set_product_code($obj->get_master_sku());
                        $riv_dto->set_flex_batch_id($obj->get_flex_batch_id());
                        $riv_dto->set_txn_time(date("Y-m-d", strtotime($obj->get_txn_time())));
                        $riv_dto->set_currency_id($obj->get_currency_id());
                        $riv_dto->set_report_pmgw($obj->get_report_pmgw());
                        $riv_dto->set_unit_price($obj->get_unit_price());
                        $riv_dto->set_qty($obj->get_qty());
                        $riv_dto->set_txn_id($obj->get_txn_id());
                        $riv_dto->set_so_no($obj->get_so_no());
                        $riv_dto->set_tran_type($obj->get_report_pmgw() . $riv_status);
                        $riv_dto->set_ria_txn_time($fr_obj->get_txn_time());
                        $riv_dto->set_sr_num("SR" . date("Ymd", strtotime($obj->get_txn_time())) . "01");
                        $report_list[] = $riv_dto;

                        $txn_id = $obj->get_txn_id();
                        $index_no++;
                        $product_line++;
                    }
                }
            }

            if ($gen_refund_report) {
                DEFINE('REPORT_PATH', $this->get_config_srv()->value_of("flex_report_path"));
                $file_path = REPORT_PATH . $folder_name;

                $out_xml = new Vo_to_xml($report_list, APPPATH . 'data/flex_refund_invoice_vo2xml.txt');
                $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_refund_invoice_xml2csv.txt', FALSE, ',');
                $csv_file = "-\r\n";
                $csv_file .= "IndexNum,Line,Row,ProductCodeCopy,Header_TranType,Header_CDNTranType,Header_Date,Header_CIVNum,Header_CurrCode,Header_CustCode,SRNum,ProductCode,BaseQty,BGUnitPrice,InnType,CustRefNo,ShipLocCode,PlatformRefNo,CartonNo\r\n";
                $csv_file .= "-\r\n";
                $csv_file .= $this->get_dex_service()->convert($out_xml, $out_csv);

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
                    $message = "CONTENT: " . $csv_file . "<br>
                                 LINE: " . __LINE__;
                    $this->error_handler($subject, $message);
                }
            }
        }

        if ($exception) {
            $report_list = array();
            $gen_exception_report = FALSE;
            foreach ($exception as $ex) {
                $obj = $ex["obj"];
                $index_no = $product_line = 1;

                $where = array();
                $where["frf.status"] = $type;
                $where["txn_time >="] = $start_date . ' 00:00:00';
                $where["txn_time <="] = $end_date . ' 23:59:59';
                $where["frf.so_no"] = $obj->get_so_no();


                if ($item_list = $this->get_so_dao()->get_flex_refund_invoice($where)) {
                    $fr_obj = $this->get_flex_ria_dao()->get(array("so_no" => $obj->get_so_no()));

                    $gen_exception_report = TRUE;
                    foreach ($item_list as $obj) {
                        $riv_dto = clone $this->get_riv_dto();
                        if ($txn_id <> $obj->get_txn_id()) {
                            $product_line = 1;
                        }

                        $riv_dto->set_index_no($index_no);
                        $riv_dto->set_product_line($product_line);
                        $riv_dto->set_row_no(($product_line - 1) == 0 ? "" : $product_line - 1);
                        $riv_dto->set_master_sku($obj->get_master_sku());
                        $riv_dto->set_product_code($obj->get_master_sku());
                        $riv_dto->set_flex_batch_id($obj->get_flex_batch_id());
                        $riv_dto->set_txn_time($obj->get_txn_time());
                        $riv_dto->set_currency_id($obj->get_currency_id());
                        $riv_dto->set_report_pmgw($obj->get_report_pmgw());
                        $riv_dto->set_unit_price($obj->get_unit_price());
                        $riv_dto->set_qty($obj->get_qty());
                        $riv_dto->set_txn_id($obj->get_txn_id());
                        $riv_dto->set_so_no($obj->get_so_no());
                        $riv_dto->set_tran_type($obj->get_tran_type());
                        $riv_dto->set_sr_num("SR" . date("Ymd", $obj->get_txn_time()) . "01");
                        $riv_dto->set_failed_reason($ex["failed_reason"]);
                        $riv_dto->set_gateway_id($obj->get_gateway_id());
                        if ($fr_obj) {
                            $riv_dto->set_ria_txn_time($fr_obj->get_txn_time());
                        } else {
                            $riv_dto->set_ria_txn_time("");
                        }
                        $report_list[] = $riv_dto;

                        $txn_id = $obj->get_txn_id();
                        $index_no++;
                        $product_line++;
                    }
                }
            }

            if ($gen_exception_report) {
                DEFINE('REPORT_PATH', $this->get_config_srv()->value_of("flex_report_path"));
                $file_path = REPORT_PATH . $folder_name;

                $out_xml = new Vo_to_xml($report_list, APPPATH . 'data/flex_refund_invoice_vo2xml.txt');
                $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_refund_exception_xml2csv.txt', TRUE, ',');
                $csv_file = $this->get_dex_service()->convert($out_xml, $out_csv);

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
                    $message = "CONTENT: " . $csv_file . "<br>
                                 LINE: " . __LINE__;
                    $this->error_handler($subject, $message);
                }
            }
        }
    }

    public function get_riv_dto()
    {
        return $this->riv_dto;
    }

    public function get_so_item_detail_dao()
    {
        return $this->so_item_detail_dao;
    }

    public function get_flex_batch_obj($where)
    {
        return $this->get_flex_batch_dao()->get($where);
    }

    public function get_flex_batch_dao()
    {
        return $this->flex_batch_dao;
    }

    public function get_flex_batch_list($where, $option)
    {
        return $this->get_flex_batch_dao()->get_list($where, $option);
    }

    public function get_flex_batch_num_rows($where)
    {
        return $this->get_flex_batch_dao()->get_num_rows($where);
    }

    public function get_so_fee_invoice($start_date, $end_date, $gateway_id)
    {
        DEFINE('REPORT_PATH', $this->get_config_srv()->value_of("flex_report_path"));
        if (is_file((REPORT_PATH . "so_fee.csv"))) {
            @unlink(REPORT_PATH . "so_fee.csv");
        }
        $where = array();
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));
        $where["fsf.txn_time >="] = $start_date . ' 00:00:00';
        $where["fsf.txn_time <="] = $end_date . ' 23:59:59';

        //comment out by nero, in fee table, value less than 0 can exist
        //$where["fsf.amount >"] = '0';

        if ($gateway_id) {
            $where["fsf.gateway_id"] = $gateway_id;
        }

        //so_fee
        $sfi_list = $this->get_fsf_dao()->get_so_fee_invoice($where);
        //var_dump($this->get_fsf_dao()->db->last_query());die();
        //var_dump($sfi_list );die();
        if ($sfi_list) {
            foreach ($sfi_list as $sfi_dto) {
                //by nero, change the amazon gateway_code
                //$this->data_restructure($sfi_dto);
                $type = $sfi_dto->get_type();

                $refund_list = array("R", "A_RF", "A_RO", "A_RF_C", "A_RF_RC", "M_MRF_W", "M_MRF_U");
                $ria_list = array("RIA", "A_COMM", "A_FBA_H", "A_FBA_P", "A_FBA_W", "A_SC", "A_CBF", "A_SO_FEE", "A_OO", "A_OP", "M_PTF", "M_F", "M_RMC_PTF", "M_RMC_F",
                    "A_FPUFF", "A_FPOFF", "A_FWBF", "A_OOS",
                    "A_OPS", "A_OP");
                $charge_back_list = array("Chargeback");

                //rolling reserve no fee, need to remove
                $rolling_reserve_list = array("TH");

                if (in_array($type, $refund_list)) {
                    $sfi_dto->set_type("Refund");
                } elseif (in_array($type, $ria_list)) {
                    $sfi_dto->set_type("RIA");
                } elseif (in_array($type, $charge_back_list)) {
                    $sfi_dto->set_type("Chargeback");
                } elseif (in_array($type, $rolling_reserve_list)) {
                    $sfi_dto->set_type("Rolling_reserve_fee");
                }

                $percentage = abs(number_format($sfi_dto->get_fee() / $sfi_dto->get_order_amount() * 100, 2, '.', ''));
                $sfi_dto->set_percentage($percentage);
            }

            $file_path = REPORT_PATH;

            $out_xml = new Vo_to_xml($sfi_list, APPPATH . 'data/flex_so_fee_invoice_vo2xml.txt');
            $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_so_fee_invoice_xml2csv.txt', TRUE, ',');
            $csv_file .= $this->get_dex_service()->convert($out_xml, $out_csv);

            $filename = "so_fee.csv";

            $fp = fopen($file_path . $filename, 'w');
            if (!fwrite($fp, $csv_file)) {
                $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/fee/" . $filename;
                $message = "CONTENT: " . $csv_file . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    public function get_gateway_fee_invoice($start_date, $end_date, $gateway_id)
    {
        DEFINE('REPORT_PATH', $this->get_config_srv()->value_of("flex_report_path"));
        if (is_file((REPORT_PATH . "gateway_fee.csv"))) {
            @unlink(REPORT_PATH . "gateway_fee.csv");
        }

        $where = array();
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));
        $where["txn_time >="] = $start_date . ' 00:00:00';
        $where["txn_time <="] = $end_date . ' 23:59:59';
        $option['limit'] = -1;
        if ($gateway_id) {
            $where["gateway_id"] = $gateway_id;
        }

        //currency exchange diff
        $fgf_list = $this->get_fgf_dao()->get_list(array_merge((array)$where, array("status IN ('FXI','FXO')" => NULL)), $option);

        //var_dump($this->get_fgf_dao()->db->last_query());die();

        if ($fgf_list) {
            $txn_array = array();

            foreach ($fgf_list AS $fgf_obj) {
                $txn_array[$fgf_obj->get_txn_id()][$fgf_obj->get_status()] = $fgf_obj;
            }
            foreach ($txn_array AS $txn_ref => $record_array) {

                $from_obj = $record_array["FXO"];
                $to_obj = $record_array["FXI"];

                $gfi_dto = clone $this->get_gfi_dto();
                $flex_rate = $this->get_flex_rate($from_obj->get_currency_id(), $to_obj->get_currency_id());
                $diff = ($from_obj->get_amount() * $flex_rate) + $to_obj->get_amount();
                $diff_percent = number_format($diff / $to_obj->get_amount() * 100, 2, '.', '');
                $flex_gateway_code = $this->get_flex_gateway_mapping($from_obj->get_gateway_id(), $from_obj->get_currency_id());

                $gfi_dto->set_type("Exchange Diff.");
                $gfi_dto->set_txn_time($from_obj->get_txn_time());
                $gfi_dto->set_from_currency($from_obj->get_currency_id());
                $gfi_dto->set_from_amount($from_obj->get_amount());
                $gfi_dto->set_gateway_id($flex_gateway_code);
                $gfi_dto->set_batch_id($from_obj->get_flex_batch_id());
                $gfi_dto->set_to_currency($to_obj->get_currency_id());
                $gfi_dto->set_to_amount($to_obj->get_amount());

                $gfi_dto->set_difference($diff);
                $gfi_dto->set_percentage($diff_percent);
                $gfi_dto->set_txn_ref($from_obj->get_txn_id());
                $report_list[] = $gfi_dto;
            }
        }

        //combine the payment sent together
        //by nero, PR represent the Payment Receive, and for those record transfer from other table.
        $ria_list = array("RIA", "M_PTF", "M_F", "M_RMC_PTF", "M_RMC_F");
        $chargeback_list = array("M_CBF");
        $fee_list = array("BTU", "A_O_FIR", "A_O", "A_SFSF", "A_SS", "A_SFRF", "A_SFDF", "A_SFLTSF", "A_O_O_DC", "A_O_O_FD", "A_O_O");
        $ps_list = array("PS");
        $pr_list = array("PR", "PR_R", "PR_C");
        //rolling reserve
        $rr_list = array("RRH");
        $mf_list = array("T_MF");

        //transfer
        $tr_list = array("P_TF");

        $status_list = array_merge($ria_list, $chargeback_list, $fee_list, $ps_list, $pr_list, $rr_list, $mf_list, $tr_list);
        $status_list_str = "'" . implode("','", $status_list) . "'";

        $tfr_fgf_list = $this->get_fgf_dao()->get_list(array_merge((array)$where, array("status IN ({$status_list_str})" => NULL)), $option);

        if ($tfr_fgf_list) {
            $txn_array = array();

            foreach ($tfr_fgf_list AS $fgf_obj) {
                $gfi_dto = clone $this->get_gfi_dto();
                $flex_gateway_code = $this->get_flex_gateway_mapping($fgf_obj->get_gateway_id(), $fgf_obj->get_currency_id());

                $type = $fgf_obj->get_status();

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


                $gfi_dto->set_type($report_type);
                $gfi_dto->set_txn_time($fgf_obj->get_txn_time());
                $gfi_dto->set_from_currency($fgf_obj->get_currency_id());
                $gfi_dto->set_from_amount($fgf_obj->get_amount());
                $gfi_dto->set_gateway_id($flex_gateway_code);
                $gfi_dto->set_batch_id($fgf_obj->get_flex_batch_id());
                $gfi_dto->set_txn_ref($fgf_obj->get_txn_id());
                $report_list[] = $gfi_dto;
            }
        }


        if ($report_list) {
            $file_path = REPORT_PATH;

            $out_xml = new Vo_to_xml($report_list, APPPATH . 'data/flex_gateway_fee_invoice_vo2xml.txt');
            $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_gateway_fee_invoice_xml2csv.txt', TRUE, ',');
            $csv_file .= $this->get_dex_service()->convert($out_xml, $out_csv);

            $filename = "gateway_fee.csv";

            $fp = fopen($file_path . $filename, 'w');
            if (!fwrite($fp, $csv_file)) {
                $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/fee/" . $filename;
                $message = "CONTENT: " . $csv_file . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            } else
                fclose($fp);
        }
    }

    public function get_gfi_dto()
    {
        return $this->gfi_dto;
    }

    public function get_flex_rate($from_currency, $to_currency)
    {
        if ($obj = $this->get_erf_dao()->get(array("from_currency_id" => $from_currency, "to_currency_id" => $to_currency))) {
            return $obj->get_rate();
        } else {
            return 1;
        }
    }

    public function get_erf_dao()
    {
        return $this->erf_dao;
    }

    public function get_flex_gateway_mapping($gateway_id, $currency_id)
    {
        if ($obj = $this->get_fgm_dao()->get(array("gateway_id" => $gateway_id, "currency_id" => $currency_id))) {
            return $obj->get_gateway_code();
        } else {
            return "";
        }

    }

    public function get_fgm_dao()
    {
        return $this->fgm_dao;
    }

    public function get_rolling_reserve_report($start_date, $end_date, $gateway_id)
    {
        include_once(APPPATH . "libraries/dto/rolling_reserve_report_dto.php");
        DEFINE('REPORT_PATH', $this->get_config_srv()->value_of("flex_report_path"));
        if (is_file((REPORT_PATH . self::ROLLING_RESERVE_REPORT_FILE_NAME))) {
            @unlink(REPORT_PATH . self::ROLLING_RESERVE_REPORT_FILE_NAME);
        }

        $where = array();
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));
        $where["txn_time >="] = $start_date . ' 00:00:00';
        $where["txn_time <="] = $end_date . ' 23:59:59';
        $option['limit'] = -1;
        if ($gateway_id) {
            $where["gateway_id"] = $gateway_id;
        }

        $frr_list = $this->get_frr_dao()->get_list($where, $option);
        //var_dump($this->get_frr_dao()->db->last_query());die();

        $rrrList = array();
        foreach ($frr_list as $frr) {
            $rrrObj = new Rolling_reserve_report_dto();
            $rrrObj->set_so_no($frr->get_so_no());
            $rrrObj->set_batch_id($frr->get_flex_batch_id());
            $flex_gateway_code = $this->get_flex_gateway_mapping($frr->get_gateway_id(), $frr->get_currency_id());


            $rrrObj->set_gateway_id($flex_gateway_code);
            $rrrObj->set_txn_id($frr->get_txn_id());
            $rrrObj->set_txn_date(date("Y-m-d", strtotime($frr->get_txn_time())));
            $rrrObj->set_currency_id($frr->get_currency_id());
            $rrrObj->set_amount($frr->get_amount());
            $rrrObj->set_status($frr->get_status());

            $so_obj = $this->get_so_dao()->get(array("so_no" => $frr->get_so_no()));


            if ($frr->get_status() == "RRR") {
                $rrObj_hold = $this->get_frr_dao()->get(array("so_no" => $frr->get_so_no(), "status" => "RRH"));
                if ($rrObj_hold)
                    $rrrObj->set_hold_time($rrObj_hold->get_txn_time());
            }

            $rrrObj->set_order_amount($so_obj->get_amount());
            $rrrObj->set_percentage($frr->get_amount() / $so_obj->get_amount() * 100);
            array_push($rrrList, $rrrObj);
        }

        //var_dump($rrrList);die();
        if ($rrrList) {
            $file_path = REPORT_PATH;

            $out_xml = new Vo_to_xml($rrrList, APPPATH . 'data/flex_rolling_reserve_vo2xml.txt');
            $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_rolling_reserve_xml2csv.txt', TRUE, ',');
            $csv_file .= $this->get_dex_service()->convert($out_xml, $out_csv);

            $filename = self::ROLLING_RESERVE_REPORT_FILE_NAME;

            $fp = fopen($file_path . $filename, 'w');
            if (!fwrite($fp, $csv_file)) {
                $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/fee/" . $filename;
                $message = "CONTENT: " . $csv_file . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            } else
                fclose($fp);
        }
    }

    public function get_pending_order_report($ship_date)
    {
        $where = array();
        if ($ship_date != '') {
            if (check_finance_role())
                $dispatch_string = "so.finance_dispatch_date";
            else
                $dispatch_string = "so.dispatch_date";

            $where["(" . $dispatch_string . " > '" . ($ship_date . ' 23:59:59') . "' or " . $dispatch_string . " is null)"] = NULL;
            $where["ria.txn_time <="] = $ship_date . ' 23:59:59';
        }

        $fr_list = $this->get_flex_ria_dao()->get_pending_order_report_list($where, array("orderby" => "ria.txn_time", "limit" => -1));
        // var_dump($this->get_flex_ria_dao()->db->last_query());die();
        if ($fr_list) {
            $where = array();
            if ($ship_date != '') {
                $where["txn_time <="] = $ship_date . ' 23:59:59';
            }

            $total = 0;
            foreach ($fr_list as $fr_obj) {
                $where["so_no"] = $fr_obj->get_so_no();
                $num_rows = $this->get_flex_refund_dao()->get_num_rows($where);
                // var_dump($this->get_flex_refund_dao()->db->last_query());die();
                if ($num_rows == 0) {
                    $pending_list = $this->get_so_dao()->get_pending_order_info(array("flex_batch_id" => $fr_obj->get_flex_batch_id(), "fr.so_no" => $fr_obj->get_so_no()));
                    foreach ($pending_list as $obj) {
                        $report_list[date("Ymd", strtotime($obj->get_txn_time()))][$obj->get_currency_id()][$obj->get_gateway_id()][] = $obj;
                        $report_total[date("Ymd", strtotime($obj->get_txn_time()))][$obj->get_currency_id()][$obj->get_gateway_id()] += $obj->get_amount();
                    }
                }
            }

            if ($report_list) {
                $total = 0;
                foreach ($report_list as $date => $cur_list) {
                    foreach ($cur_list as $curr => $gateway_list) {
                        foreach ($gateway_list as $gateway_id => $obj_list) {
                            $obj_list[count($obj_list) - 1]->set_total($report_total[$date][$curr][$gateway_id]);
                            foreach ($obj_list as $obj) {
                                $list[] = $obj;
                            }
                        }
                    }
                }
            }

            if ($list) {
                DEFINE('REPORT_PATH', $this->get_config_srv()->value_of("flex_report_path"));
                $file_path = REPORT_PATH;

                $out_xml = new Vo_to_xml($list, APPPATH . 'data/flex_pending_order_report_vo2xml.txt');
                $out_csv = new Xml_to_csv("", APPPATH . 'data/flex_pending_order_report_xml2csv.txt', TRUE, ',');
                $csv_file = $this->get_dex_service()->convert($out_xml, $out_csv);
                $filename = "pending_order_report.csv";

                $fp = fopen($file_path . $filename, 'w');
                if (!fwrite($fp, $csv_file)) {
                    $subject = "<DO NOT REPLY>Fail to write file - " . $file_path . "/pending_order_report/" . $filename;
                    $message = "CONTENT: " . $csv_file . "<br>
                                 LINE: " . __LINE__;
                    $this->error_handler($subject, $message);
                }
            }
        }
    }

    public function get_sfi_dto()
    {
        return $this->sfi_dto;
    }

    public function reverse_sales_invoice_status($date)
    {
        $date = date("Y-m-d", strtotime($date));
        if (preg_match("/\d{4}-\d{2}-\d{2}/", trim($date))) {
            $where = array();
            $where["start_date"] = $date;
            $where["end_date"] = $date;

            $dispatched_order_list = $this->get_so_dao()->get_flex_sales_invoice($where);
            //$dispatched_order_list = $this->get_so_dao()->get_flex_sales_invoice($date, $date);
            //var_dump($this->get_so_dao()->db->last_query());die();
            foreach ($dispatched_order_list as $dispatched_order_obj) {
                if ($ria_obj = $this->get_flex_ria_dao()->get(array("so_no" => $dispatched_order_obj->get_so_no()))) {
                    //all to RIA, include status of SALES and REFUNDED
                    $ria_obj->set_status("RIA");
                    $this->get_flex_ria_dao()->update($ria_obj);
                }
            }
        }
    }

    public function reverse_refund_invoice_status($date)
    {
        $date = date("Y-m-d", strtotime($date));
        if (preg_match("/\d{4}-\d{2}-\d{2}/", trim($date))) {
            $where = array();
            $start_date = date("Y-m-d", strtotime($date));
            $end_date = date("Y-m-d", strtotime($date));
            $where["txn_time >="] = $start_date . ' 00:00:00';
            $where["txn_time <="] = $end_date . ' 23:59:59';

            if ($ri_list = $this->get_flex_refund_dao()->get_list($where, array("limit" => -1))) {
                foreach ($ri_list as $refund_obj) {
                    //only reverse the status from REFUNDED to RIA,,
                    //FOR those SALES status, dont touch them
                    if ($ria_obj = $this->get_flex_ria_dao()->get(array("so_no" => $refund_obj->get_so_no(), "status" => "REFUNDED"))) {
                        $ria_obj->set_status("RIA");
                        $this->get_flex_ria_dao()->update($ria_obj);
                    }
                }
            }
        }
    }

    public function platfrom_order_insert_interface_flex_ria($gateway_id, $so_no_list)
    {
        $so_no_collect = '(' . implode(',', $so_no_list) . ')';

        $where = array("so_no IN {$so_no_collect}" => null);
        $option = array('limit' => -1);

        if (($so_obj_list = $this->get_so_dao()->get_list($where, $option))
            && ($flex_batch_obj = $this->get_flex_batch_dao()->get(array('gateway_id' => $gateway_id)))
        ) {
            foreach ($so_obj_list as $so_obj) {
                $ifr_vo = $this->get_ifr_dao()->get();
                $ifr_obj = clone $ifr_vo;

                $ifr_obj->set_so_no($so_obj->get_so_no());
                $ifr_obj->set_flex_batch_id($flex_batch_obj->get_id());
                $ifr_obj->set_gateway_id($flex_batch_obj->get_gateway_id());
                $ifr_obj->set_txn_id($so_obj->get_txn_id());
                //cant't get the time from gateway report, so use order_create_date as txn_time
                $ifr_obj->set_txn_time($so_obj->get_order_create_date());
                $ifr_obj->set_amount($so_obj->get_amount());
                $ifr_obj->set_currency_id($so_obj->get_currency_id());
                $ifr_obj->set_status('RIA');
                $ifr_obj->set_batch_status('S');

                $this->get_ifr_dao()->insert($ifr_obj);
            }
        }
    }

    public function get_ifr_dao()
    {
        return $this->ifr_dao;
    }

    public function platform_order_delete_interface_flex_ria($gateway_id, $so_no_list)
    {
        $so_no_collect = '(' . implode(',', $so_no_list) . ')';
        $where = array("so_no IN {$so_no_collect}" => null);
        $option = array('limit' => -1);

        if ($ifr_obj_list = $this->get_ifr_dao()->get_list($where, $option)) {
            foreach ($ifr_obj_list as $ifr_obj) {
                $this->get_ifr_dao()->delete($ifr_obj);
            }
        }
    }

    public function w_bank_transfer_to_flex_ria($sobt_obj)
    {
        if (($so_obj = $this->get_so_dao()->get(array('so_no' => $sobt_obj->get_so_no())))
            && ($flex_batch_obj = $this->get_flex_batch_dao()->get(array('gateway_id' => 'w_bank_transfer')))
        ) {

            if ($flex_ria_obj = $this->get_flex_ria_dao()->get(array('so_no' => $sobt_obj->get_so_no(), 'flex_batch_id' => $flex_batch_obj->get_id()))) {
                $action = 'update';
            } else {
                $action = 'insert';
                $flex_ria_vo = $this->get_flex_ria_dao()->get();
                $flex_ria_obj = clone $flex_ria_vo;
            }

            $flex_ria_obj->set_so_no($so_obj->get_so_no());
            $flex_ria_obj->set_flex_batch_id($flex_batch_obj->get_id());
            $flex_ria_obj->set_gateway_id($flex_batch_obj->get_gateway_id());
            $flex_ria_obj->set_txn_id($sobt_obj->get_ext_ref_no());
            //
            $flex_ria_obj->set_txn_time(date("Y-m-d H:i:s"));
            if ($flex_ria_obj->get_amount()) {
                $flex_ria_obj->set_amount($sobt_obj->get_received_amt_localcurr() + $flex_ria_obj->get_amount());
            } else {
                $flex_ria_obj->set_amount($sobt_obj->get_received_amt_localcurr());
            }
            $flex_ria_obj->set_currency_id($so_obj->get_currency_id());
            $flex_ria_obj->set_status('RIA');

            return $this->get_flex_ria_dao()->$action($flex_ria_obj);
        }
    }

    public function platfrom_order_insert_flex_ria($gateway_id, $so_no)
    {
        if (($so_obj = $this->get_so_dao()->get(array('so_no' => $so_no)))
            && ($flex_batch_obj = $this->get_flex_batch_dao()->get(array('gateway_id' => $gateway_id)))
        ) {

            if ($flex_ria_obj = $this->get_flex_ria_dao()->get(array('so_no' => $so_no, 'flex_batch_id' => $flex_batch_obj->get_id()))) {
                $action = 'update';
            } else {
                $action = 'insert';
                $flex_ria_vo = $this->get_flex_ria_dao()->get();
                $flex_ria_obj = clone $flex_ria_vo;
            }

            $flex_ria_obj->set_so_no($so_obj->get_so_no());
            $flex_ria_obj->set_flex_batch_id($flex_batch_obj->get_id());
            $flex_ria_obj->set_gateway_id($flex_batch_obj->get_gateway_id());
            $flex_ria_obj->set_txn_id($so_obj->get_txn_id());
            //cant't get the time from gateway report, so use order_create_date as txn_time
            $flex_ria_obj->set_txn_time($so_obj->get_order_create_date());
            $flex_ria_obj->set_amount($so_obj->get_amount());
            $flex_ria_obj->set_currency_id($so_obj->get_currency_id());
            $flex_ria_obj->set_status('RIA');

            return $this->get_flex_ria_dao()->$action($flex_ria_obj);
        }
    }

    public function platfrom_order_insert_flex_refund($gateway_id, $refund_obj)
    {
        $so_no = $refund_obj->get_so_no();
        if (($so_obj = $this->get_so_dao()->get(array('so_no' => $so_no)))
            && ($flex_batch_obj = $this->get_flex_batch_dao()->get(array('gateway_id' => $gateway_id)))
        ) {
            $flex_refund_vo = $this->get_flex_refund_dao()->get();
            $flex_refund_obj = clone $flex_refund_vo;

            $flex_refund_obj->set_so_no($so_no);
            $flex_refund_obj->set_flex_batch_id($flex_batch_obj->get_id());
            $flex_refund_obj->set_gateway_id($flex_batch_obj->get_gateway_id());
            $flex_refund_obj->set_internal_txn_id($so_obj->get_txn_id());
            $flex_refund_obj->set_txn_id($so_obj->get_txn_id());
            $flex_refund_obj->set_txn_time($so_obj->get_order_create_date());
            $flex_refund_obj->set_amount($refund_obj->get_total_refund_amount());
            $flex_refund_obj->set_currency_id($so_obj->get_currency_id());
            $flex_refund_obj->set_status('R');

            return $this->get_flex_refund_dao()->insert($flex_refund_obj);
        }
    }

    public function get_rakuten_shipped_order($platform_order_id)
    {
        $where = array(
            'so.status' => 6,
            'so.platform_id' => 'RAKUES',
            'ifr.so_no IS NULL' => null,
            "so.dispatch_date > '2014-10-31 23:59:59'" => null,
            "so.platform_order_id like '%{$platform_order_id}%'" => null
        );

        $so_list = $this->get_so_dao()->get_rakuten_shipped_order($where, array('limit' => -1));
        // var_dump($this->get_so_dao()->db->last_query());die();
        // var_dump($so_list);die();

        return $so_list;
    }

    public function get_rakuten_shipped_order_from_interface()
    {
        $where = array(
            'ifr.gateway_id' => 'rakuten',
            'fr.so_no IS NULL' => null
        );
        return $this->get_so_dao()->get_rakuten_shipped_order_from_interface($where, array('limit' => -1));
    }

}



