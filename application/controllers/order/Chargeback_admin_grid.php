<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chargeback_admin_grid extends gridbase
{
    function __construct()
    {
        global $_dbprefix;
        parent::__construct("List of chargebacks<br><a href='javascript:DG_Do(\"add;\")'>Add</a>", "chargeback");

        $this->var["override_search"] = true;
        $this->var["prefix_table_to_search_field"] = "t";
    }

    function set_where ($where)
    {
        $this->var["where"] = $where;
        $_SESSION["where"] = $where;
    }

    function setup_columns()
    {
        extract($this->var);

        $querystring = "";
        for ($i = 0; $i <= 6; $i++)
        {
            switch ($i)
            {
                case 0: $x = 6; break;
                case 1: $x = 8; break;
                case 2: $x = 9; break;
                case 3: $x = 39; break;
                case 4: $x = 51; break;
                case 5: $x = 52; break;
                case 6: $x = 1; break;
            }
            $t = $this->import_param("tfa_$x");

            if (is_array($t))
            {
                foreach ($t as $k=>$v)
                {
                    $querystring .= "&tfa_{$x}[]={$v}";
                }
            }
            else
                $querystring .= "&tfa_$x={$t}";
        }

        $this->add_child("id", "/order/chargeback_admin_1?$querystring");

        $objGrid->tabla("chargeback", "t");
        $objGrid->keyfield("id");
        $objGrid->searchby("so_no");
        $objGrid->orderby("chargeback_status_id, id", "asc, desc");

        $objGrid->buttons(false,true,false,false,-1,"");

        $link = array(
            "['so_no'] == ''" => "
            <b>CB#['id']</b><br>
            <a target='_blank' href='/cs/quick_search/'>Order&nbsp;Quick&nbsp;Search</a>
            ",

            "['so_no'] != '' && ['chargeback_status_id'] > 2" => "
            <b>CB#['id']</b><br>
            <a target='_blank' href='/cs/quick_search/view/['so_no']'>View&nbsp;SO#['so_no']</a>
            ",

            "['so_no'] != ''" => "
            <b>CB#['id']</b><br>
            <a target='_blank' href='/cs/quick_search/view/['so_no']'>View&nbsp;SO#['so_no']</a>
            <table>
                <thead>
                    <th>Actions</th>
                    <th>Template</th>
                </thead>
                <tr>
                    <td>
                        <a href='javascript:DG_Do(\"PODemail;['id'];['so_no']\")'>POD&nbsp;email</a><br>
                        <a href='javascript:DG_Do(\"MarkRefund;['id'];['so_no']\")'>Mark&nbsp;Refund</a><br>
                        <a href='javascript:DG_Do(\"StopPack;['id'];['so_no']\")'>Stop&nbsp;pack</a><br>
                    </td>
                    <td>
                        <a target='_blank' href='/order/chargeback_admin/email_template/chargeback_refunded/['so_no']'>Refunded</a><br>
                        <a target='_blank' href='/order/chargeback_admin/email_template/chargeback_shipped/['so_no']'>Shipped</a><br>
                        <a target='_blank' href='/order/chargeback_admin/email_template/chargeback_notshipped/['so_no']'>Not Ship</a><br>
                    </td>
                </tr>
            </table>
            "
        );

        // $objGrid->FormatColumn("platform_id","Platform",         "0", "50", 1, "1", "left", "text");
        // $objGrid->FormatColumn("Competitor","Competitor",            "0", "50", 1, "5", "right", "text");
        // $objGrid->FormatColumn("currency_id","Currency",             "0", "50", 0, "1", "right", "text");
        // $objGrid->FormatColumn("CompetitorPrice","Theirs",           "0", "50", 0, "1", "left", "text");
        // $objGrid->FormatColumn("OurPrice","Ours",                    "0", "50", 0, "1", "left", "text");
        // $objGrid->FormatColumn("Difference","Difference",            "0", "50", 0, "1", "left", "text");

        $objGrid->FormatColumn("id","ID",                           "0", "50", 1, "1", "center", "text");
        $objGrid->FormatColumn("action","CB#",                      "25", "0", 4, "1", "left", $link);
        $objGrid->FormatColumn("so_no","SO#",                       "0", "50", 0, "1", "left", "text");

        $objGrid->FormatColumn("currency_id","Currency",            "0", "50", 4, "1", "left", "text");
        $objGrid->FormatColumn("amount","Order Amount",             "0", "50", 4, "1", "left", "text");

        $objGrid->FormatColumn("chargeback_status_id","Status",     "0", "50", 0, "1", "left", "selected:select * from lookup_chargeback_status");

        $objGrid->FormatColumn("chargeback_reason_id","Reason",     "0", "50", 0, "1", "left", "selected:select * from lookup_chargeback_reason");
        $objGrid->FormatColumn("chargeback_reason","Reason",        "0", "50", 0, "30", "left", "text");

        $objGrid->FormatColumn("chargeback_remark_id","Remarks",    "0", "50", 0, "20", "left", "selected:select * from lookup_chargeback_remark");
        $objGrid->FormatColumn("chargeback_remark","Order Notes",   "0", "50", 0, "30", "left", "text");
        $objGrid->FormatColumn("create_on","Create Date",           "0", "50", 1, "1", "left", "text");
        $objGrid->FormatColumn("psp_gateway","PSP Gateway",         "0", "50", 4, "1", "center", "text");

        { # file upload code
            $previewpath = "documents/";
            $uploadpath = "./uploaded_$previewpath";

            $this->var["uploadpath"] = $uploadpath;

            mkdir($uploadpath);

            $fileoptions = array
            (
                "['document'] != ''" =>
                "<a href='/uploaded_".$previewpath."['document']' target='_blank'>View</a>",
            );

            $objGrid->setImageSize("document",16,16);
            $objGrid->uploadDirectory = "$uploadpath";
            $objGrid->validImgExtensions = array();#array("pdf", "doc", "docx", "gif", "jpg", "jpeg", "png", "xls"); /* Allowed img extensions to upload */

            $objGrid->FormatColumn("filename","POD",                    "0", "0", 4, "20", "left", $fileoptions);
            $objGrid->FormatColumn("document","Attach files here",      "25", "0", 2, "30", "left", "image:$uploadpath%s");
        }

        // $objGrid->chField("id","E-");
        $objGrid->chField("so_no","E-");
        $objGrid->chField("chargeback_reason_id","E-");
        $objGrid->chField("chargeback_status_id","E-");
        $objGrid->chField("chargeback_reason","E-");
        $objGrid->chField("chargeback_remark_id","E-");
        $objGrid->chField("chargeback_remark","E-");
        $objGrid->chField("action","X-E-");
        $objGrid->chField("document","E+RUM");

        $objGrid->chField("filename","X-");
        $objGrid->chField("currency_id","E-");
        $objGrid->chField("amount","E-");

        $objGrid->chField("create_on","E-");
        $objGrid->chField("psp_gateway","E-");

        // russell doesn't want this behavior where closed chargebacks cannot be edited
        // $objGrid->conditionEdit = "['chargeback_status_id'] <= 2";
        $objGrid->addCellStyle('purchaseorder_statusid',"['purchaseorder_statusid'] == 100", "redcell");
    }


    function ajax_handler()
    {
        extract ($this->var);

        $userid = $_SESSION['user']['id'];

        $param = explode(";", $objGrid->getAjaxID());
        $date = date('Y-m-d H:i:s');
        switch ($param[0])
        {
            case "add":
                $strSQL = sprintf("INSERT INTO $table (`chargeback_reason`,`chargeback_status_id`, `create_on`, `create_by`, `modify_on`, `modify_by`) values ('Please enter a SO# and reason',1, '$date', '{$_SESSION["user"]["id"]}', '$date', '{$_SESSION["user"]["id"]}')");
                // echo $strSQL; die();
                $arrData = $objGrid->SQL_query($strSQL);
                break;
            case "StopPack":
                echo "<script>alert('Emailed Logistics to stop packing');</script>";
                $this->send_stoppack($userid, "logistics@eservicesgroup.net", $param[2]);

                $strSQL = sprintf("update $table set `chargeback_status_id` = 2 where id = '{$param[1]}', `modify_on` = '$date', `modify_by`= '{$_SESSION["user"]["id"]}'");
                $arrData = $objGrid->SQL_query($strSQL);

                break;
            case "PODemail":
                echo "<script>alert('Emailed Logistics for POD');</script>";
                $this->send_podemail($userid, "logistics@eservicesgroup.net", $param[2]);

                $strSQL = sprintf("update $table set `chargeback_status_id` = 2 where id = '{$param[1]}', `modify_on` = '$date', `modify_by`= '{$_SESSION["user"]["id"]}'");
                $arrData = $objGrid->SQL_query($strSQL);

                break;
            case "MarkRefund":
                echo "<script>alert('Refund emailed to finance');</script>";
                $this->send_markrefund($userid, "refund-finance@valuebasket.com", $param[2]);

                $strSQL = sprintf("update $table set `chargeback_status_id` = 2 where id = '{$param[1]}', `modify_on` = '$date', `modify_by`= '{$_SESSION["user"]["id"]}'");
                $arrData = $objGrid->SQL_query($strSQL);

                break;
        }

        switch ($param[0])
        {
            case "StopPack":
            case "PODemail":
            case "MarkRefund":

                $list = $objGrid->getCheckedBoxes();
                foreach($list as $l)
                    echo ("<script>alert('$l');</script>");

                $data = $objGrid->getEditedData();

                $query = <<<SQL
insert into chargeback_audit set remarks = "{$param[0]} email sent", chargeback_id={$param[1]}, modify_by = "{$_SESSION["user"]["id"]}"
SQL;
                // echo "<script>alert('$query');</script>";

                $objGrid->SQL_query($query);

                $data["id"] = $param[1];
                $this->set_note("{$param[0]} email sent", $data);
                // echo "<script>alert('Email sent');</script>";
                break;
        }

        switch ($objGrid->getAjaxID())
        {
            case DG_IsDelete: // case 3:    // Delete Rowa / Borrar Registro
                break;

            case 6:     // Add/Edit - Adicionar / Editar
                #es: Como el formulario esta trabajando con 'POST' obtenemos el valor del campo
                #en: As form method is 'POST' we obtain and modify data for field
                #es: Todos los campos enviados por phpMyDataGrid tienen el prefijo dfFld
                #en: All fields sent by phpMyDataGrid has the prefix dgFld
                #$fldData = $_POST['dgFldafiliation_date'];
                #var_dump($_POST); die();

                $filename = $_POST["dgFld" . "document"];
                $fqdn = $filename;
                $newname = date("c");
                $newname = gmdate('Y-m-d\TH:i:s\Z');
                $newname = str_replace("-", "", $newname);
                $newname = str_replace(":", "", $newname);
                $newname = str_replace("T", "_", $newname);
                $newname = str_replace("+", "_", $newname);

                $ext = pathinfo($fqdn, PATHINFO_EXTENSION);
                $newname = $_POST["dgrtd1"] . "-$participant_id-$newname.$ext";

                // foreach ($_POST as $k=>$v)
                //  echo "<script>alert('$k is $v');</script>";

                if (file_exists($uploadpath.$fqdn))
                {
                    if (!copy($uploadpath.$fqdn, $uploadpath.$newname))
                    // if (file_exists($uploadpath.$newname))
                        echo "<script>alert('file cannot be stored [$uploadpath.$newname]')</script>";

                    $_POST["dgFld" . "document"] = $newname;
                    $_POST["dgFld" . "name"] = $fqdn;

                    // grid has a bug for image upload, ID contains nothing, while fieldname contains ID......
                    $data = $objGrid->getEditedData();
                    $data["id"] = $data["fieldname"];

                    // get the SO#
                    $query = "select so_no from chargeback where id = {$data["id"]}";
                    // echo "<script>alert('$so_no - $uploadpath.$newname - {$data["id"]} -- {$query}');</script>";
                    $m = $objGrid->SQL_query($query);
                    $so_no = $m[0]["so_no"];

                    $msg = "POD uploaded for SO#{$so_no}";
                    $query = <<<SQL
insert into chargeback_audit set remarks = "$msg", chargeback_id = {$data["id"]}, modify_by     = "{$_SESSION["user"]["id"]}"
SQL;

                    // echo "<script>alert('{$query}');</script>";
                    $objGrid->SQL_query($query);

                    // send email notification to compliance

                    $this->set_note($msg, $data);
                    $this->send_poduploaded("", "compliance@eservicesgroup.net", $so_no);
                }
                else
                    echo "<script>alert('Cannot find $fqdn as $newname');</script>";

                // echo "<script>alert('DONE!');</script>";
                #dgFlddocument
                break;

            case 4: // updated
                $data = $objGrid->getEditedData();

                $query = "insert into chargeback_audit set remarks = '{$data["fieldname"]} changed', {$data["fieldname"]} = '{$data["data"]}', chargeback_id={$data["id"]}, modify_by = '{$_SESSION["user"]["id"]}'";
                $objGrid->SQL_query($query);

                switch ($data["fieldname"])
                {

                    case "remarks":
                        $sql = "update table set status = 0";
                        $objGrid->SQL_query($sql);

                        echo "<script>setTimeout(function(){DG_Do('','&e_id=$gridid')},500);</script>";
                        die();  // prevent an error from showing

                    case "chargeback_remark_id":

                        $query = "select name from lookup_chargeback_remark where id = {$data["data"]}";
                        $m = $objGrid->SQL_query($query);
                        $message = $m[0]["name"];

                        $this->set_note("Chargeback Remark: $message", $data);
                        break;

                    case "chargeback_reason_id":

                        $query = "select name from lookup_chargeback_reason where id = {$data["data"]}";
                        $m = $objGrid->SQL_query($query);
                        $message = $m[0]["name"];

                        $this->set_note("CB Received, reason: $message", $data);
                        // 379032
                        // 265897
                        break;

                    case "chargeback_remark":
                        $this->set_note("Chargeback Remark: {$data["data"]}", $data);
                        break;

                    case "chargeback_reason":
                        $this->set_note("CB Received, reason: {$data["data"]}", $data);
                        // 379032
                        // 265897
                        break;

                    case "so_no":
                        echo "<script>setTimeout(function(){DG_Do('','&e_id=$gridid')},500);</script>"; // refresh our current row
                }

                break;
        }
    }

    public function record_click($message, $so_no)
    {
        extract ($this->var);

        $query = "select id from chargeback where so_no = '{$so_no}'";
        $m = $objGrid->SQL_query($query);
        $data["id"] = $m[0]["id"];

        $query = "insert into chargeback_audit set remarks = '$message', chargeback_id={$data["id"]}, modify_by = '{$_SESSION["user"]["id"]}'";
        $m = $objGrid->SQL_query($query);
    }

    private function set_note($message, $data = null)
    {
        extract ($this->var);
        include_once APPPATH."libraries/service/order_notes_service.php";

        if ($data == null) $data = $objGrid->getEditedData();

        $query = "select so_no from $table where id={$data["id"]}";
        $result = $objGrid->SQL_query($query);

        if (isset($result[0]["so_no"]))
        {
            $so_no = $result[0]["so_no"];
            $order_notes_service = new Order_notes_service();
            $note_obj = $order_notes_service->get();
            $note_obj->set_so_no($so_no);
            $note_obj->set_type("O");
            $note_obj->set_note($message);

            if ($order_notes_service->insert($note_obj) === FALSE)
            {
                echo "<script>alert('Cannot notes to SO#{$so_no}. (Does SO# exists?)');</script>";
            }
        }
    }

    function execute_custom_sql()
    {
        extract($this->var);

        $query =
        "
            select
                t.*,
                so.currency_id,
                so.amount,
                IFNULL(sops.payment_gateway_id, 'na') AS psp_gateway
            from $table t
            left join so on so.so_no = t.so_no
            left join so_payment_status sops on sops.so_no = t.so_no
        ";

        if ($where != "") $where = "1 $where";
        $objGrid->where($where);
        // var_dump($query . $where);
        $objGrid->sqlstatement($query);
    }

    function send_markrefund($from, $to, $so_no)
    {
        $subject = "[URGENT] VB $so_no mark as refunded";
$message = <<<template

Dear Finance Team,

We have received chargeback for order $so_no, please mark as refunded.
Thanks.

$from
Compliance Department
template;
        $this->sendmail($to, $subject, $message);
    }

    function send_podemail($from, $to, $so_no)
    {
        $subject = "[URGENT] VB $so_no POD Needed";
$message = <<<template
Dear Logistics Team,

We have received chargeback for order $so_no, please upload the POD to http://admincentre.valuebasket.com/order/chargeback_admin
Thanks.

$from
Compliance Department
template;
        $this->sendmail($to, $subject, $message);
    }

    function send_stoppack($from, $to, $so_no)
    {
        $subject = "[URGENT] VB $so_no stop pack";
$message = <<<template
Dear Logistics Team,

We have received chargeback for order $so_no, please stop the shipment to be sent out and let us know when it is back to stock.
Thanks.

$from
Compliance Department
template;

        $this->sendmail($to, $subject, $message);
    }

    function send_poduploaded($from, $to, $so_no)
    {
        $subject = "POD uploaded for VB $so_no";
$message = <<<template
Hi,

A POD for order $so_no has been uploaded.
Thanks.

Sent by system
template;

        $this->sendmail($to, $subject, $message);
    }

    function sendmail($to, $subject, $message)
    {
        // {$_SESSION["user"]["email"]}
        mail("$to, {$_SESSION["user"]["email"]}", $subject, $message);
        // mail("tslau@eservicesgroup.net", $subject, $message);
        error_log($subject);
    }

}