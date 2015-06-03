<?php include_once "email_tpl_header.php"?>

<h1><?=$header;?></h1>

<ul>

<?foreach($templates as $template){?>

<li>
<p><?echo '<a href="/'.SELF.'/test/test/update/'.$email->get_id().'">From: '.$email->get_emailfrom().' To: '.$email->get_emailgoesto();?></a></p>
</li>

<?}?>

</ul>

<?php include_once "email_tpl_footer.php"?>