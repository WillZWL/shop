<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <?php
    print $_title;
    print "\n";
    ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php
    print $_meta;
    ?>

    <link rel="SHORTCUT ICON" href="/resources/images/favicon.ico"/>

    <script type="text/javascript" src="/resources/js/jquery.js"></script>
    <script type="text/javascript" src="/resources/js/jquery.selectbox.js"></script>
    <script type="text/javascript" src="/resources/js/jquery.fancybox-1.3.4.js"></script>
    <script type="text/javascript" src="/resources/js/default.js"></script>

    <link href="/resources/css/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" media="screen"/>
    <!-- load fonts -->
    <link
        href="<?= ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http") ?>://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin,cyrillic"
        rel="stylesheet" type="text/css"/>
    <link
        href="<?= ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http") ?>://fonts.googleapis.com/css?family=Rokkitt:400,700"
        rel="stylesheet" type="text/css"/>

    <?php print add_css_helper('resources/css/reset'); ?>
    <?php print add_css_helper('resources/css/selectbox'); ?>
    <?php print add_css_helper('resources/css/style'); ?>
    <?php print add_css_helper('resources/css/print', FALSE, 'print'); ?>
    <!--[if IE 6]>
    <link href="/resources/css/style_ie6.css" rel="stylesheet" type="text/css" media="screen, print"/>
    <script type="text/javascript" src="/resources/js/pngfix8a-min.js"></script>
    <script type="text/javascript">DD_belatedPNG.fix('.fixpng');</script>
    <![endif]-->
    <?php
    print $_scripts;
    print "\n";
    print $_styles;
    //  include_once(VIEWPATH . "googleanalytics.php");
    ?>
    <style>
        #footer {
            background-position: 0 126px;
        }
    </style>
</head>
<body lang="en">
<div id="container" class="fixpng">
    <div class="wrapper">
        <?php
        $this->load->helper('tbswrapper');
        $this->tbswrapper = new Tbswrapper();
        ?>
        <?php include_once(VIEWPATH . "tbs_header.php") ?>
        <?php print $content ?>
        <?php include_once(VIEWPATH . "tbs_sli_footer.php") ?>
    </div>
</div>
</body>
</html>
