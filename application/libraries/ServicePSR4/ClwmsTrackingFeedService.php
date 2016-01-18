<?php
namespace ESG\Panther\Service;

class ClwmsTrackingFeedService extends BaseService
{
    private $wms_url = "http://remote.eservicesgroup.com:8080/WMS.Server.Web/Service.asmx/GetTrackingFeed";
    private $username = "clwms";
    private $password = "CLUUWMS56";
    private $retailer_name = "PT";
    private $it_dao;
    private $itf_dao;

    const SCHEDULE_ID= "WMS_GET_TRACKING_FEED";

    public function __construct()
    {
        parent::__construct();
    }

    public function processTrackingFeed()
    {
        set_time_limit(0);

        $id = self::SCHEDULE_ID;
        $current_time = date("Y-m-d H:i:s");

        if ($it_obj = $this->insertInterfaceTracking($id, $current_time)) {
            $xml = $this->getTrackingFeedXml($it_obj);
            $this->processXmlToInterfaceFeed($xml, $it_obj);
            $this->checkInterfaceFeedData($it_obj);
            $this->updateDispatchTrackingNumber($it_obj);
            $this->sendEmailInvestigated($it_obj);
            $this->updatLastTime($id, $current_time);
        }

    }
    public function insertInterfaceTracking($id, $current_time)
    {
        $last_time = $this->getLastTime($id);

        $obj = $this->getDao('InterfaceTracking')->get(['schedule_job_id'=>$id, 'start_time'=>$last_time]);
        if ( !$obj ) {
            $vo = $this->getDao('InterfaceTracking')->get();
            $obj = clone $vo;
            $obj->setScheduleJobId($id);
            $obj->setStartTime($last_time);
            $obj->setEndTime($current_time);
            $obj->setStatus('N');

            if ($it_obj = $this->getDao('InterfaceTracking')->insert($obj)) {
                return $it_obj;
            }
        } else {
            return $obj;
        }

        return false;
    }

    public function getTrackingFeedXml($it_obj)
    {
        $start_hkt_time = $this->toWmsHktTime($it_obj->getStartTime());
        $end_hkt_time = $this->toWmsHktTime($it_obj->getEndTime());

        $wms_api_url = $this->getWmsUrl() ."?clLogin=".$this->getUsername()
                                            ."&clPwd=".$this->getPassword()
                                            ."&frmDate=".$start_hkt_time
                                            ."&toDate=".$end_hkt_time
                                            ."&retailer_name=".$this->getRetailerName();

        return $this->callCurl( $wms_api_url );
    }

    public function processXmlToInterfaceFeed($xml, $it_obj)
    {
        $xmlobj = simplexml_load_string($xml, 'SimpleXMLElement');
        if ($xmlobj->result == "success") {
            if ( $xmlobj->orders->order ) {
                foreach ($xmlobj->orders->order as $order) {
                    $item_arr = [];
                    if ( $order->skus->sku ) {
                        foreach ($order->skus->sku as $sku) {
                            $item_arr[(string) $sku->master_sku] = (string) $sku->warehouse_id;
                        }
                    }

                    $item_josn = json_encode($item_arr);
                    $data['tracking_id'] = $it_obj->getId();
                    $data['so_no'] = (int) $order->so_no;
                    $data['retailer_name'] = (string) $order->retailer_name;
                    $data['tracking_no'] = (string) $order->tracking_no;
                    $data['weight_in_kg'] = (double) $order->weight_in_kg;
                    $data['courier_name'] = (string) $order->courier_name;
                    $data['courier_id'] = (string) $order->courier_id;
                    $data['courier_id_num'] = (int) $order->courier_id_num;
                    $data['items'] = $item_josn;

                    if ( ! $this->getDao('InterfaceTrackingFeed')->get(['tracking_id'=>$it_obj->getId(), 'so_no'=>$data['so_no']]) ) {
                        $this->insertInterfaceTrackingFeed($data);
                    }
                }

            } else {
                $it_obj->setStatus('NR');
                $this->getDao('InterfaceTracking')->update($it_obj);
            }
        } else {
            $it_obj->setStatus('F');
            $this->getDao('InterfaceTracking')->update($it_obj);
        }
    }

