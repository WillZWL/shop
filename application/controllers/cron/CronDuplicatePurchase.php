<?php

class CronDuplicatePurchase extends MY_Controller
{
    private $appId = "CRN0035";

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $from = "do_not_reply@eservicesgroup.com";
        $maillist = "will.zhang@eservicesgroup.com";
        $maillist = "compliance@eservicesgroup.com";
        $subject = "[PT] Duplicate Purchase Notification";
        $arrBGColor = array('#FF9966', '#FFCC66');
        $result = $this->sc['So']->getDao('So')->getDuplicatePurchase();
        // echo $this->sc['So']->getDao('So')->db->last_query();
        $content = '<table border=1><tr bgcolor="#aaaaaa"><td>Client ID</td><td>Order Number</td><td>Product SKU</td></tr>' . "\r\n";
        $iCounter = 1;
        foreach ($result as $resultRow) {
            $content .= $this->generateOneSetOrder($resultRow, $arrBGColor[($iCounter++) % 2]);
            $iCounter++;
        }

        $content .= '</table>';
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html\r\n";
        $headers .= "From: " . $from;
        mail($maillist, $subject, $content, $headers);
    }

    function generateOneSetOrder($row, $bgColor)
    {
        $result = '';
        $result .= '<tr bgcolor="' . $bgColor . '">';
        $result .= "<td>".$row['client_id']."</td><td>".$row['so_no']."</td><td>".$row['item_sku']."</td>";
        $result .= "</tr>\r\n";
        return $result;
    }

    function getAppId()
    {
        return $this->appId;
    }
}
