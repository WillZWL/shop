<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<title>ChatandVision - <?=$success?"Paiement Traité":"Echec du paiement"?></title>
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
				<td><h1>Paiement Traité<a name="top" id="top"></a></h1></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
			  	<td>
					<p>
					Félicitations! Vous avez passez commande avec succès pour
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
					.<br />
					Votre numéro de commande est <?=$so->get_so_no()?>.<br />

					<?php
						if ($so_ps && $so_ps->get_payment_status() == "S")
						{
					?>
					Nous avons envoyé une confirmation sur votre adresse email d'inscription a <?=$_SESSION["client"]["email"]?>. <br /><br />
					<?php
						}
						else
						{
					?>
					Nous avons envoyé un accusé de réception de votre commande à votre adresse email <?=$_SESSION["client"]["email"]?>. Nous vous informerons de nouveau lorsque le paiement aura été validé. <br /><br />
					<?php
						}
					?>

					Au cas où vous avez des demandes, n'hésitez surtout pas à visiter notre F.A.Q. ou à nous contacter par email à <a href="mailto:support-fr@chatandvision.com">support-fr@chatandvision.com</a> pour plus de détails.
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
            <td><h1>Echec du paiement <a name="top" id="top"></a></h1></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
		  	<td>
				<p>
				Il y a eu une erreur lors du traitement de votre paiement.  <br />
				Nous vous invitons à verifier vos coordonnées de facturation et essayer de nouveau. Parallèlement, pour plus de détails, vous pouvez contacter nos représentants de service clientèle à <a href="mailto:support@chatandvision.com">support@chatandvision.com</a><?=$so_no?" en indicant votre numéro de commande: {$so_no}. ":"."?>
				<br /><br />
				Nous nous excusons pour tout désagrément occasionné et espérons pouvoir vous assister prochainement.<br />
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
