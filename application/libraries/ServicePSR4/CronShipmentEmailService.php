<?php
namespace ESG\Panther\Service;
use PHPMailer;

class CronShipmentEmailService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendEmail($date = '')
    {
        $option = $where = array();
        $where["so.dispatch_date >="] = $date." 00:00:00";
        $where["so.dispatch_date <="] = $date." 23:59:59";
        $option['limit'] = -1;
        $option["groupby"] = "sa.so_no";
        $list = $this->getDao('SoAllocate')->getShipmentList($where, $option);
        $data = $this->genMsg($list);
        $csv = $data['csv'];
        $title = "Panther Shipment on ".$date;
        $msg = "Summarize that the same days total shipment as warehouse dispatched<br />";
        $msg = $msg.$data['msg']."<br /><br />You can view shipment as warehouse dispatched detail info by attachment";
        $filename = 'Shipment' . $date . '.csv';
        $email = 'finance@valuebasket.com';
        $this->_sendEmail($email, $title, $msg, $csv, $filename);
    }


    public function _sendEmail($email = '', $title = '', $msg = '', $csv_content = '', $filename = '')
    {
        $phpmail = new PHPMailer;
        $phpmail->isMail();
        $phpmail->From = "do_not_reply@eservicesgroup.com";
        $phpmail->FromName = "Panther";
        $phpmail->AddAddress($email);
        $phpmail->AddAddress('will.zhang@eservicesgroup.com');
        $phpmail->Subject = $title;
        $phpmail->IsHTML(true);
        $phpmail->Body = $msg;
        $phpmail->AddStringAttachment($csv_content, $filename);
        $result = $phpmail->Send();
    }

    private function genMsg($data=array())
    {
        $csv = "Order No,Warehouse\r\n";
        if(!empty($data)) {
            $data = $this->processDataRow($data);

            foreach ($data as $key => $value) {
                $email_msg .= $key." : ".count($value)."<br />";
                foreach($value as $row) {
                    $csv .= $row['so_no'].",".$row['warehouse_id']."\r\n";
                }
                $res['msg'] = $email_msg;
            }
            $res['csv'] = $csv;
        }
        return $res;
    }

    private function processDataRow($arr = array())
    {
        if(!empty($arr)) {
            $n_arr = array();
            $warehouse_list = $this->getDao('Warehouse')->getList(array(), array('limit'=>-1));
            foreach ($warehouse_list as $w_value) {
                $n_arr[$w_value->getWarehouseId()] = array();
            }
            foreach ($arr as $key => $value) {
                $new_arr['so_no'] = $value->getSoNo();
                $new_arr['warehouse_id'] = $value->getWarehouseId();
                $n_arr[$value->getWarehouseId()][] = $new_arr;
            }
        }
        return $n_arr;
    }
}