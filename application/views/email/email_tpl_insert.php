<?php include_once "email_tpl_header.php"?>
<link type="text/css" rel="stylesheet" href="<?=base_url()?>css/email_template.css">
<h1><?=$header;?></h1>

<?if (isset($_POST["submit"])) echo "<font color='red'>".validation_errors()."</font>";?>
<?=form_open_multipart("email/email_tpl/insert")?>
<table border="0" cellpadding="4" cellspacing="1" width="80%" class="table">
	<tr>
		<td class="heading" width="20%">Template Name</td>
		<td class="alt_bg"><input name="tpl[name]" class="box" value="<?=set_value('tpl[name]');?>"></td>
	</tr>
	<tr>
		<td class="heading">Description</td>
		<td><textarea name="tpl[description]" class="area" rows=3 value="<?=set_value('tpl[description]');?>"></textarea></td>
	</tr>
	<tr>
		<td class="heading">Template File</td>
		<td class="alt_bg"><input name="tpl[file]" class="box" type="file" value="<?=set_value('tpl[file]');?>"></td>
	</tr>
	<tr>
		<td class="heading">Subject</td>
		<td class="alt_bg"><input name="tpl[subject]" class="box"></td>
	</tr>
</table>
<br>
<table border="0" cellpadding="4" cellspacing="1" width="80%" class="table">
	<tr>
		<td class="heading" width="20%">Attachment #1 Name</td>
		<td class="alt_bg"><input name="att[0][name]" class="box"></td>
	</tr>
	<tr>
		<td class="heading">Attachment #1 Description</td>
		<td><textarea name="att[0][description]" class="area" rows=3></textarea></td>
	</tr>
	<tr>
		<td class="heading">Attachment #1 File</td>
		<td class="alt_bg"><input name="att[0][tpl_file]" class="box" type="file"></td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="1" width="80%" class="table">
	<tr>
		<td class="heading" width="20%">Attachment #2 Name</td>
		<td class="alt_bg"><input name="att[1][name]" class="box"></td>
	</tr>
	<tr>
		<td class="heading">Attachment #2 Description</td>
		<td><textarea name="att[1][description]" class="area" rows=3></textarea></td>
	</tr>
	<tr>
		<td class="heading">Attachment #2 File</td>
		<td class="alt_bg"><input name="att[1][tpl_file]" class="box" type="file"></td>
	</tr>
</table>

<table border="0" cellpadding="4" cellspacing="1" width="80%" class="table">
	<tr>
		<td class="heading" width="20%">Attachment #3 Name</td>
		<td class="alt_bg"><input name="att[2][name]" class="box"></td>
	</tr>
	<tr>
		<td class="heading">Attachment #3 Description</td>
		<td><textarea name="att[2][description]" class="area" rows=3></textarea></td>
	</tr>
	<tr>
		<td class="heading">Attachment #3 File</td>
		<td class="alt_bg"><input name="att[2][tpl_file]" class="box" type="file"></td>
	</tr>
</table>
<p align="center" style="width:80%"><input type="submit" name="submit" value="Submit"></p>
</form>
<?php include_once "email_tpl_footer.php"?>