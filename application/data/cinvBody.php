<?php
$body = "<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td colspan='2' align='left'>
            <b><h1 style='font-size: 18px;'>".$shipper_name."</h1></b>
        </td>
    </tr>
    <tr>
        <td style='width:100px;'>
            <b>Shipper  :</b>
        </td>
        <td>".$shipper_name."</td>
    </tr>
    <tr>
        <td style='width:100px;padding-top:5px;' valign='top'>
            <b>Address  :</b><br>
        </td>
        <td style='padding-top:5px;'>".
           $saddr_1."<br/>".
           $saddr_2."<br/>".
           $saddr_3."<br/>".
           $saddr_4."<br/>".
           $saddr_5."<br/>
        </td>
    </tr>
    <tr>
        <td colspan='2' style='padding-top:5px;font-size: 18px;text-decoration:underline;' align='center'>
            CUSTOM&nbsp;&nbsp;INVOICE
        </td>
    </tr>
    <tr>
        <td valign='top' style='padding-top: 20px;'>
            <b>INVOICE TO   :</b>
        </td>
        <td valign='top' style='padding-top: 20px;'>"
            .$deliver_name.
            "<div style='float: right;padding-right: 80px;'>
            DATE :". $date_of_invoice."
            </div>
        </td>
    </tr>
    <tr>
        <td valign='top' style='padding-top: 5px;'>
            <b>ORDER NUMBER     :</b>
        </td>
        <td valign='top' style='padding-top: 5px;'>"
            .$client_id . '-' . $order_number.
        "</td>
    </tr>
    <tr>
        <td valign='top' style='padding-top: 5px;'>
            <b>DELIVERY     :</b>
        </td>
        <td valign='top' style='padding-top: 5px;'>".
           $daddr_1."<br>".
           $daddr_2."<br>".
           $daddr_3."<br>".
           $daddr_4."<br>".
           $daddr_5."<br>
        </td>
    </tr>
    <tr>
        <td valign='top' style='padding-top: 5px;'>
            <b>CONTACT  :</b>
        </td>
        <td valign='top' style='padding-top: 5px;'>"
           .$shipper_phone.
        "</td>
    </tr>
    <tr>
        <td colspan='2' style='padding-top: 10px;'>
            <table border='1' width='100%' cellspacing='0' cellpadding='5'>
                <tr align='center'>
                    <td width='521' valign'top' height='10px' bgcolor='#CCCCCC'><b>Description</b></td>
                    <td width='117' valign'top' height='10px' bgcolor='#CCCCCC'><b>Qty</b></td>
                    <td width='117' valign'top' height='10px' bgcolor='#CCCCCC'><b>HS Code</b></td>
                    <td width='117' valign'top' height='10px' bgcolor='#CCCCCC'><b>Unit Price<br>(".$currency.")</b></td>
                    <td width='117' valign'top' height='10px' bgcolor='#CCCCCC'><b>Total Price<br>(".$currency.")</b></td>
                </tr>"
                .$item_info.
                "<tr>
                    <td colspan='3'></td>
                    <td valign='top' align='center'><b>Total</b></td>
                    <td valign='top' align='right'>".$total_cost."</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
<br/>
<hr style='height:1px;margin:0;padding:0;'>
<hr style='height:1px;margin:0;padding:0;'>
<hr style='height:1px;margin:0;padding:0;'>
<br>
<div>
    I declare that the above information is true and correct and to the best of our knowledge. The product(s) covered by
this document are not subject to any export or import prohibitions & restrictions.
</div>
<div style=''>
    <img src='".base_url()."images/esg_sign_cinv.png' style='border-bottom:1px solid #000000;'>
</div>
<div style='margin-left: 50px;'>Thank you for your business</div>
";
?>