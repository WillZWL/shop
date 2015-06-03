<?php
$tab = $this->input->get('tab')?$this->input->get('tab'):"desc";
$tab_no = $tab_number[$tab];

if($prod_price > 0)
{
	$prod_rrp = rrpfunc(number_format($prod_price / 0.80,2,".",""));
	$prod_saved = $prod_rrp-$prod_price;
}
else
{
	$prod_rrp = $prod_saved = 0;
}

function rrpfunc($amountin){
	$remainder = fmod($amountin,5);
	$add_too = 5-$remainder;
	$amountrtn =  number_format($amountin-(-$add_too)-.01,2,'.','');
	return $amountrtn;
}

function get_video_title($id, $src)
{
	$url = "http://gdata.youtube.com/feeds/api/videos/$id";
	$doc = new DOMDocument;
	$doc->load($url);
	$title = $doc->getElementsByTagName("title")->item(0)->nodeValue;
	return $title;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="//cdn.optimizely.com/js/8554725.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Buy Skype Certifed Headsets, Phones, Webcams at ValueBasket - Skype Global Merchant Partner</title>
<link href="<?=base_url()?>css/style.css" rel="stylesheet" type="text/css" />
<!-- <script language="javascript" src="<?=base_url()?>/js/checkform.js"></script> -->
<!-- <script type="text/javascript" src="<?=base_url()?>js/common.js"></script> -->
<script src="<?=base_url()?>js/jquery.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/jquery.tools.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/jquery_timers.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/top_up-min.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/lytebox_cv.min.js?20110223" type="text/javascript"></script>
<script src="<?=base_url()?>js/modal.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/kandytabs.pack.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?=base_url()?>css/kandytabs.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?=base_url()?>css/kandy_main_product.css" type="text/css" media="all" />
<link rel="stylesheet" href="http://mark.reevoo.com/stylesheets/reevoomark/reevoo_reviews.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/scrollable-horizontal.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/scrollable-buttons.css" />
<link rel="stylesheet" href="<?=base_url()?>css/lytebox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?=base_url()?>css/lytebox_ext.css" type="text/css" media="screen" />
<style type="text/css">
.bundle-image .modal {
	position: absolute;
	display: none;
	left: 0px;
	top: 0px;
	border: 1px solid #000000;
	background: #FFFFFF;
}
</style>
<script type="text/javascript">
$(function(){
	$(".scrollable").scrollable();
});
$(function(){
	$(".iscrollable").scrollable();
});
</script>
<script type="text/javascript">
function updateDisplayImage(src, im, fl, type){
	if(type == 'image')
	{
		document.getElementById('main').innerHTML = "<a href='"+src+"."+im+"' class='top_up'><img border='0px' src='"+src+"_l."+im+"' width='190' height='190' border='1' /></a>";
		document.getElementById('link').innerHTML = "<font face='Arial, Helvetica, sans-serif' size='1' color ='#8e8e8e'><a href='"+src+"."+im+"' class='top_up'><img border=0px src='/images/04products_magglass.gif' width='11' height='11' />&nbsp;<strong>Click to Enlarge</strong></a></font>";
	}
	else if(type == 'flash')
	{
		document.getElementById('main').innerHTML = "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='190' height='190'><param name='movie' value='"+src+"."+fl+"' /><param name='wmode' value='transparent' /><!--[if !IE]>--><object type='application/x-shockwave-flash' data='"+src+"."+fl+"' width='190' height='190'><param name='wmode' value='transparent' /><!--<![endif]--><p><img border=0px src='"+src+"."+im+"' width='190 height='190' /></p><!--[if !IE]>--></object><!--<![endif]--></object>";
		document.getElementById('link').innerHTML = "<font face='Arial, Helvetica, sans-serif' size='1' color ='#8e8e8e'><a href='"+src+"."+fl+"' toptions='width=550, height=400, title= 360 Degree Product View, effect=switch'><img border=0px src='/images/04products_magglass.gif' width='11' height='11' />&nbsp;<strong>Click to Enlarge</strong></a></font>";
	}
}
function openTab(tab){
	tab = "desc";
	var title = "t_"+tab;
	document.getElementsByClassName("tabcur")[0].className = "tabbtn";
	document.getElementById(title).className = "tabbtn tabcur";

	var divs = document.getElementsByClassName("tabcont");
	for(i=0; i<divs.length; i++)
	{
		divs[i].style.display = "none";
	}
	document.getElementById(tab).style.display = "block";
}
function addToCart(sku){
	var qty = document.getElementById('quantity').value;

	if(qty > 0 && qty != "")
	{
		document.location.href = "<?=base_url()?>"+"cart/add_item_qty/"+sku+"/"+qty;
	}
}
</script>
<script src="http://mark.reevoo.com/reevoomark/CHA.js"
type="text/javascript" charset="utf-8"></script>

</head>
<body link="#60D4FF">
<?php
	include 'reevoo_mark.php';
	$reevoo_mark = new ReevooMark("reevoo_cache", "http://mark.reevoo.com/widgets/offers", "CHA", "$sku");
?>
<input type="hidden" name="sku" id="sku" value="<?=$sku?>">
<table width="1024" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td>
		<?php include VIEWPATH . 'header.php';?>
	</td>
</tr>
<tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="5" colspan="3"></td>
	</tr>
	<tr>
		<td colspan="3">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="220">
			<table align="center" width="190" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" width="190" height="190">
				<span id="main">
			<?php
				if($prod_obj->get_flash() != "")
				{
			?>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="190" height="190">
						<param name="movie" value="<?=base_url()?>images/product/<?=$prod_obj->get_sku()?>.<?=$prod_obj->get_flash()?>" />
						<param name="wmode" value="transparent" />
						<!--[if !IE]>-->
						<object type="application/x-shockwave-flash" data="<?=base_url()?>images/product/<?=$prod_obj->get_sku()?>.<?=$prod_obj->get_flash()?>" width="190" height="190">
						<!--<![endif]-->
							<p><img border=0px src="<?=base_url()?>images/product/<?=$prod_obj->get_sku()?>.<?=$prod_obj->get_image()?>" /></p>
						<!--[if !IE]>-->
							<param name="wmode" value="transparent" />
						</object>
						<!--<![endif]-->
					</object>
			<?php
				}
				else
				{
			?>
					<a href="<?=base_url().get_image_file($prod_obj->get_image(), '', $sku);?>" class="top_up">
						<img  border="3px" src="<?=base_url()?><?=get_image_file($prod_obj->get_image(), '', $sku);?>" width="190" height="190" border="1" />
					</a>
			<?php
				}
			?>
				</span>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
			<tr>
				<td align="center">
				<table width="150" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center">
					<span id="link">
				<?php
					if($prod_obj->get_flash() != "")
					{
				?>
						<font face="Arial, Helvetica, sans-serif" size="1" color="#8e8e8e">
							<a href="<?=base_url()?>images/product/<?=$prod_obj->get_sku().".".$prod_obj->get_flash()?>" toptions="width = 550, height = 400, title = 360 Degree Product View, effect = switch">
								<img border=0px src="<?=base_url()?>images/04products_magglass.gif" width="11" height="11" />&nbsp;<strong>Click to Enlarge</strong>
							</a>
						</font>
				<?php
					}
					else
					{
				?>
						<font face="Arial, Helvetica, sans-serif" size="1" color="#8e8e8e">
							<a href="<?=base_url().get_image_file($prod_obj->get_image(), '', $sku);?>" class="top_up">
							<img border=0px src="<?=base_url()?>images/04products_magglass.gif" width="11" height="11" />&nbsp;<strong>Click to Enlarge</strong></a>
						</font>
				<?php
					}
				?>
					</span>
					</td>
				</tr>
				</table>
				</td>
			</tr>
		<?php
			$total_items = sizeof($prod_image);

			if($prod_obj->get_flash())
			{
				$total_items++;
			}
			if($total_items > 1)
			{
		?>

			<tr>
				<td align="center">
				<table width="240" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td align="center" >
					<a class="prev browse_left ileft"></a>
					<div class="iscrollable">
						<div class="iitems">
					<?php
						$count = 0;
						if($prod_obj->get_flash() != "")
						{
							$count++;

							if(($count)%4 == 1)
							{
								echo "<div>";

							}
					?>
							<img onclick="updateDisplayImage('<?=base_url()."images/product/".$prod_obj->get_sku()?>', '<?=$prod_obj->get_image()?>', '<?=$prod_obj->get_flash()?>', 'flash')" width="36px" height="36px" src="<?=base_url()."images/360image.gif"?>" />
					<?php
							if(($count)%4 == 0 || $count == $total_items)
							{
								echo "</div>";
							}
						}
						foreach($prod_image as $rskey=>$image_obj)
						{
							$count++;

							if(($count)%4 == 1)
							{
								echo "<div>";
							}
					?>
							<img onclick="openTab();updateDisplayImage('<?=base_url()."images/product/".$image_obj->get_sku()."_".$image_obj->get_id()?>', '<?=$image_obj->get_image()?>', '', 'image')" width="38px" height="38px" src="<?=base_url()."images/product/".$image_obj->get_sku()."_".$image_obj->get_id()."_s.".$image_obj->get_image()?>" />
					<?php
							if(($count)%4 == 0 || $count == $total_items)
							{
								echo "</div>";
							}
						}
					?>
						</div>
					</div>
					<a class="next browse_right iright"></a>
					<br clear="all" />
					</td>
					</tr>
				</table>
				</td>
			</tr>
		<?php
			}
		?>
		<tr>
			<td height="10" align="center"></td>
		</tr>
		</table>
		</td>
		<td width="8">&nbsp;</td>
		<td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td height="100%" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td valign="top"><font face="Arial, Helvetica, sans-serif" size="4"><strong><?=$prod_cont_obj->get_prod_name()?></strong></font></td>
				</tr>
				<tr>
				<?php
					$dstr = "";

					if($prod_obj->get_display_quantity())
					{
						$quantity = min($prod_obj->get_website_quantity(),$prod_obj->get_display_quantity());
					}
					else
					{
						$quantity = $prod_obj->get_website_quantity();
					}

					if($quantity)
					{
						$dstr = "<u>".$quantity."</u> in stock.";
					}
					else
					{
						$dstr = "<span style='font-size:10px;color:#666666; color:red;'>Out of stock</span>";
					}
				?>
					<td align="left"><font face="Arial, Helvetica, sans-serif" size="2"><?=$dstr?></font><br />
				<?php if(!($prod_type['VIRTUAL'] == 1 && $prod_type['TRIAL'] == 1)) {?>
					<font face="Arial, Helvetica, sans-serif" size="1" color="#8eb53a"><strong>Usually ships in <?=$working_day?> Business Days</strong></font>
				<?php } ?>
					</td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td height="10px"></td>
		</tr>
		<tr>
			<td valign="top">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td height="100%">
				<?php
					include "reevoomark.php"
				?>
						</td>
						<td rowspan="3" align="left">
					<script type="text/javascript">
var eKomiWGProt = (("https:" == document.location.protocol) ? "https://" : "http://");
document.write(unescape("%3Cscript src='" + eKomiWGProt + "connect.ekomi.de/widget/U5YCVJA5FP2MFJ4.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<br />
<br />
<div align="right">
<a href="http://www.facebook.com/pages/Chat-and-Vision/132709896785104"><img src="<?=base_url()?>images/facebook.png" /></a>&nbsp;&nbsp;<a href="http://twitter.com/valuebasket"><img src="<?=base_url()?>images/twitter.png" /></a>
</div>
</td>

					</tr>

			</td>

		</tr>
		<tr>
			<td height="10px"></td>
		</tr>
		<tr>
			<td align="left" valign="top">
			<?php
				if(!empty($main_prod_video))
				{
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<!-- <td width="194"><img src="<?=base_url()?>images/revooreview.gif" width="194" height="41" /></td> -->
					<td width="5">&nbsp;</td>
					<td width="280" align="left">
					<?php
						if(!empty($main_prod_video))
						{
							$ref_id = $main_prod_video[0]->get_ref_id();

							if($http)
							{
					?>
							<a href="http://www.youtube.com/v/<?=$ref_id?>&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = iv, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><img height="41" width="96" src="<?=base_url()?>images/viewvideo.gif" /></a>
					<?php
							}
							else
							{
					?>
							<a href="https://www.youtube.com/v/<?=$ref_id?>&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = iv, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><img height="41" width="96" src="<?=base_url()?>images/viewvideo.gif" /></a>
					<?php
							}
						}
					?>

						<?if(PLATFORMID == 'WEBGB'){?>
						<img src="/images/ukphone.png"/>
						<?}?>
						</td>
					<td width="5">&nbsp;</td>
					<!-- <td align="left" valign="bottom"><img src="<?=base_url()?>images/skypecallme.gif" width="99" height="37" /></td> -->
					<td>&nbsp;</td>
				</tr>
			</table>
			<?php
				}
				else
				{
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="5">&nbsp;</td>
					<td width="280" align="left">
					<?php
						if($http)
						{
							?>
							<a href="http://www.youtube.com/v/oXcu5dWIKuk&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = iv, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><img height="41" width="96" src="<?=base_url()?>images/AboutUsVideo.png" /></a>
							<?php
						}
						else
						{
							?>
							<a href="https://www.youtube.com/v/oXcu5dWIKuk&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = iv, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><img height="41" width="96" src="<?=base_url()?>images/AboutUsVideo.png" /></a>
							<?php
						}
							?>

						<?if(PLATFORMID == 'WEBGB'){?>
						<img src="/images/ukphone.png"/>
						<?}?>
					</td>
					<td width="5">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<?php
				}
			?>
			</td>
		</tr>
		<tr>
			<td height="12"></td>
		</tr>
		<!--
		<tr>
			<td width="100%"><table width="100%"><tr>
			<td align="left"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">




-->
			<!--
			<tr>
				<td background="<?=base_url()?>images/borders_01.gif" width="8" height="8"></td>
				<td height="8" background="<?=base_url()?>images/borders_10.gif"></td>
				<td background="<?=base_url()?>images/borders_03.gif" width="8" height="8"></td>
			</tr>
			<tr>
				<td width="8" background="<?=base_url()?>images/borders_12.gif" style="background-repeat:repeat">&nbsp;</td>
				<td>
				<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
				<tr>
					<td height="60px" align="left" width="80%">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="left">
							<font face="Arial, Helvetica, sans-serif" size="3" color="#00aff0"><strong>
							<img src="<?=base_url()?>images/pickoftheday.gif" width="18" height="17" /></strong></font>
							<font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff"><strong>&nbsp;</strong></font>
							<font face="Arial, Helvetica, sans-serif" size="3" color="#00aff0"><strong>Top Reasons Why You Should Shop with Us</strong></font>
						</td>
					</tr>
					<tr>
						<td><font face="Arial, Helvetica, sans-serif" size="2" color="#666666"><b>
						<ul style="list-style-image: url(<?=base_url()?>images/arrow_02.gif); line-height:1.5em; margin-top:2; margin-bottom:0; margin-left: -1.5em;">
						<li> Free shipping for all orders</li>
						<li> Low prices</li>
						<li> Easy and secure ordering</li>
						</ul>
						</b></font>
						</td>
					</tr>

					</table>
					</td>
				</tr>
				</table>
				</td>
				<td width="8" background="<?=base_url()?>images/borders_14.gif" style="background-repeat:repeat">&nbsp;</td>
			</tr>
			<tr>
				<td width="8" height="6"><img src="<?=base_url()?>images/borders_21.gif" width="8" height="8" /></td>
				<td background="<?=base_url()?>images/borders_22.gif"><img src="<?=base_url()?>images/borders_22.gif" width="309" height="8" /></td>
				<td width="8" height="6"><img src="<?=base_url()?>images/borders_23.gif" width="8" height="8" /></td>
			</tr>



			</table>
			</td>
			<td align="right">
			<table cellpadding="0" cellspacing="0" width="100%" height="90px">
-->
				<!--<tr height="37"><td valign="top" align="left"><img src="<?=base_url()?>images/skypecallme.gif" width="99" height="37"></td></tr>-->
				<!--
				<tr height="37" width="99"><td>&nbsp;</td></tr>
				<tr><td></td></tr>
				<tr height="23"><td valign="bottom" align="left"><a href="http://www.facebook.com/pages/Chat-and-Vision/132709896785104"><img src="<?=base_url()?>images/facebook.png" /></a>&nbsp;&nbsp;<a href="http://twitter.com/valuebasket"><img src="<?=base_url()?>images/twitter.png" /></a></td></tr>
			</table>
			</td>
			</tr>
			-->
			</table></td>
		</tr>

	</table>
	</td>

	<td><img src="<?=base_url()?>images/01index_98.gif" width="1px" height="220px" /></td>
	<td width="250" valign="top"><table width="250" border="0" cellspacing="5" cellpadding="0">
	<tr>
		<td height="5" colspan="2" align="right"></td>
	</tr>
<?php
	if($prod_type['VIRTUAL'] == 1 && $prod_type['TRIAL'] == 1)
	{
?>
	<tr>
		<td align="right" colspan="2" nowrap style="white-space:nowrap"><font face="Arial, Helvetica, sans-serif" size="5" color="red"><strong>Free !</strong></font>&nbsp;</td>
	</tr>
<?php
	}
	else
	{
?>
	<tr>
		<td>&nbsp;&nbsp;<font face="Arial, Helvetica, sans-serif" size="2" color="#999999"><b>MSRP:</b></font></td>
		<td width="80" align="right" colspan="2"><font face="Arial, Helvetica, sans-serif" size="3" color="#999999"><del><?=platform_curr_format(PLATFORMID, $prod_rrp)?></del></font>&nbsp;&nbsp;</td>
	</tr>
	<!--
	<tr>
		<td height="5px"></td>
	</tr>
	-->
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<font face="Arial, Helvetica, sans-serif" size="2" color="#FF6600"><b>You Save:</b></font></td>
		<td width="80" align="right" colspan="2"><font face="Arial, Helvetica, sans-serif" size="3" color="#FF6600"><b><?=platform_curr_format(PLATFORMID, $prod_saved)?></b></font></td>
	</tr>
	<tr>
		<td align="right"></td>
		<td align="right" colspan="2" nowrap style="white-space:nowrap">&nbsp;&nbsp;<font face="Arial, Helvetica, sans-serif" size="3" color="#FF6600"><b>Free Delivery</b></font></td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;<font face="Arial, Helvetica, sans-serif" size="4" color="#00aff0"><strong>Our Price:</strong></font></td>
		<?=$price?>
		<td align="right" colspan="2" nowrap style="white-space:nowrap"><font face="Arial, Helvetica, sans-serif" size="5" color="#00aff0"><strong><?=platform_curr_format(PLATFORMID, $prod_price)?></strong></font></td>
	</tr>
<?php
	}
?>
	<?php
		if($quantity)
		{
	?>
	<tr height="40px" valign="bottom">
		<td align="right" colspan="3">&nbsp;&nbsp;<font face="Arial, Helvetica, sans-serif" size="2"><strong>Quantity:</strong></font>&nbsp;&nbsp;
		<select name="quantity" id="quantity" >
		<?	for($i=1; $i<=$quantity; $i++)
			{
		?>
			<option value="<?=$i?>"><?=$i?></option>
		<?php
			}
		?>
		</select>
		</td>
	</tr>
	<?php
		}
	?>
	<tr>
		<td colspan="3" align="right">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
		<?php
			if(!$oos)
			{
		?>
			<tr>
				<td colspan="2" align="center" height="40px">
				<table border="0" cellpadding="0" cellspacing="0" width="226">
				<tr>
					<td onclick="addToCart('<?=$sku?>')" background="<?=base_url()?>images/addtobasket.gif" style="width:100%;cursor:pointer" height="45" width="70">
						<font face="Arial, Helvetica, sans-serif" size="3" color="#ffffff" style="padding-left:40px;padding-right:55px"><strong>Add to Basket</strong></font>
					</td>
				</tr>
				</table>
				</td>
			</tr>
		<?php
			}
		?>
		<tr valign="bottom" height="30px">
		</tr>
		</table>
		</td>
	</tr>
		</table></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td width="220" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
				<?php if(!empty($ra_list)){?>
					 <td width="220" height="26" align="center" background="<?=base_url()?>images/oftenboughtbkgrd.gif"><font face="Arial, Helvetica, sans-serif" size="3" color="#ffffff"><strong>Often bought with</strong></font></td>
				<?php }?>
				</tr>
				<tr>
					<td>
						<?php
						if(!empty($ra_list))
						{
						?>
						<table width="100%" height="25" border="0" cellspacing="0" cellpadding="0" style="border-width:1px;border-color:#60D4FF;border-style:solid">
							<?php
							foreach($ra_list as $rskey=>$ra_item)
							{
								if($rskey <= 3)
								{
									if($rskey != '0')
									{
							?>
							<tr>
								<td height="20" align="center">
									<table width="90%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td height="2" bgcolor="#60D4FF"></td>
										</tr>
									</table>
								</td>
							</tr>
							<?php
										}
							?>
							<tr>
								<td height="20"></td>
							</tr>
							<tr>
								<td height="20">
									<table width="210" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center"><a href="<?=base_url()?>mainproduct/view/<?=$ra_item->get_sku()?>"><img border="0px" src="<?=base_url()?><?=get_image_file($ra_item->get_image(), 'l', $ra_item->get_sku());?>" width="80" height="80" /></a></td>
										</tr>
										<tr>
											<td align="center"><a href="<?=base_url()?>mainproduct/view/<?=$ra_item->get_sku()?>"><font face="Arial, Helvetica, sans-serif" size="2"><strong><?=$ra_item->get_content_prod_name()?$ra_item->get_content_prod_name():$ra_item->get_prod_name();?></strong></font></a></td>
										</tr>
										<tr>
											<td align="center" nowrap style="white-space:nowrap"><font face="Arial, Helvetica, sans-serif" size="3" color="#00aff0"><strong><?=platform_curr_format(PLATFORMID, $ra_item->get_price())?></strong></font></td>
										</tr>
										<tr>
											<td align="center"><table width="80" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td onclick="document.location.href = '<?=base_url()."cart/add_item?sku=".$ra_item->get_sku()?>'" width="80" height="22" align="center" background="<?=base_url()?>images/addthebasket.gif" style="background-repeat:no-repeat;cursor:pointer">
												<font face="Arial, Helvetica, sans-serif" size="1" color="#ffffff"><strong>add to basket</strong></font>
											</td>
										</tr>
										</table></td>
										</tr>
									</table>
								</td>
							</tr>
							<?php
									}
								}
							?>
						</table>
						<?php
						}
						?>
					</td>
				</tr>
			</table>
		</td>
		<td width="8">&nbsp;</td>
		<td width="798" valign="top">
		<?php
			$display = 0;
			foreach($show_tab as $tab)
			{
				if($tab)
				{
					$display = 1;
				}
			}

			if($display)
			{
		?>
			<dl id="ra_tab">
			<?php
				if($show_tab['description'])
				{
			?>
					<dt id="t_desc" ><b><?="Description"?></b></dt>
					<dd id="desc" >
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="690">&nbsp;</td>
									</tr>
								</table>
								</td>
							</tr>
							<?php
								if(!empty($what_we_say))
								{
							?>
							<tr>
								<td align="center">
								<table width="760" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td>
									<table width="740" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td><img src="<?=base_url()?>images/04products_say_01.gif" /></td>
										<td background="<?=base_url()?>images/04products_say_02.gif"><img src="<?=base_url()?>images/04products_say_02.gif" width="760" height="5"/></td>
										<td><img src="<?=base_url()?>images/04products_say_03.gif" /></td>
									</tr>

									<tr>
										<td background="<?=base_url()?>images/04products_say_04.gif" style="background-repeat:no-repeat"><img src="<?=base_url()?>images/04products_say_04.gif" /></td>
										<td align="center"><font face="Arial, Helvetica, sans-serif" size="3"><strong>What we say:</strong></font></td>
										<td background="<?=base_url()?>images/04products_say_06.gif" style="background-repeat:repeat"><img src="<?=base_url()?>images/04products_say_06.gif" /></td>
									</tr>
									<tr>
										<td background="<?=base_url()?>images/04products_say_04.gif" style="background-repeat:no-repeat"><img src="<?=base_url()?>images/04products_say_04.gif"  /></td>
										<td align="center"><img src="<?=base_url()?>images/03Subcategory_line.gif" width="680" height="2" /></td>
										<td background="<?=base_url()?>images/04products_say_06.gif" style="background-repeat:repeat"><img src="<?=base_url()?>images/04products_say_06.gif"  /></td>
									</tr>
									<tr>
										<td background="<?=base_url()?>images/04products_say_04.gif" style="background-repeat:repeat"><img src="<?=base_url()?>images/04products_say_04.gif"  /></td>
										<td width="735">
										<table align="center" width="735" border="0" cellspacing="0" cellpadding="3">
											<tr>
												<td align="center" valign="top"><img src="<?=base_url()?>images/quote_01.gif" width="14" height="11" /></td>
												<td width="710" align="center"><font face="Arial, Helvetica, sans-serif" size="2"><?=$prod_cont_obj->get_short_desc()?></font></td>
												<td align="center" valign="bottom"><img src="<?=base_url()?>images/quote_02.gif" width="14" height="11" /></td>
											</tr>
										</table>
										</td>
										<td background="<?=base_url()?>images/04products_say_06.gif" style="background-repeat:repeat"><img src="<?=base_url()?>images/04products_say_06.gif" /></td>
									</tr>
									<tr>
										<td><img src="<?=base_url()?>images/04products_say_07.gif"/></td>
										<td background="<?=base_url()?>images/04products_say_08.gif"><img src="<?=base_url()?>images/04products_say_08.gif" width="760"/></td>
										<td><img src="<?=base_url()?>images/04products_say_09.gif"/></td>
									</tr>
									</table>
									</td>
									</tr>
								</table>
								</td>
							</tr>
							<?php
								}
							?>
							<tr>
								<td height="10" align="center"></td>
							</tr>
							<tr>
								<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td>
										<table width="100%" border="0" cellspacing="0" cellpadding="10">
											<!-- short desciption -->
											<?php
												if(!empty($in_a_nutshell))
												{
											?>
											<tr>
												<td><font face="Arial, Helvetica, sans-serif" size="4"><strong>In a Nutshell</strong></font></td>
											</tr>
											<tr>
												<td height="10" align="center">
												<table cellspacing="0" cellpadding="0" border="0" width="100%">
													<tbody>
													<tr>
														<td height="2" bgcolor="#60d4ff"></td>
													</tr>
													</tbody>
												</table>
												</td>
											</tr>
											<tr>
												<td><font face="Arial, Helvetica, sans-serif" size="2"><?=$in_a_nutshell?></font></td>
											</tr>
											<?php
												}
											?>
											<!-- features -->
											<?php
												if(!empty($feature))
												{
											?>
											<tr>
												<td><font face="Arial, Helvetica, sans-serif" size="4"><strong>Features</strong></font></td>
											</tr>
											<tr>
												<td height="10" align="center">
												<table cellspacing="0" cellpadding="0" border="0" width="100%">
													<tbody>
													<tr>
														<td height="2" bgcolor="#60d4ff"></td>
													</tr>
													</tbody>
												</table>
												</td>
											</tr>
											<tr>
												<td><font face="Arial, Helvetica, sans-serif" size="2">
												<ul style="list-style-image: url(<?=base_url()?>images/arrow_02.gif); line-height:1.5em; margin-top:0">
													<?=$feature?>
												</ul>
												</font></td>
											</tr>
											<?php
												}
											?>
											<!-- in the box -->
											<?php
												if(!empty($in_the_box))
												{
											?>
											<tr>
												<td><font face="Arial, Helvetica, sans-serif" size="4"><strong>In the Box</strong></font></td>
											</tr>
											<tr>
												<td height="10" align="center">
												<table cellspacing="0" cellpadding="0" border="0" width="100%">
													<tbody>
													<tr>
														<td height="2" bgcolor="#60d4ff"></td>
													</tr>
													</tbody>
												</table>
												</td>
											</tr>
											<tr>
												<td><font face="Arial, Helvetica, sans-serif" size="2">
												<ul style="list-style-image: url(<?=base_url()?>images/arrow_02.gif); line-height:1.5em; margin-top:0">
													<?=$in_the_box?>
												</ul>
												</font></td>
											</tr>
											<?php
												}
											?>
											<!-- requirement -->
											<?php
												if(!empty($requirement))
												{
											?>
											<tr>
												<td><font face="Arial, Helvetica, sans-serif" size="4"><strong>Requirements</strong></font></td>
											</tr>
											<tr>
												<td height="10" align="center">
												<table cellspacing="0" cellpadding="0" border="0" width="100%">
													<tbody>
													<tr>
														<td height="2" bgcolor="#60d4ff"></td>
													</tr>
													</tbody>
												</table>
												</td>
											</tr>
											<tr>
												<td><font face="Arial, Helvetica, sans-serif" size="2">
												<ul style="list-style-image: url(<?=base_url()?>images/arrow_02.gif); line-height:1.5em; margin-top:0">
													<?=$requirement?>
												</ul>
												</font></td>
											</tr>
											<?php
												}
											?>
										</table>
										</td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td height="15" align="center"></td>
							</tr>
						</table>
					</dd>
				<?php
					}
					if($prod_spec)
					{
				?>
					<dt id="t_spec"><b><?="Specifications"?></b></dt>
					<dd id="spec">
						<table summary="specifications" width="100%" cellspacing="0" cellpadding="2" style="border-width:1px; border-color:#DADADA; border-style:solid; border-collapse:collapse;text-indent:1em;" rules="rows">
						<?php
							foreach($prod_spec AS $psg=>$ps_array)
							{
								?>
								<tr style="border-width:1px; border-color:#DADADA; border-style:solid;">
									<td colspan="2" bgcolor="#CCCCCC" >
									<font face="Arial, Helvetica, sans-serif" size="3"><b><?=$psg?></b></font></td>
								</tr>
								<?php
									foreach($ps_array AS $ps_key=>$ps_obj)
									{
										$unit_id = $ps_obj->get_unit_id();
										if($unit_id == 'txt')
										{
											$ps_value = $ps_obj->get_text();
										}
										else
										{
											if($ps_obj->get_end_value())
											{
												$start_value = number_format($ps_obj->get_start_value(),2, '.', '');
												$end_value = number_format($ps_obj->get_end_value(),2, '.', '');
												$ps_value = $start_value." - ".$end_value." ".$ps_obj->get_unit_id();
											}
											else
											{
												$start_value = number_format($ps_obj->get_start_value(),2, '.', '');
												$ps_value = $start_value." ".$ps_obj->get_unit_id();
											}
										}
										?>
											<tr style="border-width:1px; border-color:#DADADA; border-style:solid;">
												<td width="30%" bgcolor="#ECECEC"><font face="Arial, Helvetica, sans-serif" size="2"><b><?=$ps_key?></b></font></td>
												<td><font face="Arial, Helvetica, sans-serif" size="2"><?=$ps_value?></font></td>
											</tr>
										<?php
									}
							}
						?>
						</table>
					</dd>
				<?php
					}
					if($show_tab['instruction'])
					{
				?>
					<dt id="t_acc"><b><?="Instruction"?></b></dt>
					<dd id="acc">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td>
								<?=$instruction?>
							</td>
						</tr>
						</table>
					</dd>
				<?php
					}
					if($show_tab['accessories'])
					{
				?>
					<dt id="t_acc"><b><?="Accessories"?></b></dt>
					<dd id="acc">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr height="40px">
							<td valign="middle"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><strong>Customers who bought "<?=$prod_obj->get_name()?>" also bought these accessories:</strong></font></td>
						</tr>
						<tr>
							<td bgcolor="#ECECEC">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff"><strong>&nbsp;</strong></font><font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff"><strong>&nbsp;</strong></font><font face="Arial, Helvetica, sans-serif" size="2">Product Details</font></td>
								<td width="80" align="center"><font face="Arial, Helvetica, sans-serif" size="2">Price</font></td>
								<td width="80" align="center"><font face="Arial, Helvetica, sans-serif" size="2">Stock Details</font></td>
								<td width="100">&nbsp;</td>
							</tr>
							</table></td>
						</tr>
						<?php
							$count = 0;
							foreach ($acc["ra_cat_list"] as $rskey=>$ra_cat_obj)
							{
								for ($j=0; $j<count($ra_cat_obj); $j++)
								{
									$item = $acc["ra_cat_item_list"][$rskey][$j];
						?>

						<tr>
							<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<?php
									if($count != 0)
									{
						?>
							<tr>
								<td height="10"><img src="<?=base_url()?>images/03Subcategory_line.gif" width="750" height="2" /></td>
							</tr>
						<?php
									}
									$count++;
						?>
							<tr>
								<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="120"><a href="<?=base_url()."mainproduct/view/".$item->get_sku()?>"><img src="<?=base_url().get_image_file($item->get_image(), 'm', $item->get_sku())?>" width="50" height="50" /></a></td>
									<td><table width="100%" border="0" cellspacing="0" cellpadding="3">
									<tr>
										<td><a href="<?=base_url()."mainproduct/view/".$item->get_sku()?>"><font face="Arial, Helvetica, sans-serif" size="2"><strong><?=$item->get_prod_name()?></strong></font></a></td>
									</tr>
									<tr>
										<td><font face="Arial, Helvetica, sans-serif" size="2"><?=$item->get_detail_desc()?></font>
										<br /><a href="<?=base_url()."mainproduct/view/".$item->get_sku()?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#666666">click here for more information</font></a></td>
									</tr>
									</table></td>
									<td width="260"><table width="260" border="0" cellpadding="0" cellspacing="0">
									<?php
										$dstr = "";

										if($item->get_display_quantity())
										{
											$quantity = min($item->get_website_quantity(),$item->get_display_quantity());
										}
										else
										{
											$quantity = $item->get_website_quantity();
										}

										if($quantity)
										{
											$dstr = "<b>".$quantity."</b> in stock.";
										}
										else
										{
											$dstr = "<span style='font-size:10px;color:#666666; color:red;'>Out of stock</span>";
										}
									?>
									<tr>
										<td width="80" align="center" nowrap style="white-space:nowrap"><font face="Arial, Helvetica, sans-serif" size="4" color="#00aff0"><strong><?=platform_curr_format(PLATFORMID, $item->get_price())?></strong></font></td>
										<td width="80" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#8eb53a"><strong><?=$dstr?></strong></font></td>
										<td width="100" align="center"><table width="79" border="0" cellspacing="0" cellpadding="0">
									<?php
										if($quantity)
										{
									?>
										<tr>
											<td onclick="document.location.href = '<?=base_url()."cart/add_item?sku=".$ra_item->get_sku()?>'" background="<?=base_url()?>images/03Subcategory_addtobasket.gif" style="width:100%;cursor:pointer" height="23"><font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff" style="padding-left:8px;padding-right:27px"><strong>Add to</strong></font></td>
										</tr>
									<?php
										}
									?>
										</table></td>
									</tr>
									</table></td>
								</tr>
								</table></td>
							</tr>
							</table>
							</td>
						</tr>
						<?php
								}
							}
						?>
						<tr>
							<td height="15" align="center"></td>
						</tr>
						</table>
					</dd>
			<?php
				}
				if($show_tab['bundle'])
				{
			?>
					<dt id="t_bundle"><b><?="Bundles"?></b></dt>
					<dd id="bundle">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr height="40px">
							<td valign="middle"><font face="Arial, Helvetica, sans-serif" size="2" color="#00aff0"><strong>Buy these Recommended Bundles for more savings!</strong></font></td>
						</tr>
						<tr>
							<td align="center" bgcolor="#ECECEC"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff"><strong>&nbsp;</strong></font><font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff"><strong>&nbsp;</strong></font><font face="Arial, Helvetica, sans-serif" size="2">Product Details</font></td>
								<td width="80" align="center"><font face="Arial, Helvetica, sans-serif" size="2">Price</font></td>
								<td width="80" align="center"><font face="Arial, Helvetica, sans-serif" size="2">Stock Details</font></td>
								<td width="100">&nbsp;</td>
							</tr>
							</table></td>
						</tr>
						<tr>
							<td align="center">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<?php

							foreach($bundle as $b_obj)
							{
						?>
							<tr>
								<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="200" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
							<?php
								$count = 0;

								foreach($b_obj->get_component_sku_list() as $component)
								{
									if($count > 0)
									{
							?>
										<td align="center"><img src="<?=base_url()?>images/bundleplus.gif" width="10" height="10" /></td>
							<?php
									}
									$count++;
							?>
									<td align="center">
										<div class="bundle-image" style="padding-left:1px">
											<a class="info" href="<?=base_url()?>product_skype/info/<?=$component['component_sku']?>" rel="lyteframe" rev="width: 600px; height:527px; scrolling: auto;padding: 40px;">
											<img class="prod-img" src="<?=base_url().get_image_file($component['component_image_file_ext'],'m',$component['component_sku'])?>" width="50" height="50" />
											</a>
											<div class="modal">
												<img name="large_image" src="<?=base_url().get_image_file($component['component_image_file_ext'],'l',$component['component_sku'])?>" border="0" width="150" height="150" />
											</div>
										</div>
									</td>
									<!--
										<td align="center">
											<a href="<?=base_url()."mainproduct/view/".$component['component_sku']?>">
												<img src="<?=base_url().get_image_file($component['component_image_file_ext'],'m',$component['component_name'])?>" width="50" height="50" />
											</a>
										</td>
									 -->
							<?php
								}
							?>
									</tr>
									</table></td>
									<td><table width="100%" border="0" cellspacing="0" cellpadding="3">
									<tr>
										<td><table width="350" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="30">&nbsp;</td>
											<td>
												<font face="Arial, Helvetica, sans-serif" size="2">
											<?php
												foreach($b_obj->get_component_sku_list() as $component)
												{
											?>
													&bull; <strong><?=$component["component_name"]?></strong><br />
											<?php
													// does not show "click for more info" for primary product as requested by jesslyn 20110304
													if($sku != $component['component_sku'])
													{
											?>
													<a class="info" href="<?=base_url()?>mainproduct/info/<?=$component['component_sku']?>" rel="lyteframe" rev="width: 600px; height:527px; scrolling: auto;padding: 40px;">
														<font face="Arial, Helvetica, sans-serif" size="1" color="#666666">&nbsp;click for more info</font><br />
													</a>
											<?php
													}
												}
											?>
												</font>
											</td>
										</tr>
										</table></td>
									</tr>
									</table></td>
									<td width="260" align="center"><table width="260" border="0" cellpadding="0" cellspacing="0">
									<?php
										$dstr = "";

										if($quantity = $b_obj->get_website_quantity())
										{
											$dstr = "<b>".$quantity."</b> in stock.";
										}
										else
										{
											$dstr = "<span style='font-size:10px;color:#666666; color:red;'>Out of stock</span>";
										}
									?>
									<tr>
										<td width="80" align="center" nowrap style="white-space:nowrap"><font face="Arial, Helvetica, sans-serif" size="4" color="#00aff0"><strong><?=platform_curr_format(PLATFORMID, $b_obj->get_total_price())?></strong></font></td>
										<td width="80" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#8eb53a"><strong><?=$dstr?></strong></font></td>
										<td width="100" align="center"><table width="79" border="0" cellspacing="0" cellpadding="0">
										<tr>
										<?if($quantity){?>
											<td onclick="document.location.href = '<?=base_url()."cart/add_item?sku=".$b_obj->get_prod_sku()?>'" background="<?=base_url()?>images/03Subcategory_addtobasket.gif" style="width:100%;cursor:pointer" height="23"><font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff" style="padding-left:8px;padding-right:27px"><strong>Add to</strong></font></td>
										<?}?>
										</tr>
										</table></td>
									</tr>
									</table></td>
								</tr>
								</table>
								</td>
							</tr>
						<?php
							}
						?>
							</table>
							</td>
						</tr>
						<tr>
							<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>&nbsp;</td>
							</tr>
							</table></td>
						</tr>
						<tr>
							<td height="15" align="center"></td>
						</tr>
						</table>
					</dd>
				<?php
					}
					if($show_tab['video'])
					{
				?>
					<dt id="t_video"><b><?="Videos"?></b></dt>
					<dd id="video">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="690">&nbsp;</td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td align="center" bgcolor="#ECECEC">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<?php
									if(!empty($featured_review_video))
									{
								?>
									<tr>
										<td><font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff"><strong>&nbsp;&nbsp;</strong></font><font face="Arial, Helvetica, sans-serif" size="2"><strong>View our Reviews</strong></font></td>
									</tr>
								<?php
									}
								?>
								</table>
								</td>
							</tr>
							<tr>
								<td align="center">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td>
										<table width="100%" border="0" cellspacing="0" cellpadding="9">
											<tr>
											<?php
												if(!empty($featured_review_video))
												{
													//$src = $featured_review_video->get_src();
													$ref_id = $featured_review_video->get_ref_id();
													if($http)
													{
												?>
													<td align="center">
														<object width="554" height="344">
															<param name="movie" value="http://www.youtube.com/v/<?=$ref_id?>&amp;fs=1"></param>
															<param name="allowFullScreen" value="true"></param>
															<param name="allowscriptaccess" value="always"></param>
															<param name="allowNetworking" value="internal" />
															<param name="wmode" value="transparent" />
															<param name="autoplay" value="0"></param>
															<embed src="http://www.youtube.com/v/<?=$ref_id?>&amp;fs=1" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" allowfullscreen="true" allownetworking="internal" width="554" height="344"></embed>
														</object>
														<br />
														<a href="http://www.youtube.com/v/<?=$ref_id?>&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = fr, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><span style="font-size:10px;color:blue;"><b>Enlarge Video</b></span></a>
													</td>
												<?php
													}
													else
													{
												?>
													<td align="center">
														<object width="554" height="344">
															<param name="movie" value="https://www.youtube.com/v/<?=$ref_id?>&amp;fs=1"></param>
															<param name="allowFullScreen" value="true"></param>
															<param name="allowscriptaccess" value="always"></param>
															<param name="allowNetworking" value="internal" />
															<param name="wmode" value="transparent" />
															<param name="autoplay" value="0"></param>
															<embed src="https://www.youtube.com/v/<?=$ref_id?>&amp;fs=1" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" allowfullscreen="true" allownetworking="internal" width="554" height="344"></embed>
														</object>
														<br />
														<a href="https://www.youtube.com/v/<?=$ref_id?>&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = fr, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><span style="font-size:10px;color:blue;"><b>Enlarge Video</b></span></a>
													</td>
												<?php
													}
												}
												else
												{
													//echo 'Video not Found';
												}
												?>
											</tr>
											<?php
												if(!empty($featured_review_video))
												{
											?>
											<tr>
												<td align="center"><font face="Arial, Helvetica, sans-serif" size="3"><?=get_video_title($ref_id, $src);?></font></td>
											</tr>
											<?php
												}
											?>
											<tr>
											<?php
												if(sizeof($latest_review_video) >= 2)
												{
											?>
												<td colspan="3" align="center">

													<!-- "previous page" action -->
													<a class="prev browse left"></a>

													<!-- root element for scrollable -->
													<div class="scrollable">

														<!-- root element for the items -->
														<div class="items">
														<?php
															foreach($latest_review_video as $count=>$rv_obj)
															{
																if(($count+1)%5 == 1)
																{
																	echo "<div>";
																}
																echo "<div style=\"width:132px\">";
																if($http)
																{
																	?><a href="http://www.youtube.com/v/<?=$rv_obj->get_ref_id()?>&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = lrv, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><img src="http://img.youtube.com/vi/<?=$rv_obj->get_ref_id()?>/1.jpg" valign="center" width="124px" height="96px" title="" alt="View our Reviews" border="0" /></a>
																	<br /><font face="Arial, Helvetica, sans-serif" size="2"><?=get_video_title($rv_obj->get_ref_id(), $rv_obj->get_src())?></font><?php
																}
																else
																{
																	?><a href="https://www.youtube.com/v/<?=$rv_obj->get_ref_id()?>&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = lrv, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><img src="http://img.youtube.com/vi/<?=$rv_obj->get_ref_id()?>/1.jpg" valign="center" width="124px" height="96px" title="" alt="View our Reviews" border="0" /></a>
																	<br /><font face="Arial, Helvetica, sans-serif" size="2"><?=get_video_title($rv_obj->get_ref_id(), $rv_obj->get_src())?></font><?php
																}
																echo "</div>";
																if(($count+1)%5 == 0 || ($count+1) == sizeof($latest_review_video))
																{
																	echo "</div>";
																}
															}
														?>
														</div>
													</div>
													<!-- "next page" action -->
													<a class="next browse right"></a>
												</td>
											<?php
												}
											?>
											</tr>
										</table>
										</td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td align="center" bgcolor="#ECECEC">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<?php
									if(!empty($featured_guide_video))
									{
								?>
									<tr>
										<td><font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff"><strong>&nbsp;&nbsp;</strong></font><font face="Arial, Helvetica, sans-serif" size="2"><strong>View our Guides</strong></font></td>
									</tr>
								<?php
									}
								?>
								</table>
								</td>
							</tr>
							<tr>
								<td align="center">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td>
										<table width="100%" border="0" cellspacing="0" cellpadding="9">
											<tr>
											<?php
												if(!empty($featured_guide_video))
												{
													//$src = $featured_guide_video->get_src();
													$ref_id = $featured_guide_video->get_ref_id();
													if($http)
													{
											?>
													<td align="center">
														<object width="554" height="344">
															<param name="movie" value="http://www.youtube.com/v/<?=$ref_id?>&amp;fs=1"></param>
															<param name="allowFullScreen" value="true"></param>
															<param name="allowscriptaccess" value="always"></param>
															<param name="wmode" value="transparent" />
															<param name="autoplay" value="0"></param>
															<param name="allowNetworking" value="internal" />
															<embed src="http://www.youtube.com/v/<?=$ref_id?>&amp;fs=1" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" allowfullscreen="true" allownetworking="internal" width="554" height="344"></embed>
														</object>
														<br />
														<a href="http://www.youtube.com/v/<?=$ref_id?>&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = fg, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><span style="font-size:10px;color:blue;"><b>Enlarge Video</b></span></a>
													</td>
											<?php
													}
													else
													{
											?>
													<td align="center">
														<object width="554" height="344">
															<param name="movie" value="https://www.youtube.com/v/<?=$ref_id?>&amp;fs=1"></param>
															<param name="allowFullScreen" value="true"></param>
															<param name="allowscriptaccess" value="always"></param>
															<param name="wmode" value="transparent" />
															<param name="autoplay" value="0"></param>
															<param name="allowNetworking" value="internal" />
															<embed src="https://www.youtube.com/v/<?=$ref_id?>&amp;fs=1" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" allowfullscreen="true" allownetworking="internal" width="554" height="344"></embed>
														</object>
														<br />
														<a href="https://www.youtube.com/v/<?=$ref_id?>&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = fg, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><span style="font-size:10px;color:blue;"><b>Enlarge Video</b></span></a>
													</td>
											<?php
													}
												}
												else
												{
													//echo 'Video not Found';
												}
											?>
											</tr>
											<?php
												if(!empty($featured_guide_video))
												{
											?>
											<tr>
												<td align="center"><font face="Arial, Helvetica, sans-serif" size="3"><?=get_video_title($ref_id, $src);?></font></td>
											</tr>
											<?php
												}
											?>
											<tr>
											<?php
												if(sizeof($latest_guide_video) >= 2)
												{
											?>
												<td colspan="3" align="left">

													<!-- "previous page" action -->
													<a class="prev browse left"></a>

													<!-- root element for scrollable -->
													<div class="scrollable">

														<!-- root element for the items -->
														<div class="items">
														<?php
															foreach($latest_guide_video as $count=>$gv_obj)
															{
																if(($count+1)%5 == 1)
																{
																	echo "<div>";
																}
																echo "<div style=\"width:132px\">";
																if($gv_obj->get_src() == "Y")
																{
																	?><a href="http://www.youtube.com/v/<?=$gv_obj->get_ref_id()?>&amp;fs=1&amp;rel=0&amp;border=1" toptions="group = lgv, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><img src="http://img.youtube.com/vi/<?=$gv_obj->get_ref_id()?>/1.jpg" valign="center" width="124px" height="96px" title="" alt="View our Guides" border="0" /></a>
																	<br /><font face="Arial, Helvetica, sans-serif" size="2"><?=get_video_title($gv_obj->get_ref_id(), $gv_obj->get_src())?></font><?php
																}
																elseif($gv_obj->get_src() == "V")
																{
																	?><a href="http://view.vzaar.com/<?=$gv_obj->get_ref_id()?>.flashplayer" toptions="group = lgv, type = flash, effect = show, width = 830, height = 495, overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})"><img src="http://vzaar.com/videos/<?=$gv_obj->get_ref_id()?>.thumb" title="" alt="View our Guides" border="0" /></a>
																	<br /><font face="Arial, Helvetica, sans-serif" size="2"><?=get_video_title($gv_obj->get_ref_id(), $gv_obj->get_src())?></font><?php
																}
																echo "</div>";
																if(($count+1)%5 == 0 || ($count+1) == sizeof($latest_guide_video))
																{
																	echo "</div>";
																}
															}
														?>
														</div>
													</div>
													<!-- "next page" action -->
													<a class="next browse right"></a>
												</td>
											<?php
												}
											?>
											</tr>
										</table>
										</td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td>&nbsp;</td>
									</tr>
								</table>
								</td>
							</tr>
							<tr>
								<td height="15" align="center"></td>
							</tr>
						</table>
					</dd>
			<?php
				}

				$reevoo_mark = new ReevooMark("reevoo_cache", "http://mark.reevoo.com/reevoomark/first_two_reviews.html", "CHA", "$sku");
				if($reevoo_mark->reviewCount() > 0)
				{
			?>
				<dt id="t_rev" class="requires-reevoomark"><b><?="Review"?></b></dt>
				<dd id="rev" class="requires-reevoomark">
				  <?php if( $reevoo_mark->reviewCount() == 0 ){ ?>
				    <h1>No reviews</h1>
				  <?php } ?>
				  <?php
					$reevoo_mark->render();
				  ?>
				</dd>
			<?php
				}
			?>
			</dl>
			<script type="text/javascript">
			$("#ra_tab").KandyTabs(
			{
				trigger:"click",
				current:<?=$tab_no?$tab_no:"1"?>
			}
			);
			</script>

			<!-- <iframe id="prod_video" name="prod_video" src="<?=base_url()?>mainproduct/product_video/<?=$sku?>" width="812px" height="900px" scrolling="yes" frameborder="0" style="overflow-x: hidden;"></iframe> -->
			</td>
	<?php
		}
	?>
		</tr>
		<tr>
			<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td height="15" align="center"></td>
		</tr>
	</table>
	</td>
</tr>
<tr>
	<td height="4"></td>
</tr>
<?php
	if(!empty($ra_list))
	{
?>
	<tr>
		<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><img src="<?=base_url()?>images/02category_92.gif" width="5" height="28" /></td>
			<td width="1010" align="center" bgcolor="#00AFF0"><font face="Arial, Helvetica, sans-serif" size="3" color="#ffffff"><strong>What Do Customers Ultimately Buy After Viewing This Item?</strong></font></td>
			<td><img src="<?=base_url()?>images/02category_94.gif" width="5" height="28" /></td>
		</tr>
		<tr>
			<td background="<?=base_url()?>images/02category_95.gif" style="background-repeat:repeat"><img src="<?=base_url()?>images/02category_95.gif" width="5" height="130" /></td>
			<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">

		<?php
			foreach($ra_list as $rskey=>$ra_item)
			{
				if($rskey <= 3)
				{
					if($rskey != 0)
					{
		?>
			<tr>
				<td height="10" align="center"><img src="<?=base_url()?>images/03Subcategory_line.gif" width="750" height="2" /></td>
			</tr>
		<?php
					}
		?>

			<tr>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="120" align="center"><a href="<?=base_url()."mainproduct/view/".$ra_item->get_sku()?>"><img src="<?=base_url()?><?=get_image_file($ra_item->get_image(), 'm', $ra_item->get_sku());?>" width="50" height="50" /></a></td>
					<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="3">
					<tr>
						<td><a href="<?=base_url()."mainproduct/view/".$ra_item->get_sku()?>"><font face="Arial, Helvetica, sans-serif" size="2"><strong><?=$ra_item->get_content_prod_name()?$ra_item->get_content_prod_name():$ra_item->get_prod_name();?></strong></font></a></td>
					</tr>
					<tr>
						<td><font face="Arial, Helvetica, sans-serif" size="2"><?=$ra_prod_cont_list[$rskey]->get_short_desc();?></font></td>
					</tr>
					<tr>
						<td>
						<table width="300" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<?php
								$dstr = "";

								if($ra_item->get_display_quantity())
								{
									$quantity = min($ra_item->get_website_quantity(),$ra_item->get_display_quantity());
								}
								else
								{
									$quantity = $ra_item->get_website_quantity();
								}

								if($quantity)
								{
									$dstr = "<b>".$quantity."</b> in stock.";
								}
								else
								{
									$dstr = "<span style='font-size:10px;color:#666666; color:red;'>Out of stock</span>";
								}
							?>
							<td width="100"><font face="Arial, Helvetica, sans-serif" size="1" color="#8eb53a"><strong><?=$dstr?></strong></font></td>
							<?php
								if($ra_item->get_with_bundle())
								{
							?>
							<td>
								<a href="<?=base_url()."mainproduct/view/".$ra_item->get_sku()."?tab=bundle"?>">
								<strong>&nbsp;<img src="<?=base_url()?>images/buddle.gif" width="20" height="19" />&nbsp;</strong>
								<font face="Arial, Helvetica, sans-serif" size="2">See available bundles</font>
								</a>
							</td>
							<?php
								}
							?>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
					<td width="110" align="center">
					<table width="100" border="0" cellspacing="0" cellpadding="9">
					<tr>
						<td align="center"><font face="Arial, Helvetica, sans-serif" size="4" color="#00aff0" nowrap style="white-space:nowrap"><strong><?=platform_curr_format(PLATFORMID, $ra_item->get_price());?></strong></font></td>
					</tr>
					<tr>
						<td align="center">
						<table width="79" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<?php if($quantity){?>
							<td onclick="document.location.href = '<?=base_url()."cart/add_item?sku=".$ra_item->get_sku()?>'" background="<?=base_url()?>images/03Subcategory_addtobasket.gif" style="width:100%;cursor:pointer" height="23">
								<font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff" style="padding-left:8px;padding-right:27px"><strong>Add to</strong></font>
							</td>
						<?php }?>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
		<?php
				}
			}
		?>
			</table>
			</td>
			<td background="<?=base_url()?>images/02category_97.gif" style="background-repeat:repeat"><img src="<?=base_url()?>images/02category_97.gif" width="5" height="130" /></td>
		</tr>
		<tr>
			<td width="5" height="2"><img src="<?=base_url()?>images/02category_98.gif" width="5" height="2" /></td>
			<td background="<?=base_url()?>images/02category_99.gif" height="2"><img src="<?=base_url()?>images/02category_99.gif" width="1010" height="2" /></td>
			<td width="5" height="2"><img src="<?=base_url()?>images/02category_100.gif" width="5" height="2" /></td>
		</tr>
		</table>
		</td>
	</tr>
<?php
	}
?>
<!-- 3rd row -->
<tr>
	<td height="4"></td>
</tr>
<tr>
	<td height="10"></td>
</tr>
<tr>
	<td height=40px></td>
</tr>
<tr>
	<td>
		<?php include VIEWPATH . 'footer_web.php';?>
	</td>
</tr>
</table>
<script type="text/javascript">
$(document).ready(function(){
      $.modalInitialization({
            parent:'.bundle-image',
            trigger:'.bundle-image .prod-img',
            target:'.modal',
            followMouse:true,
            offset:10,
            canEnter:false,
            fadeSpeed:0
      });
});
$("#t_rev").click(function(){
	ReevooMark.show_popup(document.getElementById('sku').value);
	$(".tabcur").click();
});
</script>
</body>
</html>