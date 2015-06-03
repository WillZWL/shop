<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<title>ChatandVision - Pagamento <?=$success?"Processado":"Não Concluído"?></title>
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
				<td><h1>Pagamento Processado<a name="top" id="top"></a></h1></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
			  	<td>
					<p>
					Parabéns! Fez uma encomenda de XX com sucesso.
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

					O seu número de encomenda é <?=$so->get_so_no()?>.<br />

					<?php
						if ($so_ps && $so_ps->get_payment_status() == "S")
						{
					?>
					Enviámos uma confirmação desta encomenda para o seu e-mail registado <?=$_SESSION["client"]["email"]?>. <br /><br />
					<?php
						}
						else
						{
					?>
					Enviámos uma cópia de conhecimento desta encomenda para o seu e-mail registado em  <?=$_SESSION["client"]["email"]?>. Iremos informá-lo novamente logo que o pagamento seja efectuado. <br /><br />
					<?php
						}
					?>

					Na eventualidade de surgir qualquer questão, por favor, não hesite em visitar a nossa página de <a href="<?=base_url()?>faq">F.A.Q (Perguntas Frequentes)</a> ou em enviar-nos um e-mail para <a href="mailto:support-pt@chatandvision.com">support-pt@chatandvision.com</a> para obter mais assistência.
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
            <td><h1>Pagamento Não Concluído<a name="top" id="top"></a></h1></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
		  	<td>
				<p>
				Ocorreu um erro no processamento do seu pagamento.<br />
				Pode desejar rever os detalhes de facturação e tentar novamente. Caso contrário, contacte os nossos agentes do Serviço ao Cliente em <a href="mailto:support-pt@chatandvision.com">support-pt@chatandvision.com</a><?=$so_no?" indicando este número de referência: {$so_no} para obter mais assistência. ":"."?>
				<br /><br />
				Pedimos desculpa por qualquer inconveniente causado e aguardamos notícias suas brevemente.<br />
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
