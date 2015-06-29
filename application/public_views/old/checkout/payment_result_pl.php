<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<title>ChatandVision - Płatności<?=$success?"Processed":"Unsuccessful"?></title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<?php include VIEWPATH . "googleanalytics.php"; //google analytic tracking?>
</head>

<body>
<div id="container">

<?// include VIEWPATH.'header_skype_' . get_lang_id() . '.php';?>


<!--  CONTENT -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td><img src="/images/chat_and_vision_logo.png" height="40" border="0" /></td>
    </tr>
    <tr  height="1" bgcolor="#FF9900"><td style="line-height:1px;padding:0px;" background="<?=base_url()?>images/line_blue.png" width="100%"></td></tr>
  <tr>
    <td>
        <table align="center" width="1000" border="0" cellspacing="5" cellpadding="5">
          <?php /*
          <tr>
                    <td><font color="#999999"><b><a href="/">Home</a> > Shipping Information</b></font></td>
                </tr>
                */ ?>

            <?php
                if ($success)
                {
            ?>
            <tr>
                <td><h1>Płatność zrealizowana<a name="top" id="top"></a></h1></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <p>
                    Gratulujemy! Złożenie zamówienia na
                    <?php
                        if ($so_items)
                        {
                            $ar_prod_name = array();
                            foreach ($so_items as $so_item)
                            {
                                $ar_prod_name[] = $so_item->get_name();
                            }
                        }
                        echo @implode(",", $ar_prod_name);
                    ?>
                     zakończone powodzeniem.<br />
                    Numer zamówienia to <?=$so->get_so_no()?>.<br />

                    <?php
                        if ($so_ps && $so_ps->get_payment_status() == "S")
                        {
                    ?>
                    Wysłaliśmy potwierdzenie zamówienia na podany przez Państwa przy rejestracji adres e-mail: <?=$_SESSION["client"]["email"]?>. <br /><br />
                    <?php
                        }
                        else
                        {
                    ?>
                    Wysłaliśmy potwierdzenie złożenia zamówienia na podany przez Państwa przy rejestracji adres e-mail: <?=$_SESSION["client"]["email"]?>. Skontaktujemy się z Państwem ponownie po weryfikacji płatności. <br /><br />
                    <?php
                        }
                    ?>

                    W razie pytań i wątpliwości, prosimy o odwiedzenie działu  <a href="<?=base_url()?>faq">Często Zadawanych Pytań (FAQ) </a>lub e-mail na adres <a href="mailto:support-pl@chatandvision.com"> support-pl@chatandvision.com </a>.
                    </p>
                </td>
            <td>&nbsp;</td>
        </tr>
            <?php
                }
                else
                {
            ?>
          <tr>
            <td><h1>Płatność odrzucona<a name="top" id="top"></a></h1></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>
                <p>
                W trakcie weryfikacji dokonanej przez Państwa płatności wystąpił błąd. <br />
                Prosimy o zapoznanie się ze szczegółami transakcji i ponowienie próby. W przypadku dalszych problemów, proszę skontaktować się z pracownikami naszego Biura Obsługi Klienta pod adresem ,<a href="mailto:support-pl@chatandvision.com">support-pl@chatandvision.com</a><?=$so_no?", podając następujący numer referencyjny :{$so_no}.":"."?>
                <br /><br />
                Przepraszamy za wszelkie niedogodności i mamy nadzieję, że wkrótce się Państwo z nami skontaktują.<br />
                </p>
            </td>
            <td>&nbsp;</td>
        </tr>
            <?php
                }
            ?>
            <?php /*
          <tr>
            <td><a href="#top">Back To Top</a></td>
            <td></td>
          </tr>
          */ ?>
          <tr>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td></td>
          </tr>
        </table>

    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>


<!-- END CONTENT -->



<?// include VIEWPATH.'footer_' . get_lang_id() . '.php';?>

</div>
<?php
if ($success)
{
?>
<!-- Google Code for vb Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1017948130;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "G-r2CK7o9QIQ4s-y5QM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1017948130/?label=G-r2CK7o9QIQ4s-y5QM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<?php
}
?>
</body>
</html>
