<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Adroll_product_feed_service extends Base_service
{
    const ORDER_TYPE_ALERT1 = "ALERT1";
    const ORDER_TYPE_ALERT2 = "ALERT2";

    public $so_dao;
    private $_ftp;
    private $_ftp_info_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->_config_service = new Context_config_service();
        include_once(APPPATH . "libraries/service/Ftp_connector.php");
        $this->_ftp = new Ftp_connector();
        include_once(APPPATH . "libraries/dao/Ftp_info_dao.php");
        $this->_ftp_info_dao = new Ftp_info_dao();
    }

    public function send_report($platform_id)
    {
        $this->send_adroll_product_feed($platform_id);
    }

    public function send_adroll_product_feed($platform_id)
    {
        define('DATAPATH', "/var/data/valuebasket.com/");
        $where = array();
        $where["c.priority"] = 1;
        $where["p.platform_id IN ('WEBFR', 'WEBES', 'WEBGB', 'WEBIT', 'WEBNZ')"] = NULL;
        $where["a.status"] = 2;

        $option = array("group_by" => "a.sku, p.platform_id");

        $orderList = $this->get_so_dao()->adroll_product_feed_list($where, $option);
        //var_dump($this->get_so_dao()->db->last_query()); die();

        foreach ($orderList as $list) {
            $urlhead = '';
            //$app_pic = $list->pic;
            $data['sku'] = $list['sku'];
            $data['name'] = $list['prod_name'];
            $urlname = str_replace(' ', '-', $list['name']);

            if ($list['platform_id'] == 'WEBFR') {
                $data['url'] = 'http://www.valuebasket.fr/fr_FR/' . $urlname . '/mainproduct/view/' . $list['sku'];
            } elseif ($list['platform_id'] == 'WEBES') {
                $data['url'] = 'http://www.valuebasket.es/es_ES/' . $urlname . '/mainproduct/view/' . $list['sku'];
            } elseif ($list['platform_id'] == 'WEBGB') {
                $data['url'] = 'http://www.valuebasket.com/en_GB/' . $urlname . '/mainproduct/view/' . $list['sku'];
            } elseif ($list['platform_id'] == 'WEBIT') {
                $data['url'] = 'http://www.valuebasket.com/it_IT/' . $urlname . '/mainproduct/view/' . $list['sku'];
            } elseif ($list['platform_id'] == 'WEBNZ') {
                $data['url'] = 'http://www.valuebasket.co.nz/' . $urlname . '/mainproduct/view/' . $list['sku'];
            }

            $data['pic'] = 'http://cdn.valuebasket.com/808AA1/vb//images/product/' . $list['pic'];
            if ($list['sign_pos'] = 'L') {
                $data['price'] = $list['sign'] . ' ' . number_format($list['price'], $list['dec_place'], $list['dec_point'], $list['thousands_sep']);
            } else {
                $data['price'] = number_format($list['price'], $list['dec_place'], $list['dec_point'], $list['thousands_sep']) . ' ' . $list['sign'];
            }
            $data['platform'] = $list['platform_id'];

            $order[] = $data;
        }
        //echo '<pre>' ;var_dump($order); die();
        $csv = $this->gen_csv($order);

        //mkdir(DATAPATH . "feeds/adroll", 0775, true);
        chmod(DATAPATH . "feeds/adroll", 0775);
        if (!file_exists(DATAPATH . "feeds/adroll/$platform_id")) {
            mkdir(DATAPATH . "feeds/adroll/$platform_id", 0775, true);
        }
        $filename = 'adroll_' . $platform_id . '.csv';
        $fp = fopen(DATAPATH . "feeds/adroll/$platform_id/" . $filename, 'w');
        $fpath = DATAPATH . "feeds/adroll/$platform_id/" . $filename;

        if (fwrite($fp, $csv)) {
            // if($country == "FR")
            //{
            //$this->ftp_feeds(DATAPATH . "feeds/adroll/$platform_id/" . $filename, $filename, $this->get_ftp_name());
            //}
            //$this->attach_file(DATAPATH . 'feeds/graysonline/' . $filename);


            header("Content-type: text/csv");
            header("Cache-Control: no-store, no-cache");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            echo $csv;

        } else {
            $title = "Adroll CSV upload fail -  Fails to create Adroll File";
            $message = "Fails to create Adroll File";

            $this->_email_report($csv, $title, $message, $filename, "jerry.lim@eservicesgroup.com");

            $subject = "<DO NOT REPLY> Fails to create Adroll File";
            $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
            $this->error_handler($subject, $message);
        }

        //Send file via sftp to server

        $strServer = "ftp.adroll.com";
        $strServerPort = "22";
        $strServerUsername = "valuebasket";
        $strServerPassword = 'EC$PDZ@3';
        //$csv_filename = "Test_File.csv";

        //connect to server
        $resConnection = ssh2_connect($strServer, $strServerPort);

        if (ssh2_auth_password($resConnection, $strServerUsername, $strServerPassword)) {
            //Initialize SFTP subsystem

            echo "connected";
            $resSFTP = ssh2_sftp($resConnection);
            if (!$resSFTP) {

                $title = "Adroll CSV upload fail -  Fail to Connect to SFTP";
                $message = "Fail to connect to SFTP";

                $this->_email_report($csv, $title, $message, $filename, "jerry.lim@eservicesgroup.com");
                throw new Exception("Could not initialize SFTP subsystem.");
            }

            $resFile = @fopen("ssh2.sftp://$resSFTP" . ssh2_sftp_realpath($resSFTP, ".") . "/$filename", 'w');
            if (!$resFile)
                throw new Exception("Could not open file: $resSFTP/$filename");

            $srcFile = fopen(DATAPATH . "feeds/adroll/$platform_id/" . $filename, 'r');
            $writtenBytes = stream_copy_to_stream($srcFile, $resFile);
            fclose($resFile);
            fclose($srcFile);

        } else {
            $title = "Adroll CSV upload fail -  Authentication Failure in SFTP";
            $message = "Authentication Failure in SFTP";

            $this->_email_report($csv, $title, $message, $filename, "jerry.lim@eservicesgroup.com");

            throw new Exception("Could not authenticate with username $username " .
                "and password $password.");
        }

    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function set_so_dao($value)
    {
        $this->so_dao = $value;
    }

    private function gen_csv($data = array())
    {
        $csv = "";
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if ($key == 0) {
                    foreach ($value as $label => $v) {
                        $csv .= "\"$label\",";
                    }

                    $csv .= "\n";
                }
                //$svalue = '"'.$value.'"';
                $csv .= '"' . implode('","', $value) . '"';
                $csv .= "\n";
            }
        }

        return $csv;
    }

    private function _email_report($csv = "", $title = "", $message = "", $filename, $email1, $email2 = null)
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new PHPMailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.com>";

        $phpmail->AddAddress($email1);

        if ($email2)
            $phpmail->AddAddress($email2);

        $phpmail->Subject = $title;
        $phpmail->IsHTML(false);
        $phpmail->Body = $message;
        $phpmail->AddStringAttachment($csv, $filename, 'base64', 'text/csv');

        $result = $phpmail->Send();
    }

    private function _upload_to_adroll($local_path, $remote_path, $filename)
    {
        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();

        $ftp_info_obj = $this->_ftp_info_dao->get(array("name" => "ADROLL"));
        $this->_ftp->set_remote_site($server = $ftp_info_obj->get_server());
        $this->_ftp->set_username($ftp_info_obj->get_username());
        $this->_ftp->set_password($ftp_info_obj->get_password());
        $this->_ftp->set_port($ftp_info_obj->get_port());
        $this->_ftp->set_is_passive($ftp_info_obj->get_pasv());

        if ($this->_ftp->connect() !== FALSE) {
            // var_dump('hello'); die();
            if ($this->_ftp->login() !== FALSE) {
                return $this->_ftp->putfile($local_path . $filename, $remote_path . $filename);
            } else
                return FALSE;
        } else
            return FALSE;
    }
}

/* End of file Fireman_service.php */
/* Location: ./system/application/libraries/service/Fireman_service.php */
