<?php

class Cron_duplicate_purchase extends MY_Controller
{
    private $app_id = "MKT0046";

    function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/sku_mapping_feed_model');
        $this->load->library('service/so_service');
    }

    function index()
    {
        $from = "website@valuebasket.com";
        $maillist = "tslau@eservicesgroup.com";
        $maillist = "compliance@simplyelectronics.net";
        $subject = "[VB] Duplicate Purchase Notification";


        $iCounter = 0;
        $arrBGColor = array('#FF9966', '#FFCC66');

        $arrAmountFilter = array('gbp' => 100, 'eur' => 110, 'usd' => 150, 'aud' => 100);
        $arrFirstRecord = array('', '');

        $result = $this->so_service->get_dao()->get_duplicate_purchase();

        ob_start(); //Turn on output buffering

        $arrRecordDetail = array();

        echo '<table border=1><tr bgcolor="#aaaaaa"><td>Client ID</td><td>Product Name</td><td>Order Number</td><td>Currency</td><td>Product Unit Price</td></tr>' . "\r\n";
        foreach ($result as $resultRow) {
            if (array_key_exists(strtolower($resultRow['currency_id']), $arrAmountFilter)) {
                if ($resultRow['unit_price'] < $arrAmountFilter[strtolower($resultRow['currency_id'])]) {
                    continue;
                }
            }

            if (($resultRow['client_id'] == $arrFirstRecord[0]) && ($resultRow['prod_sku'] == $arrFirstRecord[1])) {
                $iCurrentRecord = count($arrRecordDetail);
                $arrRecordDetail[$iCurrentRecord][0] = $resultRow['so_no'];
                $arrRecordDetail[$iCurrentRecord][1] = $resultRow['currency_id'];
                $arrRecordDetail[$iCurrentRecord][2] = $resultRow['unit_price'];
            } else {
                if ($arrFirstRecord[0] != '') {
                    echo $this->generateOneSetOrder($arrFirstRecord, $arrRecordDetail, $arrBGColor[($iCounter++) % 2]);
                }

                $arrFirstRecord[0] = $resultRow['client_id'];
                $arrFirstRecord[1] = $resultRow['prod_sku'];

                $arrRecordDetail = array();
                $arrRecordDetail[0][0] = $resultRow['so_no'];
                $arrRecordDetail[0][1] = $resultRow['currency_id'];
                $arrRecordDetail[0][2] = $resultRow['unit_price'];
            }
        }

        if (count($arrRecordDetail) > 1) {
            echo $this->generateOneSetOrder($arrFirstRecord, $arrRecordDetail, $arrBGColor[($iCounter++) % 2]);
        }

        echo '</table>';

        //copy current buffer contents into $content variable and delete current output buffer
        $content = ob_get_clean();
        echo $content;

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html\r\n";
        $headers .= "From: " . $from;

        mail($maillist, $subject, $content, $headers);
    }

    function generateOneSetOrder($arrFirstRecord, $arrRecordDetail, $bgColor)
    {
        $rowSpan = count($arrRecordDetail);

        $result = '';
        $result .= '<tr bgcolor="' . $bgColor . '"><td rowspan=' . $rowSpan . '>' . $arrFirstRecord[0] . '</td><td rowspan=' . $rowSpan . '>' . $arrFirstRecord[1] . '</td>';
        $result .= '<td>' . $arrRecordDetail[0][0] . '</td><td>' . $arrRecordDetail[0][1] . '</td><td>' . $arrRecordDetail[0][2] . '</td></tr>' . "\r\n";

        for ($i = 1; $i < $rowSpan; $i++) {
            $result .= '<tr bgcolor="' . $bgColor . '"><td>' . $arrRecordDetail[$i][0] . '</td><td>' . $arrRecordDetail[$i][1] . '</td><td>' . $arrRecordDetail[$i][2] . '</td></tr>' . "\r\n";
        }
        $result .= "\r\n";

        return $result;
    }

    function _get_app_id()
    {
        return "CRN0009";
        return $app_id;
    }
}
