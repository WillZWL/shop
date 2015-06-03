<?php
if($step=='1'){$whaton1='on';}else{$whaton1='off';}
if($step=='2'){$whaton2='on';}else{$whaton2='off';}
if($step=='3'){$whaton3='on';}else{$whaton3='off';}
if($step=='4'){$whaton4='on';}else{$whaton4='off';}
//if($step=='5'){$whaton5='on';}else{$whaton5='off';}
?>		<table width="100%" cellspacing="0" cellpadding="0">
			<tr style="font-weight:bold;background:#ffffff">
				<td height="58px">&nbsp;
				<?php if($step<'4'){  } ?>

				<a href="<?=$_SESSION['pre_page']?>"><img src="/images/checkout/processcontinue.gif"></a>
				<?php $_SESSION['pre_page']=htmlentities($_SERVER['REQUEST_URI']); ?>
				</td>
				<td width="195px" height="58px" style="background-image:url('/images/checkout/process<?=$whaton1?>_01.gif'); background-repeat:no-repeat;">&nbsp;</td>
				<td width="92px" height="58px" style="background-image:url('/images/checkout/process<?=$whaton2?>_02.gif'); background-repeat:no-repeat;">&nbsp;</td>
				<td width="77px" height="58px" style="background-image:url('/images/checkout/process<?=$whaton3?>_03.gif'); background-repeat:no-repeat;">&nbsp;</td>
				<td width="205px" height="58px" style="background-image:url('/images/checkout/process<?=$whaton4?>_04.gif'); background-repeat:no-repeat;">&nbsp;</td>
			</tr>
		</table>