    private function insertInterfaceTrackingFeed($data)
    {
        $vo = $this->getDao('InterfaceTrackingFeed')->get();
        $obj = clone $vo;
        $obj->setTrackingId( $data['tracking_id'] );
        $obj->setSoNo( $data['so_no'] );
        $obj->setRetailerName( $data['retailer_name'] );
        $obj->setTrackingNo( $data['tracking_no'] );
        $obj->setWeightInKg( $data['weight_in_kg'] );
        $obj->setCourierName( $data['courier_name'] );
        $obj->setCourierId( $data['courier_id'] );
        $obj->setCourierIdNum( $data['courier_id_num'] );
        $obj->setItems( $data['items'] );
        $obj->setSendEmail(0);
        $obj->setStatus('N');
        $obj->setRefundStatus(0);
        $obj->setHoldStatus(0);
        $this->getDao('InterfaceTrackingFeed')->insert($obj);
    }

    public function checkInterfaceFeedData($it_obj)
    {
        if ($it_obj->getStatus() === 'N') {
            $where = $option = [];
            $where['tracking_id'] = $it_obj->getId();
            $where['status'] = 'N';
            $option['limit'] = -1;
            if ($itf_objlist = $this->getDao('InterfaceTrackingFeed')->getList($where, $option)) {
                foreach ($itf_objlist as $itf_obj) {
                    $wh = [];
                    $wh['so.so_no'] = $itf_obj->getSoNo();
                    $res = $this->getDao('InterfaceTrackingFeed')->getSoAllocateShipment($wh);
                    if ( !empty( $res ) ) {
                        $note = "";
                        $itf_obj->setShNo($res['sh_no']);

                        if ($res['courier_id'] && $res['courier_id'] <> $itf_obj->getCourierId()) {
                            $itf_obj->setVbCourierId($res['courier_id']);
                            $itf_obj->setSendEmail(1);
                        }

                        if ($res['refund_status'] != 0 && $res['hold_status'] != 0) {
                            $itf_obj->setNotes("The so_no refund_status > 0 and hold_status > 0");
                            $itf_obj->setRefundStatus($res['refund_status']);
                            $itf_obj->setSendEmail(1);
                            $itf_obj->setStatus('I');
                        } else if ($res['refund_status'] != 0) {
                            $itf_obj->setNotes("The so_no refund_status > 0");
                            $itf_obj->setRefundStatus($res['refund_status']);
                            $itf_obj->setSendEmail(1);
                            $itf_obj->setStatus('I');
                        } else if ($res['hold_status'] != 0) {
                            $itf_obj->setNotes("The so_no be hold_status > 0");
                            $itf_obj->setHoldStatus($res['hold_status']);
                            $itf_obj->setSendEmail(1);
                            $itf_obj->setStatus('I');
                        } else if ( !empty($res['tracking_no']) ) {

                            $itf_obj->setNotes("Tracking number already exist");
                            $itf_obj->setHistoryTrackingNo($res['tracking_no']);
                            $itf_obj->setSendEmail(1);
                            $itf_obj->setStatus('I');

                        } else {

                            $itf_obj->setStatus('R');

                        }
                    } else {
                        $itf_obj->setNotes('No found shipment record');
                        $itf_obj->setStatus('F');
                    }
                    $this->getDao('InterfaceTrackingFeed')->update($itf_obj);
                }
            }
        }
    }

    public function updateDispatchTrackingNumber($it_obj)
    {
        if ($it_obj->getStatus() === 'N') {
            $tracking_id = $it_obj->getId();
            $where['tracking_id'] = $tracking_id;
            $where['status'] = 'R';
            $where['sh_no is not null'] = null;
            $option['limit'] = -1;
            if ($itf_objlist = $this->getDao('InterfaceTrackingFeed')->getList($where, $option)) {
                foreach ($itf_objlist as $itf_obj) {
                    $this->update_dispatch($itf_obj, $itf_obj->getSoNo(), $itf_obj->getShNo(), $itf_obj->getTrackingNo(), $itf_obj->getCourierId());
                }
            }

            $item_total = $this->getDao('InterfaceTrackingFeed')->getNumRows(['tracking_id'=>$tracking_id]);
            $success_total = $this->getDao('InterfaceTrackingFeed')->getNumRows(['tracking_id'=>$tracking_id, 'status'=>'S']);

            if ($item_total == 0) {
                $status = 'NR';
            } else if ($item_total == $success_total) {
                $status = 'C';
            } else {
                $status = 'CE';
            }

            $it_obj->setStatus($status);
            $this->getDao('InterfaceTracking')->update($it_obj);
            return $status;
        }

        return false;
    }

