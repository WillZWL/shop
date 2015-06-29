<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body style="width:auto;margin:4px;"<?if ($success) {?> onLoad="UpdateBack();parent.document.getElementById('lbClose').onclick()"<?}?>>
<div style="width:auto;text-align:left">
<?=$notice["img"]?>
<center>
<form name="fm" method="post">
    <textarea name="note" style="width:99%" rows="5"><?=htmlspecialchars(@call_user_func(array($obj, "get_note")))?></textarea>
    <input type="submit" value="<?=$lang["add_note"]?>">
    <input type="hidden" name="posted" value="1">
</form>
</center>
<hr></hr>
<?php
    if ($objlist)
    {
        foreach ($objlist as $note_obj)
        {
?>
            <p class="normal_p"><?=nl2br($note_obj->get_note())?></p><p class="normal_p comment"><?=$lang["create_by"]?>: <?=$note_obj->get_create_by()?> &nbsp; &nbsp; <?=$lang["create_on"]?>: <?=$note_obj->get_create_on()?><br><br></p>
<?php
        }
    }
?>
</div>
<script>
function UpdateBack()
{
    window.parent.document.getElementById('note_<?=$line?>').innerHTML = '<?=str_replace(array("'", "\n"), array("\'", "<br \>"), @call_user_func(array($obj, "get_note")))?>'
}
</script>
<?=$notice["js"]?>
</body>
</html>