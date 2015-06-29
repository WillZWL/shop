<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Supplier_service extends Base_service
{
    private $sp_dao;
    private $sr_dao;
    private $cpssl_dao;
    private $dex_srv;
    private $config_srv;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Supplier_dao.php");
        $this->set_dao(new Supplier_dao());
        include_once(APPPATH."libraries/dao/Supplier_prod_dao.php");
        $this->set_sp_dao(new Supplier_prod_dao());
        include_once(APPPATH."libraries/dao/Supplier_region_dao.php");
        $this->set_sr_dao(new Supplier_region_dao());
        include_once(APPPATH."libraries/dao/Cps_sourcing_list_dao.php");
        $this->set_cpssl_dao(new Cps_sourcing_list_dao());
        include_once(APPPATH."libraries/service/Data_exchange_service.php");
        $this->set_dex_srv(new Data_exchange_service());
        include_once(APPPATH."libraries/service/Context_config_service.php");
        $this->set_config_srv(new Context_config_service());
    }

    public function get_sp_dao()
    {
        return $this->sp_dao;
    }

    public function set_sp_dao(Base_dao $dao)
    {
        $this->sp_dao = $dao;
    }

    public function get_sr_dao()
    {
        return $this->sr_dao;
    }

    public function set_sr_dao(Base_dao $dao)
    {
        $this->sr_dao = $dao;
    }

    public function get_dex_srv()
    {
        return $this->dex_srv;
    }

    public function set_dex_srv(Base_service $srv)
    {
        $this->dex_srv = $srv;
    }

    public function get_config_srv()
    {
        return $this->config_srv;
    }

    public function set_config_srv(Base_service $srv)
    {
        $this->config_srv = $srv;
    }

    public function get_supplier_prod_history_for_report($start_time='',
        $end_time='', $platform_id='WSGB', $classname='Product_cost_change_dto')
    {
        return $this->get_sp_dao()->get_supplier_prod_history_for_report($start_time,
            $end_time, $platform_id, $classname);
    }

    public function check_valid_supplier_cost($sku)
    {
        return $this->get_dao()->check_valid_supplier_cost($sku);
    }

    public function get_cpssl_dao()
    {
        return $this->cpssl_dao;
    }

    public function set_cpssl_dao(Base_dao $dao)
    {
        $this->cpssl_dao = $dao;
    }

    public function download_supplier_status_csv()
    {
        define('DATAPATH', $this->get_config_srv()->value_of('data_path'));

        $arr = $this->get_dao()->get_supplier_status_dto(array(), array('orderby'=>'p.sku', 'limit'=>-1));
        if (!$arr)
        {
            return;
        }

        $new_list = array();
        foreach ($arr as $row)
        {
            $status_code = $row->get_supplier_status();
            switch ($status_code)
            {
                case 'A' : $row->set_supplier_status_desc('Readily Available'); break;
                case 'O' : $row->set_supplier_status_desc('Temp Out of Stock'); break;
                case 'C' : $row->set_supplier_status_desc('Stock Constraint'); break;
                case 'L' : $row->set_supplier_status_desc('Last Lot'); break;
                case 'D' : $row->set_supplier_status_desc('Discontinued'); break;
                default  : $row->set_supplier_status_desc('');
            }

            $new_list[] = $row;
        }

        $out_xml = new Vo_to_xml($new_list, '');
        $out_csv = new Xml_to_csv('', APPPATH . 'data/supplier_status_xml2csv.txt', TRUE, ',');

        $data_feed = $this->get_dex_srv()->convert($out_xml, $out_csv);
        if($data_feed)
        {
            $backup_path = DATAPATH . 'feeds/supplier_status/' . date('Y') . '/' . date('m');
            if (is_dir($backup_path) === FALSE)
            {
                if (@mkdir($backup_path, 0775, TRUE) === FALSE)
                {
                    return FALSE;
                }
            }

            $filename = 'supplier_status_' . date('YmdHis') . '.csv';
            $file_fullpath = $backup_path . '/' . $filename;
            $fp = fopen($file_fullpath, 'w');
            if(fwrite($fp, $data_feed))
            {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Expires: ' . gmdate('D, d M Y H:i:s', gmmktime() - 3600) . ' GMT');
                header('Content-Length: ' . filesize($file_fullpath));
                $fp = fopen($file_fullpath, 'r');
                fpassthru($fp);
            }
        }
    }

    public function download_supplier_cost_csv($platform_id='')
    {
        if (empty($platform_id))
        {
            return;
        }

        define('DATAPATH', $this->get_config_srv()->value_of('data_path'));

        $arr = $this->get_dao()->get_supplier_cost_dto(array('v.platform_id'=>strtoupper($platform_id)), array('orderby'=>'p.sku', 'limit'=>-1));
        if (!$arr)
        {
            return;
        }

        $new_list = array();
        foreach ($arr as $row)
        {
            $new_list[] = $row;
        }

        $out_xml = new Vo_to_xml($new_list, '');
        $out_csv = new Xml_to_csv('', APPPATH . 'data/supplier_cost_xml2csv.txt', TRUE, ',');

        $data_feed = $this->get_dex_srv()->convert($out_xml, $out_csv);
        if($data_feed)
        {
            $backup_path = DATAPATH . 'feeds/supplier_cost/' . strtoupper($platform_id) . '/' . date('Y') . '/' . date('m');
            if (is_dir($backup_path) === FALSE)
            {
                if (@mkdir($backup_path, 0775, TRUE) === FALSE)
                {
                    return FALSE;
                }
            }

            $filename = 'supplier_cost_' . date('YmdHis') . '.csv';
            $file_fullpath = $backup_path . '/' . $filename;
            $fp = fopen($file_fullpath, 'w');
            if(fwrite($fp, $data_feed))
            {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Expires: ' . gmdate('D, d M Y H:i:s', gmmktime() - 3600) . ' GMT');
                header('Content-Length: ' . filesize($file_fullpath));
                $fp = fopen($file_fullpath, 'r');
                fpassthru($fp);
            }
        }
    }

    public function get_item_cost_in_hkd($sku)
    {
        $where = array();
        $where["prod_sku"] = $sku;
        $where["order_default"] = 1;

        $spObj = $this->get_sp_dao()->get($where);
        if ($spObj)
        {
            return $spObj->get_cost();
        }
        else
        {
            $email_subject = "[VB] No supplier cost in" . __METHOD__ . ":" . __LINE__;
            $message = $this->get_sp_dao()->db->last_query();
            mail("oswald-alert@eservicesgroup.com", $email_subject, $message, "From: Admin <admin@valuebasket.com>" . "\r\n");
            return 0;
        }
    }
}

/* End of file supplier_service.php */
/* Location: ./system/application/libraries/service/Supplier_service.php */