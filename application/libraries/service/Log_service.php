<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Log_service extends Base_service
{
    var $logmedia = "";
    var $logfile = "";
    var $logformat = "";
    var $logpath = "";
    var $dao = "";
    var $loglevel = "";
    var $rloglevel = array();

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        $this->config = $CI->config;
        $this->logfile = $this->config->item('logfile');
        $this->logmedia = $this->config->item('logmedia');
        $this->logformat = $this->config->item('logformat');
        $this->logpath = $this->config->item('logpath');
        $this->loglevel = $this->config->item('loglevel');
        include_once APPPATH . '/libraries/dao/Logmessage_dao.php';
        $this->dao = new Logmessage_dao();
        // foreach($this->loglevel as $type=>$level)
        // {
        //  $this->rloglevel[$level] = $type;
        // }
    }

    public function get_loglevel()
    {
        return $this->loglevel;
    }

    public function get_log_header()
    {
        $empty_field_array = array();
        $logmessage_obj = $this->dao->get();
        $class_method = get_class_methods($logmessage_obj);
        foreach ($class_method as $fct_name) {
            if (substr($fct_name, 0, 4) == "get_") {
                $field = substr($fct_name, 4);
                $empty_field_array[$field] = "";
            }
        }
        return $empty_field_array;
    }

    public function write_log($data)
    {
        if ($this->logmedia["file"] == 1) {
            $this->write_log_to_file($data);
        }
        if ($this->logmedia["database"] == 1) {
            $this->write_log_to_database($data);
        }
        if ($this->logmedia["screen"] == 1) {
            $str = $this->logformat;
            foreach ($data as $field => $value) {
                str_replace("#" . $field . "#", $value, $str);
            }

            $output = '<pre style="font-family:Arial; font-size:10px; color:#ff0000;">Log to Screen Notice:<br>';
            $output .= $str . '<br>';
            $output .= '</pre>';

            echo $output;
        }
    }

    private function write_log_to_file($data, $error = 0)
    {
        $str = $this->logformat;
        //print_r($data);
        foreach ($data as $field => $value) {
            if ($field == "type") {
                $value = $this->rloglevel[$value];
            }
            if ($field == "user" && $value == "") {
                $value = 'system';
            }
            $str = str_replace('__' . $field . '__', $value, $str);
        }

        $str = str_replace('__date_created__', date("Y-m-d H:i:s"), $str);

        $date = date("Ymd");
        $this->logfile = str_replace('__date__', $date, $this->logfile);


        try {
            if ($fp = @fopen($this->logpath . $this->logfile, 'a+')) {
                if ($error == 1) {
                    $str = "#\t" . $str;
                } else {
                    $str = "*\t" . $str;
                }
                @fwrite($fp, $str . "\n");
                @fclose($fp);
            } else {
                throw new Exception("Cannot open $this->logpath$this->logfile for writing");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        };
    }

    private function write_log_to_database($data)
    {
        $log_message_vo = $this->dao->get();
        $class_methods = get_class_methods($log_message_vo);
        foreach ($class_methods as $fct_name) {
            if (substr($fct_name, 0, 4) == 'set_') {
                $var = substr($fct_name, 4);
                $set_method = 'set_' . $var;
                call_user_func(array($log_message_vo, $set_method), $data[$var]);
            }
        }

        try {
            $return_obj = $this->dao->insert($log_message_vo);

//          if(!($return_obj = $this->dao->insert($log_message_vo)))
            if (!$return_obj) {
                throw new Exception("Fail adding log records to database, dumping records into log file");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->write_log_to_file($data, 1);
        }
    }
}
