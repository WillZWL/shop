<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\ValidationService;
use ESG\Panther\Service\EventService;
use ESG\Panther\Service\PriceMarginService;
use ESG\Panther\Service\FtpConnector;

class ExchangeRateService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->validationService = new ValidationService;
        $this->eventService = new EventService;
        $this->priceMarginService = new PriceMarginService;
        $this->ftpConnector = new FtpConnector;
    }

    public function getActiveCurrencyList()
    {
        $rtn = [];
        $where = [];
        $option = [];
        $obj_array = $this->getDao('Currency')->getActiveCurrencyList($where, $option);
        foreach ($obj_array as $obj) {
            $rtn[$obj->getCurrencyId()] = $obj->getName();
        }

        return $rtn;
    }

    public function getActiveCurrencyObjList()
    {
        return $this->getDao('Currency')->getActiveCurrencyList($where = [], $option = []);
    }

    public function getCurrencyList($where = [], $option = [])
    {
        $rtn = [];
        $obj_array = $this->getDao('Currency')->getList($where, $option);
        foreach ($obj_array as $obj) {
            $rtn[$obj->getCurrencyId()] = $obj->getName();
        }

        return $rtn;
    }

    public function alterExchangeRate($from, $to, $rate, $dao)
    {
        if ($from != "" && $to != "") {
            $obj = $this->getDao($dao)->get();
            $obj->setFromCurrencyId($from);
            $obj->setToCurrencyId($to);
            $obj->setRate($rate);
            if ($dao == "ExchangeRateApproval") {
                $obj->setApprovalStatus("0");
            } elseif ($dao == "ExchangeRate") {
                $app_obj = $this->getDao('ExchangeRateApproval')->get();
                $app_obj->setFromCurrencyId($from);
                $app_obj->setToCurrencyId($to);
                $app_obj->setRate($rate);
                $app_obj->setApprovalStatus("1");
            }
            $num_row = $this->getDao($dao)->getNumRows(["from_currency_id" => $from, "to_currency_id" => $to]);
            if ($num_row) {
                $rtn = $this->getDao($dao)->update($obj);
                if ($rtn && $dao == "ExchangeRate") {
                    $rtn = $this->getDao('ExchangeRateApproval')->update($app_obj);
                }
            } else {
                $rtn = $this->getDao($dao)->insert($obj);
                if ($rtn && $dao == "ExchangeRate") {
                    $rtn = $this->getDao('ExchangeRateApproval')->insert($app_obj);
                }
            }
        } else {
            $rtn = FALSE;
        }

        return $rtn;
    }

    public function getBasedRate($base, $currency_list, $dao)
    {
        if ($base != "") {
            $ret = [];
            foreach ($currency_list as $key => $value) {
                if ($base != $key) {
                    $obj = $this->getDao($dao)->get(["from_currency_id" => $base, "to_currency_id" => $key]);
                    if (empty($obj)) {
                        $ret[$key] = 1.00;
                    } else {
                        $ret[$key] = $obj->getRate();
                    }
                } else {
                    $ret[$base] = 1.00;
                }
            }
            $rtn = $ret;
        } else {
            $rtn = FALSE;
        }
        return $rtn;
    }

    public function getExchangeRate($from = "", $to = "")
    {
        if ($from != "" && $to != "") {
            $ret = $this->getDao('ExchangeRate')->get(["from_currency_id" => $from, "to_currency_id" => $to]);
        } else {
            $ret = $this->getDao('ExchangeRate')->get();
        }

        return $ret;
    }

    public function getExchangeRateApprovalList($where = [], $option = [])
    {
        return $this->getDao('ExchangeRateApproval')->getList($where, $option);
    }

    public function uploadExchangeRate()
    {
        $curr = "";
        $url = $this->getDao('Config')->valueOf('xrate_url');
        $currency_list = $this->getDao('Currency')->getList([]);
        $i = 0;
        foreach ($currency_list as $obj) {
            if ($i > 0) {
                $curr .= "_" . $obj->getCurrencyId();
            } else {
                $curr .= $obj->getCurrencyId();
            }
            $i++;
        }
        $url = $url . "&expr=" . $curr . "&exch=" . $curr;
        $tmp[] = $xrate[] = [];
        $tmp = file_get_contents($url);
        $tmp = trim($tmp);
        if ($tmp != "") {
            $filename = "exchange_rate" . date("Ymd") . ".csv";
            $path = "/var/data/valuebasket.com/exchange_rate";
            if (file_exists($path . '/' . $filename)) {
                unlink($path . '/' . $filename);
            }
            if ($fp = @fopen($path . '/' . $filename, 'w')) {
                @fwrite($fp, $tmp);
                @fclose($fp);
            }
        }
        $tmp = explode("\n", $tmp);
        for ($i = 2; $i < count($tmp); $i++) {
            $xrate[$i - 2] = $tmp[$i];
        }
        $remark = "";
        foreach ($xrate AS $obj) {
            set_time_limit(120);
            $difference = 0;

            if (!$obj) {
                continue;
            }

            $data = explode(",", $obj);
            $date = $this->changeDateFormat($data[3]);
            if (!($this->getDao('ExchangeRateHistory')->get(["from_currency_id" => $data[0], "to_currency_id" => $data[1], "date" => $date["today"]]))) {
                $xrate_today = $this->getDao('ExchangeRateHistory')->get();
                $xrate_today->setFromCurrencyId($data[0]);
                $xrate_today->setToCurrencyId($data[1]);
                $xrate_today->setDate($date["today"]);
                $xrate_today->setRate($data[4]);
                $this->getDao('ExchangeRateHistory')->insert($xrate_today);
            }
            $xrate_system = $this->getDao('ExchangeRate')->get(["from_currency_id" => $data[0], "to_currency_id" => $data[1]]);
            if ($xrate_system) {
                $difference = $this->calcDailyDiff($xrate_system->getRate(), $data[4]);
            }
            if ($difference) {
                if ($diff_currency != $data[0] && $remark != "") {
                    $remark .= "<br>";
                }

                $remark .= $data[0] . "|" . $data[1] . "|" . $xrate_system->getRate() . "|" . $data[4] . " => " . $difference . "% difference<br>";
                $diff_currency = $data[0];
            } else {
                $xrate = $this->getDao('ExchangeRate')->get(["from_currency_id" => $data[0], "to_currency_id" => $data[1]]);
                if ($xrate) {
                    $xrate->setRate($data[4]);
                } else {
//                  var_dump(__LINE__.$this->db->last_query());
                }
                $this->getDao('ExchangeRate')->update($xrate);
            }
            $xrate_obj = $this->getDao('ExchangeRateApproval')->get(["from_currency_id" => $data[0], "to_currency_id" => $data[1]]);
            if ($xrate_obj) {
                $xrate_obj->setRate($data[4]);
                if ($difference) {
                    $xrate_obj->setApprovalStatus("0");
                } else {
                    $xrate_obj->setApprovalStatus("1");
                }
                $this->getDao('ExchangeRateApproval')->update($xrate_obj);
            } else {
                $xrate_obj = $this->getDao('ExchangeRateApproval')->get();
                $xrate_obj->setFromCurrencyId($data[0]);
                $xrate_obj->setToCurrencyId($data[1]);
                $xrate_obj->setRate($data[4]);
                if ($difference) {
                    $xrate_obj->setApprovalStatus("0");
                } else {
                    $xrate_obj->setApprovalStatus("1");
                }
                $this->getDao('ExchangeRateApproval')->insert($xrate_obj);
            }
        }
        $email_to = $this->getDao('Config')->valueOf('alan_email');
        if ($remark) {
            $line = explode("<br><br>", $remark);
            foreach ($line AS $line_obj) {
                if ($line_obj) {
                    $temp_obj = explode("<br>", $line_obj);
                    if ($temp_obj) {
                        $num_obj = count($temp_obj);
                        $header = "(" . $num_obj . " entries changed)<br>";
                        $remark_revise .= $header . $line_obj . "<br><br>";
                    }
                }
            }
            $default_msg = "Daily Currency has been updated. Some currency need approval to effective.<br>From|To|Was Rate|Now Rate=>difference<br>";
            $remark_revise = $default_msg . $remark_revise . "<br><br>Click <a href='http://admincentre.valuebasket.com/mastercfg/exchange_rate/view/'>here</a> to approve.";
            if ($email_to && $remark_revise) {
                $subject = "Daily Exchange rates updated. Pending for approval.";
                $this->notificationEmail($email_to, $remark_revise, "daily_ex_rate", "daily_ex_rate_email", $subject);
            }
        } else {
            $header = "All exchange rates were approved automatically.<br>";
            $remark_revise = $header . "<br><br>Click <a href='http://admincentre.valuebasket.com/mastercfg/exchange_rate/view/'>here</a> to view.";
            if ($email_to && $remark_revise) {
                $subject = "Daily Exchange rates updated. No Approval Required.";
                $this->notificationEmail($email_to, $remark_revise, "daily_ex_rate", "daily_ex_rate_email", $subject);
            }
        }

    }

    public function changeDateFormat($date)
    {
        $temp = explode("-", $date);
        $today = $temp[2] . "-" . $temp[0] . "-" . $temp[1] . " 00:00:00";
        $yesterday = date('Y-m-d 00:00:00', mktime(0, 0, 0, $temp[0], $temp[1] - 1, $temp[2]));
        return ["today" => $today, "yesterday" => $yesterday];
    }

    public function calcDailyDiff($was_rate, $now_rate)
    {
        if ($was_rate != 0) {
            $diff = (($now_rate - $was_rate) / $was_rate) * 100;
            if ($diff > 5 || $diff < -5) {
                return number_format($diff, 4, '.', '');
            }
        }
    }

    function notificationEmail($sent_to, $value, $title = 'ex_rate_notification', $tpl_id = "ex_rate_notice_email", $subject = "")
    {
        $email_dto = new \EventEmailDto();

        $now_access_time = date("Y-m-d H:i:s");
        if ($sent_to) {
            // $dispatch_email = $email;
            // $bill_name = $name;
            $tmp = clone $email_dto;
            $tmp->setEventId($title);
            $tmp->setMailTo($sent_to);
            $tmp->setMailFrom("no_reply@valuebasket.com");
            $tmp->setTplId($tpl_id);
            $tmp->setReplace(["remark" => $value, "subject" => $subject]);
            $this->eventService->fireEvent($tmp);
        }
    }

    public function updateExchangeRateFromCv()
    {
        if ($filename = $this->getExchangeRateFile()) {
            $batch_vo = $this->getBatchDao('Batch')->get();
            if ($rs = $this->validateExchangeRateFile($filename)) {
                $batch_obj = clone $batch_vo;
                $batch_obj->setFuncName("exchange_rate");
                $batch_obj->setStatus("N");
                $batch_obj->setListed(1);
                $batch_obj->setRemark($filename);
                $this->getBatchDao('Batch')->insert($batch_obj);
            } else {
                mail("steven@eservicesgroup.net", "[VB] Exchange Rate file format does not meet requirement", "For more details, please refer to transmission_log table", "From: Admin <itsupport@eservicesgroup.net>\r\n");
                exit;
            }

            $batch_obj = $this->getBatchDao('Batch')->get(["remark" => $filename]);
            $batch_id = $batch_obj->getId();

            $iex_vo = $this->getDao('InterfaceExchangeRate')->get();
            foreach ($rs AS $row) {
                $iex_obj = clone $iex_vo;
                $iex_obj->setBatchId($batch_id);
                $iex_obj->setBatchStatus("N");
                $iex_obj->setFromCurrencyId($row[0]);
                $iex_obj->setToCurrencyId($row[1]);
                $iex_obj->setRate($row[2]);
                $this->getDao('InterfaceExchangeRate')->insert($iex_obj);
            }

            $this->batchExchangeRate($batch_id, $rs);

            $result = $this->priceMarginService->refreshAllPlatformMargin();

            if (isset($result["error_message"])) {
                mail($this->notificationEmail, "VB Error updating Price Margin", $result["error_message"]);
            }
        }
    }

    public function getExchangeRateFile()
    {
        $local_path = $this->getDao('Config')->valueOf("ex_rate_data_path");
        $ftp = $this->ftpConnector;
        $ftpObj = $this->getDao('FtpInfo')->get(["name" => "CV_EXCHANGE_RATE"]);
        $ftp->setRemoteSite($server = $ftpObj->getServer());
        $ftp->setUsername($ftpObj->getUsername());
        $ftp->setPassword($this->encrypt->decode($ftpObj->getPassword()));
        $ftp->setPort($ftpObj->getPort());
        $ftp->setIsPassive($ftpObj->getPasv());
        $remote_path = "/";
        $dao = $this->getDao();
        $tlog_vo = $this->getDao('TransmissionLog')->get();
        $filename = "cv_exchange_rate_" . date("Ymd") . ".csv";

        if ($ftp->connect() !== FALSE) {
            if ($ftp->login() !== FALSE) {
                if ($ftp->getfile($local_path . $filename, $remote_path . $filename)) {
                    return $filename;
                } else {
                    $tlog_obj = clone $tlog_vo;
                    $tlog_obj->setFuncName($func);
                    $tlog_obj->setMessage("failed_to_download_cv_exchange_rate_file '" . $server . "'");
                    $this->getDao('TransmissionLog')->insert($tlog_obj);
                }
            } else {
                $tlog_obj = clone $tlog_vo;
                $tlog_obj->setFuncName($func);
                $tlog_obj->setMessage("failed_login_to_server '" . $server . "'");
                $this->getDao('TransmissionLog')->insert($tlog_obj);
            }
            $ftp->close();
        } else {
            $tlog_obj = clone $tlog_vo;
            $tlog_obj->setFuncName($func);
            $tlog_obj->setMessage("cannot_connect_to_server '" . $server . "'");
            $this->getDao('TransmissionLog')->insert($tlog_obj);
        }
        return FALSE;
    }

    public function validateExchangeRateFile($filename)
    {
        if (empty($filename)) {
            return false;
        }

        $local_path = $this->getDao('Config')->valueOf("ex_rate_data_path");
        $tlog_obj = $this->getDao('TransmissionLog')->get();
        $tlog_obj->setFuncName("exchange_rate");
        $batch_obj = $this->getBatchDao('Batch')->get(["remark" => $filename]);
        $success = 1;
        if (!empty($batch_obj)) {
            $tlog_obj->setMessage($filename . " already_in_batch");
            $this->getDao('TransmissionLog')->insert($tlog_obj);
        } else {
            if ($handle = @fopen($local_path . $filename, "r")) {
                while (!feof($handle)) {
                    $tmp = trim(fgets($handle));
                    if (!empty($tmp)) {
                        $ret[] = $ar_tmp = @explode(",", $tmp);
                    }
                    $rules[0] = ["not_empty"];
                    $rules[1] = ["not_empty"];
                    $rules[2] = ["is_number", "min=0"];

                    $rs = $this->validateDataRow($ar_tmp, $rules);
                    if (!$rs["valid"]) {
                        $tlog_obj->setMessage($rs["err_msg"]);
                        $this->getDao('TransmissionLog')->insert($tlog_obj);
                        $success = 0;
                    }
                }
                fclose($handle);
                if ($success) {
                    return $ret;
                }
            } else {
                $tlog_obj->setMessage($filename . " not_found");
                $this->getDao('TransmissionLog')->insert($tlog_obj);
            }
        }
        return false;
    }

    public function validateDataRow($data = [], $rules = [])
    {
        if (empty($data)) {
            return ["valid" => 0, "error_msg" => "empty data set"];
        }

        $this->validationService->setData($data);
        $this->validationService->setRules($rules);

        $rs = false;
        $err_msg = "";
        try {
            $rs = $this->validationService->run();
        } catch (Exception $e) {
            $err_msg = $e->getMessage();
        }

        if ($rs) {
            return ["valid" => 1, "err_msg" => null];
        }

        return ["valid" => 0, "err_msg" => $err_msg];
    }

    public function batchExchangeRate($batch_id, $data)
    {
        if (empty($batch_id)) {
            return false;
        }

        set_time_limit(180);
        $success = 1;
        if ($batch_obj = $this->getBatchDao('Batch')->get(["id" => $batch_id])) {
            $objlist = $this->getDao('InterfaceExchangeRate')->getList(["batch_id" => $batch_id, "batch_status" => "N"], ["limit" => -1]);
            if ($objlist) {
                foreach ($objlist AS $iex_obj) {
                    $action = null;
                    $rules["from_currency_id"] = ["not_empty"];
                    $rules["to_currency_id"] = ["not_empty"];
                    $rules["rate"] = ["is_number", "min=0"];

                    $rs = $this->validateDataRow($iex_obj, $rules);
                    if ($rs["valid"]) {
                        if ($ex_rate_obj = $this->getDao('ExchangeRate')->get(["from_currency_id" => $iex_obj->getFromCurrencyId(), "to_currency_id" => $iex_obj->getToCurrencyId()])) {
                            if ($iex_obj->getRate() != $ex_rate_obj->getRate()) {
                                $iex_obj->setBatchStatus("R");
                            } else {
                                $iex_obj->setBatchStatus("S");
                            }
                        } else {
                            $iex_obj->setBatchStatus("R");
                        }
                    } else {
                        $iex_obj->setBatchStatus("F");
                        $iex_obj->getFailedReason($rs["err_msg"]);
                        $success = 0;
                    }
                    $this->getDao('InterfaceExchangeRate')->update($iex_obj);
                }
            }
        }

        if (!$success) {
            $batch_obj->setStatus("CE");
            $batch_obj->setEndTime(date("Y-m-d H:i:s"));
            $this->getBatchDao('Batch')->update($batch_obj);
        }

        $this->proceedExchangeRate($batch_id);
    }

    public function proceedExchangeRate($batch_id)
    {
        set_time_limit(180);
        $batch_err = 0;
        $err_msg = "";
        $batch_obj = $this->getBatchDao('Batch')->get(["id" => $batch_id]);
        if ($batch_obj) {
            $batch_obj->setStatus("P");
            $this->getBatchDao('Batch')->update($batch_obj);

            $iex_list = $this->getDao('InterfaceExchangeRate')->getList(["batch_id" => $batch_id, "batch_status" => "R"], ["limit" => -1]);
            if (!empty($iex_list)) {
                $ex_vo = $this->getDao('ExchangeRate')->get();
                foreach ($iex_list as $iex_obj) {
                    if (!($this->getDao('ExchangeRateHistory')->get(["from_currency_id" => $iex_obj->getFromCurrencyId(), "to_currency_id" => $iex_obj->getToCurrencyId(), "date" => date("Ymd 00:00:00")]))) {
                        $xrate_today = $this->getDao('ExchangeRateHistory')->get();
                        $xrate_today->setFromCurrencyId($iex_obj->getFromCurrencyId());
                        $xrate_today->setToCurrencyId($iex_obj->getToCurrencyId());
                        $xrate_today->setDate(date("Ymd 00:00:00"));
                        $xrate_today->setRate($iex_obj->getRate());
                        $this->getDao('ExchangeRateHistory')->insert($xrate_today);
                    }

                    if ($ex_obj = $this->getDao('ExchangeRate')->get(["from_currency_id" => $iex_obj->getFromCurrencyId(), "to_currency_id" => $iex_obj->getToCurrencyId()])) {
                        $action = "update";
                    } else {
                        $action = "insert";
                        $ex_obj = clone $ex_vo;
                    }
                    $ex_obj->setFromCurrencyId($iex_obj->getFromCurrencyId());
                    $ex_obj->setToCurrencyId($iex_obj->getToCurrencyId());
                    $ex_obj->setRate($iex_obj->getRate());

                    $ret = $this->getDao('ExchangeRate')->$action($ex_obj);
                    if ($ret === FALSE) {
                        $iex_obj->setBatchStatus('F');
                        $iex_obj->setFailedReason($this->getDao('ExchangeRate')->db->_error_message());
                        $this->getDao('InterfaceExchangeRate')->update($iex_obj);

                        var_dump($this->getDao('ExchangeRate')->db->_error_message());
                        $err_msg .= "LINE: " . __LINE__ . "\nREASON: " . $this->getDao('ExchangeRate')->db->_error_message() . "\nSQL: " . $this->getDao('ExchangeRate')->db->last_query() . "\n\n\n";
                        $batch_err = 1;
//                      break;
                    } else {
                        $iex_obj->setBatchStatus('S');
                        $this->getDao('InterfaceExchangeRate')->update($iex_obj);
                        $this->updateExchangeRateApproval($ex_obj);
                    }
                }
            }
        }

        if ($batch_err) {
            $batch_obj->setStatus("CE");
            $batch_obj->setEndTime(date("Y-m-d H:i:s"));
            $this->getBatchDao('Batch')->update($batch_obj);
            mail("oswald-alert@eservicesgroup.com", "[VB] Batch Error", $err_msg, "From: Admin <itsupport@eservicesgroup.net>\r\n");
        } else {
            $batch_obj->setStatus("C");
            $batch_obj->setEndTime(date("Y-m-d H:i:s"));
            $this->getBatchDao('Batch')->update($batch_obj);
        }
    }

    public function updateExchangeRateApproval($ex_obj)
    {
        set_time_limit(180);
        $approval_obj = $this->getDao('ExchangeRateApproval')->get(["from_currency_id" => $ex_obj->getFromCurrencyId(), "to_currency_id" => $ex_obj->getToCurrencyId()]);
        if ($approval_obj) {
            $approval_obj->setRate($ex_obj->getRate());
            $approval_obj->setApprovalStatus("1");
            $ret = $this->getDao('ExchangeRateApproval')->update($approval_obj);
        } else {
            $approval_obj = $this->getDao('ExchangeRateApproval')->get();
            $approval_obj->setFromCurrencyId($ex_obj->getFromCurrencyId());
            $approval_obj->setToCurrencyId($ex_obj->getToCurrencyId());
            $approval_obj->setRate($ex_obj->getRate());
            $approval_obj->setApprovalStatus("1");
            $ret = $this->getDao('ExchangeRateApproval')->insert($approval_obj);
        }
        if ($ret === FALSE) {
            mail("steven@eservicesgroup.net", "[VB] Batch Error", "Unable to Approve the Exchange Rate\r\n" . $this->db->last_query(), "From: Admin <itsupport@eservicesgroup.net>\r\n");
        }
    }

    public function compareDifference($from, $to, $rate)
    {
        $xrate_obj = $this->getDao('ExchangeRate')->get(["from_currency_id" => $from, "to_currency_id" => $to]);
        if ($xrate_obj->getRate() != $rate) {
            $diff = number_format((($rate - $xrate_obj->getRate()) / $xrate_obj->getRate()) * 100, 4, '.', '');
            return $from . "|" . $to . "|" . $xrate_obj->getRate() . "|" . $rate . " => " . $diff . "% difference <br>";
        }
    }

}


