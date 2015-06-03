<?php
function print_header($lang, $is_fail_validation)
{
	$output_string = "<tr class='header'>";
	$output_string .= "<td TITLE=" . $lang["trans_id"] . ">" . $lang["trans_id"] . "</td>";
	$output_string .= "<td TITLE=" . $lang["so_no"] . ">" . $lang["so_no"] . "</td>";
	$output_string .= "<td TITLE=" . $lang["courier_pick_date"] . ">" . $lang["courier_pick_date"] . "</td>";
	$output_string .= "<td TITLE=" . $lang["comment"] . ">" . $lang["comment"] . "</td>";
	if (!$is_fail_validation)
		$output_string .= "<td><input value='reprocess' type='submit'></td>";
	$output_string .= "</tr>";

	return $output_string;
}
?>
<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript">
	function Download(path)
	{
		location.href = path;
	}
</script>
</head>
<body>
<div id="main">
<?=$notice["js"]?>
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30" class="title"><?=$lang["title"]?></td>
	</tr>
	<tr>
		<td height="2" class="line"></td>
		<td height="2" class="line"></td>
	</tr>
</table>
<form name="uploadForm" method="POST" enctype="multipart/form-data" onsubmit="return CheckForm(this)">
<table height="70" class="page_header" width="100%">
	<tr>
		<td width="150"><?=$lang["file_type_reminder"]?></td>
		<td><input accept="csv" type="file" class="input" id="process_file" name="process_file" notEmpty></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?=$lang['submit_button']?>"></td>
	</tr>
	<tr>
		<td>CSV format (2 column only):</td>
		<td><div style="font-size:16px;">so_no,(yyyy-mm-dd)date</div></td>
	</tr>
</table>
<input type="hidden" name="posted" value='1'>
</form>
<table width="100%" class="tb_list">
	<tr>
		<td width="200px"><?php print $lang["batch_id"] . ": " . $batch_id; ?></td>
<?php
			if ((isset($show_success)) && ($show_success))
				$class_highlight = "class=\"success_highlight\"";
			else
				$class_highlight = "";
			if ($error_message != "")
				$class_highlight = "class=\"warning\"";
?>
		<td <?php print $class_highlight ?>>
<?php 
		if ($show_success) 
			print $lang["success_message"];
		else if ($error_message != "")
			print $error_message;
?>
		</td>
		<td width="200px"><?php print $lang["number_of_successes"] . ": " . $number_of_successes; ?></td>
		<td width="200px" <?php print ($number_of_failures > 0) ? "class=\"warning\"":""  ?>><?php print $lang["number_of_failures"] . ": " . $number_of_failures; ?></td>
	</tr>
</table>
<form name="reprocess" method="post" action="/order/import_finance_dispatch_date/reprocess/<?=$batch_id;?>">
<table width="100%" class="tb_list">
	<tr class='light_header' align='center'><td colspan='5'><?php print $lang["process_list"] ?></td></tr>
<?php 
	print print_header($lang, $is_fail_validation);
	$i = 0;
	if ($combined_validate_result)
	{
//		print "<input type='hidden' name='batch_id' id='batch_id' value='" . $batch_id . "'>";
		foreach ($combined_validate_result as $cResult)
		{
			$row_style = "";
			if ($cResult->get_has_error())
				$row_style = 'warning';
			else
				$row_style = "row" . ($i % 2);
			print "<tr class='" . $row_style . "' onMouseOver=\"AddClassName(this, 'highlight')\" onMouseOut=\"RemoveClassName(this, 'highlight')\">";
			if ($cResult->get_has_error())
				$trans_id = $cResult->get_trans_id();
			else
				$trans_id = ($i + 1);
			print "<td width='60px'>" . $trans_id . "</td>\n";
			print "<td>";
			if ($cResult->get_has_error())
			{
				print "<input type='hidden' name='" . $cResult->get_trans_id() . "' id='" . $cResult->get_trans_id() . "'>";
				print "<input name='reProcessSono[" . $cResult->get_trans_id() . "]' id='reProcessSono[" . $cResult->get_trans_id() . "]' value='" . $cResult->get_so_no() . "'>";
			}
			else
				print $cResult->get_so_no();
			print "</td>\n";
			print "<td>";
			if ($cResult->get_has_error())
			{
				print "<input name='reProcessDispatch[" . $cResult->get_trans_id() . "]' id='reProcessDispatch[" . $cResult->get_trans_id() . "]' value='" . $cResult->get_finance_dispatch_date() . "'>";
			}
			else
				print $cResult->get_finance_dispatch_date();
			print "</td>\n";
			print "<td>" . $cResult->get_error_message() . "</td>\n";
			if (!$is_fail_validation)
			{
				print "<td width='100px'>";
				if ($cResult->get_has_error())
				{
					print "<input type='checkbox' value='" . $cResult->get_trans_id() . "' name='reProcessCheck[" . $cResult->get_trans_id() . "]'>";
				}
				print "</td>";
			}
			print "</tr>";
			$i++;
		}
	}
	print print_header($lang, $is_fail_validation);
 ?>
</table>
</form>
</body>
</html>