    public function update_dispatch($itf_obj, $so_no, $sh_no, $tracking_no, $courier_id)
    {
        $u_where["modify_on <="] = date("Y-m-d H:i:s");
        $r_where["soal.status"] = 2;
        if ($valueArr["dispatchType"] != 'r') {
            $r_where["so.hold_status"] = "0";
            $r_where["so.refund_status"] = "0";
        }
        $r_option["limit"] = -1;
        $r_where["soal.sh_no"] = "{$sh_no}";

        $rlist = $this->getDao('SoAllocate')->getInSoList($r_where, $r_option);

        $update_sh = [];
        foreach ($rlist as $obj) {
            $sh_no = $obj->getShNo();
            $line_no = $obj->getLineNo();
            $item_sku = $obj->getItemSku();
            $al_id = $obj->getId();
            $update_sh[$sh_no][$al_id] = $obj;
        }

        if ($update_sh) {
            foreach ($update_sh as $sh_no => $soal_list) {
                $error = "";
                $success = 1;
                $no_courier_id = false;
                $this->getDao('So')->db->trans_start();
                $sosh_obj = $this->getDao('SoShipment')->get(["sh_no" => $sh_no]);

                $sosh_obj->setStatus("2");
                if (!$sosh_obj->getCourierId()) {
                    if ($this->getDao('Courier')->get(['courier_id'=>$courier_id])) {
                        $sosh_obj->setCourierId($courier_id);
                    } else {
                        $success = 0;
                        $no_courier_id = true;
                    }
                }
                $sosh_obj->setTrackingNo($tracking_no);
                if ($this->getDao('SoShipment')->update($sosh_obj)) {
                    foreach ($soal_list as $al_id => $soal_obj) {
                        $soal_obj->setStatus("3");
                        if (!$this->getDao('SoAllocate')->update($soal_obj)) {
                            $success = 0;
                            break;
                        }
                    }
                } else {
                    $success = 0;
                }
                if ($success) {
                    $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);
                    if ($this->getDao('SoAllocate')->getNumRows(["so_no" => $so_no, "status" => 1]) == 0) {
                        if (!$this->getService('So')->updateCompleteOrder($so_obj, 0)) {
                            $success = 0;
                        }

                        if ($so_obj->getBizType() == 'SPECIAL') {
                            $special_orders[] = $so_obj->getSoNo();
                        }
                    }

                    if (substr($so_obj->getPlatformId(), 0, 2) != "AM" && substr($so_obj->getPlatformId(), 0, 2) != "TS" && $so_obj->getBizType() != "SPECIAL") {
                        $this->fireDispatch($so_obj, $sh_no);
                    }
                }

                if (!$success)
                {
                    $this->get_so_srv()->get_dao()->trans_rollback();
                    $itf_obj->setStatus('F');
                    if ($no_courier_id) {
                        $subject = "";
                        $bodynote = "Update fail, Panhter courier table cannot found about courier_id:$courier_id recode";
                        $itf_obj->setNotes($bodynote);
                        $this->sendEmail($subject, $bodynote);
                    } else {
                        $itf_obj->setNotes('Update tracking number fail');
                    }
                } else {
                    $itf_obj->setStatus('S');
                }

                $this->getDao('So')->db->trans_complete();
            }

            if ($special_orders) {
                foreach ($special_orders as $key => $so_no) {
                    $so_w_reason = $this->getDao('So')->getSoWithReason(['so.so_no' => $so_no], ['limit' => 1]);

                    if ($so_w_reason->getReasonId() == '34') {
                        $aps_direct_order[] = $so_w_reason->getSoNo();
                    }
                }

                $aps_direct_orders = implode(',', $aps_direct_order);
                $where = "where so.so_no in (" . $aps_direct_orders . ")";
                $content = $this->getDao('So')->getApsDirectOrderCsv($where);

                $phpmail = new PHPMailer;

                $phpmail->IsSMTP();
                $phpmail->From = "VB APS ORDER ALERT <do_not_reply@valuebasket.com>";
                $phpmail->AddAddress("bd.platformteam@eservicesgroup.net");

                $phpmail->Subject = " DIRECT APS ORDERS";
                $phpmail->IsHTML(false);
                $phpmail->Body = "Attached: DIRECT APS ORDERS.";
                $phpmail->AddStringAttachment($content, "direct_aps_info.csv");
                $result = $phpmail->Send();

            }
        } else {
            $itf_obj->setStatus('F');
            $itf_obj->setNotes('Update tracking number fail');
        }

