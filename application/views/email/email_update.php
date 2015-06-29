<html>

<head>

<title><?php echo $title;?></title>

</head>

<body>

<h1><?php echo $header;?></h1>

<?php if (isset($obj)){?>
<form method="post">
id:     <input name="id" value="<?=$obj->get_id()?>" READONLY><br>
site:   <input name="site" value="<?=$obj->get_site()?>"><br>
emailname:  <input name="emailname" value="<?=$obj->get_emailname()?>"><br>
emailfrom:  <input name="emailfrom" value="<?=$obj->get_emailfrom()?>"><br>
emailgoesto:    <input name="emailgoesto" value="<?=$obj->get_emailgoesto()?>"><br>
emailsubject:   <input name="emailsubject" value="<?=$obj->get_emailsubject()?>"><br>
emailcontent:   <input name="emailcontent" value="<?=$obj->get_emailcontent()?>"><br>
emailsignature:     <input name="emailsignature" value="<?=$obj->get_emailsignature()?>"><br>
emailsigtitle:  <input name="emailsigtitle" value="<?=$obj->get_emailsigtitle()?>"><br>
variables:  <input name="variables" value="<?=$obj->get_variables()?>"><br>
dateupdated:    <input name="dateupdated" value="<?=$obj->get_dateupdated()?>"><br>
paxupdated:     <input name="paxupdated" value="<?=$obj->get_paxupdated()?>"><br>
active:     <input name="active" value="<?=$obj->get_active()?>"><br>
<input name="submit" type="submit" value="Update">
<?php } else {?>

<font color="red"><b>Record Not Found.</b></font>
<?php } ?>
</body>

</html>

