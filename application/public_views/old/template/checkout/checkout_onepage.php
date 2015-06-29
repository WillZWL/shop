<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>ValueBasket</title>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/prototype/prototype.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/varien/form.js"></script>

    <?php
    print $_scripts;
    print "\n";
    print $_styles;
    ?>
</head>
<body>
<?php
$this->load->helper('tbswrapper');
$this->tbswrapper = new Tbswrapper();
?>
<?php //include_once(VIEWPATH . "tbs_header.php") ?>
<?php print $main_content ?>
<?php //include_once(VIEWPATH . "tbs_footer.php") ?>
</body>
</html>
