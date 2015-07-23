<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Base_batch_service extends Base_service
{
    private $tlog_dao;
    private $ftp;
    private $fi_dao;
    private $config;
    private $valid;

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Batch_dao.php");
        $this->set_dao(new Batch_dao());
        include_once(APPPATH . "libraries/dao/Transmission_log_dao.php");
        $this->set_tlog_dao(new Transmission_log_dao());
        include_once(APPPATH . "libraries/service/Ftp_connector.php");
        $this->set_ftp(new Ftp_connector());
        include_once(APPPATH . "libraries/dao/Ftp_info_dao.php");
        $this->set_fi_dao(new Ftp_info_dao());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        include_once(APPPATH . "libraries/service/Validation_service.php");
        $this->set_valid(new Validation_service());
        include_once(APPPATH . "libraries/service/Event_service.php");
        $this->set_event(new Event_service());
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $this->set_dex(new Data_exchange_service());
    }

    public function set_event($value)
    {
        $this->event = $value;
    }

    public function set_dex($value)
    {
        $this->dex = $value;
    }

    public function check_and_update_batch_status($batch_id = "")
    {
        if ($batch_id == "") {
            return FALSE;
        }

        return $this->get_dao()->get_batch_status_for_order($batch_id);
    }

    public function get_tlog_dao()
    {
        return $this->tlog_dao;
    }

    public function set_tlog_dao(Base_dao $dao)
    {
        $this->tlog_dao = $dao;
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function get_valid()
    {
        return $this->valid;
    }

    public function set_valid($value)
    {
        $this->valid = $value;
    }

    public function get_event()
    {
        return $this->event;
    }

    public function get_dex()
    {
        return $this->dex;
    }

    public function generate_password($len = 8)
    {

        $length = $len;
        $characters = "0123456789abcdeifghjkmnopqrstuvwxyz";
        $string = "";

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }
        return $string;
    }

    function trim_proddesc($feeder, $proddesc)
    {
        include_once(APPPATH . "helpers/string_helper.php");
        $proddesc = str_replace("'", "", $proddesc);
        $proddesc = str_replace(",", "", $proddesc);
        $proddesc = str_replace("|", "-", $proddesc);

//      $prodchrs = array (chr(20), chr(96), chr(20), chr(10), chr(13), chr(9), chr(124));
//      $prodrepl = array("-","-","-","-","-","-","-");
//      $proddesc = str_replace($prodchrs, $prodrepl, $proddesc);
        $proddesc = str_replace(array("\r\n", "\r", "\n"), "-", $proddesc);

        $proddesc = str_replace('<br />', '-', $proddesc);
        $proddesc = str_replace('<BR />', '-', $proddesc);
        $proddesc = str_replace('<br >', '-', $proddesc);
        $proddesc = str_replace('<BR >', '-', $proddesc);
        $proddesc = str_replace('<b>', '', $proddesc);
        $proddesc = str_replace('</b>', '', $proddesc);
        $proddesc = str_replace('<B>', '', $proddesc);
        $proddesc = str_replace('</B>', '', $proddesc);

        if (strlen($proddesc) > 1900) {
            $CIproddesc = cutstr($proddesc, 1900, ' . . . .');
            $RVproddesc = trim(str_replace("-", " ", $CIproddesc));
        } else {
            $CIproddesc = trim($proddesc);
            $RVproddesc = trim(str_replace("-", " ", $CIproddesc));
        }
        $CIproddesc = trim($CIproddesc);

        if (strlen($proddesc) > 200) {
            $proddesc = cutstr($proddesc, 200, ' . . . .');
        }

        $proddesc = trim($proddesc);

//      $EMAXproddesc = $CIproddesc;

//      if($feeder =='REEVOO'){ $proddesc =$EMAXproddesc; }
        if ($feeder == 'REEVOO') {
            $proddesc = $RVproddesc;
        }

        return $proddesc;
    }

    public function ftp_upload($params = array())
    {
        if (count($params) === 0) {
            return array('hostname_key, remote_path, filename and local_path are not submitted');
        }

        $reason = array();

        if (!array_key_exists('hostname_key', $params)) {
            $reason[] = 'hostname_key is not submitted';
        }

        if (!array_key_exists('remote_path', $params)) {
            $reason[] = 'remote_path is not submitted';
        }

        if (!array_key_exists('filename', $params)) {
            $reason[] = 'filename is not submitted';
        }

        if (!array_key_exists('local_path', $params)) {
            $reason[] = 'local_path is not submitted';
        }

        if (count($reason)) {
            return $reason;
        }

        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();

        $ftp = $this->get_ftp();
        $ftp_obj = $this->get_fi_dao()->get(array('name' => $params['hostname_key']));

        if (!$ftp_obj) {
            return array('No database entry is found for ' . $params['host_name_key']);
        }

        $ftp->set_remote_site($server = $ftp_obj->get_server());
        $ftp->set_username($ftp_obj->get_username());
        $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
        $ftp->set_port($ftp_obj->get_port());
        $ftp->set_is_passive($ftp_obj->get_pasv());

        if ($ftp->connect() !== FALSE) {
            if ($ftp->login() !== FALSE) {
                //print_r($params);
                $result = $ftp->putfile($params['local_path'] . '/' . $params['filename'],
                    $params['remote_path'] . '/' . $params['filename']);

                if ($result === FALSE) {
                    return array('Failed to upload file to remote site');
                }
            } else {
                return array('Unable to login to ftp site');
            }

            $ftp->close();
        } else {
            return array('Unable to connect to ftp site');
        }

        return $reason;
    }

    public function get_ftp()
    {
        return $this->ftp;
    }

    public function set_ftp($value)
    {
        $this->ftp = $value;
    }

    public function get_fi_dao()
    {
        return $this->fi_dao;
    }

    //public function ftp_upload($hostname_key, $remote_path, $filename, $local_path)

    public function set_fi_dao(Base_dao $dao)
    {
        $this->fi_dao = $dao;
    }
}