        $this->getDao('InterfaceTrackingFeed')->update($itf_obj);
    }

    private function sendEmailInvestigated($it_obj)
    {
        if ($itf_objlist = $this->getDao('InterfaceTrackingFeed')->getList(['tracking_id'=>$it_obj->getId(), 'send_email'=>1], ['limit'=>-1])) {
            $count = count((array) $itf_objlist);

            $is_send_1 = $is_send_2 = $is_send_3 = false;
            $subject_1 = $subject_2 = $subject_3 = $bodynote_1 = $bodynote_2 = $bodynote_3 = "";

            $subject_1 = "[PT] Alert, Tracking number already exist and different, API cannot overwrite";
            $bodynote_1 = "VB already exist tracking_no and different, API cannot overwrite, need Investigated\r\n";

            $subject_2 = "[PT] Alert, Need to WMS change courier_id, API cannot overwrite";
            $bodynote_2 = "Need to WMS change courier_id, Panther  with WMS for courier_id are different, \r\n";

            $subject_3 = "[PT] Alert, Some orders should not be ship";
            $bodynote_3 = "Some orders should not be ship, refund_status > 0 or hold_status > 0\r\n";

            foreach ($itf_objlist as $itf_obj) {
                $so_no = $itf_obj->getSoNo();
                $tracking_no = $itf_obj->getTrackingNo();
                $history_tracking_no = $itf_obj->getHistoryTrackingNo();
                $courier_id = $itf_obj->getCourierId();
                $vb_courier_id = $itf_obj->getVbCourierId();
                $refund_status = $itf_obj->getRefundStatus();
                $hold_status = $itf_obj->getHoldStatus();

                $itf_obj->setSendEmail(2);
                if ($this->getDao('InterfaceTrackingFeed')->update($itf_obj)) {
                    if ($history_tracking_no && strtolower($history_tracking_no) <> strtolower($tracking_no)) {
                        $is_send_1 = true;
                        $bodynote_1 .= "so_no: {$so_no}, tracking_no: {$history_tracking_no}, [WMS] tracking_no: {$tracking_no}\r\n";
                    }

                    if ($vb_courier_id && $vb_courier_id <> $courier_id) {
                        $is_send_2 = true;
                        $bodynote_2 .= "so_no: {$so_no}, courier_id: {$vb_courier_id}, [WMS] courier_id: {$courier_id}\r\n";
                    }

                    if ($refund_status != 0 && $hold_status != 0) {
                        $is_send_3 = true;
                        $bodynote_3 .= "so_no: {$so_no}, refund_status: {$refund_status}, hold_status: {$hold_status}\r\n";
                    } else if ($refund_status != 0) {
                        $is_send_3 = true;
                        $bodynote_3 .= "so_no: {$so_no}, refund_status: {$refund_status}\r\n";
                    } else if ($hold_status != 0) {
                        $is_send_3 = true;
                        $bodynote_3 .= "so_no: {$so_no}, hold_status: {$hold_status}\r\n";
                    }
                }
            }

            if ($is_send_1) {
                $this->sendEmail($subject_1, $bodynote_1);
            }

            if ($is_send_2) {
                $this->sendEmail($subject_2, $bodynote_2);
            }

            if ($is_send_3) {
                $this->sendEmail($subject_3, $bodynote_3);
            }
        }
    }

    private function callCurl( $url )
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $server_result = curl_exec($ch);
        curl_close($ch);

        return $server_result;
    }

    private function toWmsHktTime($gmt_time)
    {
        # GMT with HKT 8 hours time difference
        # Format date of DATE_ATOM
        $hkt_time = date('Y-m-d H:i:s',strtotime("$gmt_time+8 hour"));
        $date_time = date(DATE_ATOM, strtotime($hkt_time));
        $date_time = substr($date_time, 0, strrpos($date_time, '+'));

        return $date_time;
    }

    private function sendEmail($subject ,$bodynote) {base_url();
        $header = "From: admin@digitaldiscount.co.uk\r\n";
        $user_email = $_SESSION["user"]["email"] ? ", {$_SESSION["user"]["email"]}" : "";
        $to = "{$user_email}, brave.liu@eservicesgroup.com";
        mail($to, "{$subject}", "{$bodynote}", $header);
    }

    private function getLastTime($id)
    {
        if ($obj = $this->getDao('ScheduleJob')->get(["id" => $id, "status" => 1])) {
            return $obj->getLastAccessTime();
        }
    }

    private function updatLastTime($id, $current_time)
    {
        if ($obj = $this->getDao('ScheduleJob')->get(["id" => $id, "status" => 1])) {
            $obj->setLastAccessTime($current_time);
            return $this->getDao('ScheduleJob')->update($obj);
        }
    }

    public function getWmsUrl()
    {
        return $this->wms_url;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRetailerName()
    {
        return $this->retailer_name;
    }

}
