<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php
    print $_title;
    print "\n";
    ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php
    print $_meta;
    ?>

    <link rel="SHORTCUT ICON" href="/resources/images/favicon.ico" />
    <meta name="google-site-verification" content="dNy_bmj2yB06qKS_JqKlgoW0cUlkw_90TDhnb9NAykQ" />
    <meta name="google-site-verification" content="YFBjuXQNRUSiIlPfh0eIgZqlWY3DIntvUojbdssDCKA" />
    <meta name="google-site-verification" content="KbToa9UsKRjSymT5-eGtGEDXS6-dVBS7_OzfxEbb9pI" />
    <meta name="google-site-verification" content="GEm3rCjYudif7S_vAUGkXHjJS5WzU5tBorHfvNOEvkI" />
    <meta name="google-site-verification" content="lH08EJBYv8VnZUYEgu51ST_wlXnthPNqG46feSqK1rA" />

    <script type="text/javascript" src="/resources/js/jquery.js"></script>
    <script type="text/javascript" src="/resources/js/jquery.selectbox.js"></script>
    <script type="text/javascript" src="/resources/js/jquery.fancybox-1.3.4.js"></script>
    <script type="text/javascript" src="/resources/js/default.js" lang="<?=get_lang_id()?>"></script>
    <script type="text/javascript" src="/resources/js/jquery.dd.min.js"></script>


    <link href="/resources/css/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/resources/css/dd.css" rel="stylesheet" type="text/css" media="screen" />

    <!-- load fonts -->
    <link href="<?=((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http")?>://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css" />
    <link href="<?=((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http")?>://fonts.googleapis.com/css?family=Rokkitt:400,700" rel="stylesheet" type="text/css" />
<!--
    <link href="/resources/css/reset.css" rel="stylesheet" type="text/css" media="screen, print" />
    <link href="/resources/css/selectbox.css" rel="stylesheet" type="text/css" media="screen, print" />
    <link href="/resources/css/style.css" rel="stylesheet" type="text/css" media="screen, print" />
    <link href="/resources/css/print.css" rel="stylesheet" type="text/css" media="print" />
-->
    <?php print add_css_helper('resources/css/reset');?>
    <?php print add_css_helper('resources/css/selectbox');?>
    <?php print add_css_helper('resources/css/style');?>
    <?php print add_css_helper('resources/css/print', FALSE, 'print');?>

    <!--[if IE 6]>
        <link href="/resources/css/style_ie6.css" rel="stylesheet" type="text/css" media="screen, print" />
        <script type="text/javascript" src="/resources/js/pngfix8a-min.js"></script>
        <script type="text/javascript">DD_belatedPNG.fix('.fixpng');</script>
    <![endif]-->
    <?php
    print $_scripts;
    print "\n";
    print $_styles;
//  include_once(VIEWPATH . "googleanalytics.php");
    ?>
<?php   print $_third_party_script_in_head; ?>
</head>
<body lang="<?=get_lang_id()?>">
<?php
if (PLATFORMCOUNTRYID == "RU")
{
    print "<script type=\"text/javascript\">var isAllowLatin=true;</script>";
}
?>
<?php   print $_bodyscripts; ?>
    <div id="container" class="fixpng">
        <div class="wrapper">
            <?php
            $this->load->helper('tbswrapper');
            $this->tbswrapper = new Tbswrapper();
            ?>
            <?php include_once(VIEWPATH . "tbs_header.php") ?>
            <?php print $content ?>
            <!-- insert get_common_code here -->
            <?php include_once(VIEWPATH . "tbs_footer.php") ?>
        </div>
    </div>
    <?php
        # SBF 2220 Tradedoubler multipage tracking code fixed portion
        $td = new Tradedoubler_tracking_script_service();
        $td->set_country_id(PLATFORMCOUNTRYID);
        echo $td->get_fixed_code();

        # SBF 2247 Adroll multipage tracking code fixed portion
        $td = new Adroll_tracking_script_service();
        $td->set_country_id(PLATFORMCOUNTRYID);
        echo $td->get_fixed_code();


        #if(is_file('tags/google_remarketing_'.strtolower(PLATFORMCOUNTRYID).'.php'))
            #include('tags/google_remarketing_'.strtolower(PLATFORMCOUNTRYID).'.php');
//      var_dump($this->router->class);
//      var_dump($this->router->method);
//      var_dump($this->router);
    ?>
    </body>
<script type="text/javascript">
    jQuery(document).ready(function()
    {
<?php
if (PLATFORMCOUNTRYID == 'AU')
{
?>
        var hardCodeLink = "/display/promotions/audio-visual";
        jQuery('#nav-audio').unbind("mouseover");
        jQuery('#nav-audio').find('a').attr("href", hardCodeLink);
        var allLi = jQuery('#footer').find("li");

        jQuery.each(jQuery('#footer').find("li"), function(key, li)
        {
            if (jQuery(this).find("a").attr("title") == "Audio & Visual")
            {
                jQuery(this).find("a").attr("href", hardCodeLink);
            }
        });
<?php
}
?>

<?php
if ((PLATFORMCOUNTRYID == 'AU')
    || (PLATFORMCOUNTRYID == 'NZ')
    || (PLATFORMCOUNTRYID == 'SG')
    || (PLATFORMCOUNTRYID == 'MY')
    || (PLATFORMCOUNTRYID == 'US')
    || (PLATFORMCOUNTRYID == 'GB')
    || (PLATFORMCOUNTRYID == 'BE')
    || (PLATFORMCOUNTRYID == 'ES')
    || (PLATFORMCOUNTRYID == 'FR')
    || (PLATFORMCOUNTRYID == 'PT')
    )
{
?>
        var droneHardCodeLink = "";
        droneHardCodeLink = "/<?php print get_lang_id() . "_" . PLATFORMCOUNTRYID;?>";
        droneHardCodeLink += "/display/promotions/drone";
//      var theHref = jQuery('.nav-warranty').find('a').attr("href", hardCodeLink);

        jQuery.each(jQuery('.nav-warranty').find('a'), function(key, li)
        {
            if ((jQuery(this).attr("title") == "Gadgets & Toys")
                || (jQuery(this).attr("title") == "Gadgets & Jouets")
                || (jQuery(this).attr("title") == "Gadgets & Jouets")
                || (jQuery(this).attr("title") == "Drones y Gadgets")
                || (jQuery(this).attr("title") == "Gadgets & Juguetes"))
            {
                jQuery(this).attr("href", droneHardCodeLink);
            }
        });
<?php
}
?>
    });
</script>
</html>