<?php
{
    function htmlsku($prodinfo)
    {

        if ($prodinfo == null) return;
        //<a href='' style='background:url(/images/landing/skuback.gif);width=154px;height=188px;'>&nbsp;</a>
        $youtubeid = "";
        $imagefilename = "imageunavailable_l.jpg";
        if ($prodinfo->get_image() != "") $imagefilename = $prodinfo->get_sku(). "_l." . $prodinfo->get_image();

        //http://usdev.valuebasket.com/mainproduct/view/10022-AA-NA
        $url = base_url() . "mainproduct/view/" . $prodinfo->get_sku();
        $add = base_url() . "cart/add_item_qty/" . $prodinfo->get_sku() . "/1";

        if ($prodinfo->get_youtube_id() != "")
        {
            $youtubelink = "http://www.youtube.com/v/" . $prodinfo->get_youtube_id() . "&fs=1&rel=0&border=1";
            $videoicon = "<a href='$youtubelink' toptions='group = fr, type = flash, effect = show, width = 830, height = 495,  overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})'>
                    <font face='Arial'><b>
                    <img border='0' src='/images/landing/icon-video.png'></b></font>
                    </a>";
            $videotext = "<a href='$youtubelink' toptions='group = fr, type = flash, effect = show, width = 830, height = 495,  overlayClose = 1, layout = quicklook, shaded = 1, title = {alt} ({current} of {total})'>
                    <b><font size='2' color='#8A9398'>play
                    video</font></font></b>
                    </a>";
        }

        return "<table border='0'>
                <tr>
                <td rowspan='6' style='background:url (/images/landing/skuback.gif);padding:0;margin:0;width:154px;height:188px;margin:0;background-repeat:no-repeat;' valign='top'>
                <a href='$url'><img border=0 style='padding-top:10px;padding-left:2px;margin:0px' width=150px  src='/images/product/$imagefilename'></a>
                </td>
                <td rowspan='6'></td>
                <td valign=top height=50px colspan='2'><font face='Arial'><b><font size='2' color='#000000'><a href='$url'> {$prodinfo->get_content_prod_name()}</a></b></font></td>
                </tr>
                <tr>
                <td colspan='2'><font face='Arial'><b><font size='2' color='#8A9398'>from</font></b></font></td>
                </tr>
                <tr>
                <td valign='top' colspan='2'><font face='Arial'><b><font color='#DE328F'>".
                platform_curr_format(PLATFORMID,$prodinfo->get_price())
                ."</font></b></font></td>
                </tr>
                <tr>
                <td colspan='2'>
                <a href='$url' style='background:url(/images/landing/bluebutton.png);display:block;padding-left:15px;padding-top:3px;width:98px;height:34px;margin:0;background-repeat: no-repeat;' valign='top'>
                <font face='Arial' color='#FFFFFF' size='1'>learn more</font></a>
                </td>
                </tr>
                <tr >
                <td colspan='2'>
                <p align='left'>
                <a href='$add' style='background: url(/images/landing/yellowbutton.png);display:block;padding-left:15px;padding-top:3px;width:98px;height:34px;margin:0;background-repeat:no-repeat;' valign='top'>
                <font face='Arial' color='#FFFFFF' size='1'>add to cart</font></a>
                </p>
                </td>
                </tr>
                <tr>
                <td width='21' height='19' valign='center'>
                $videoicon
                </td>

                <td valign='top'><font face='Arial'>
                $videotext
                </td>
                </tr>
                </table>";
    }
}
//background-attachment:fixed;

// <html>
// <head>
// <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
// <title>ValueBasket - Skype Promotion Page</title>
// <link rel="stylesheet" type="text/css" href="/css/tabcontent.css" />
// <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>ValueBasket - Skype Promotion Page</title>
    <link rel="stylesheet" type="text/css" href="/css/tabcontent.css" />

    <script type="text/javascript" src="/js/tabcontent.js">
/***********************************************
* Tab Content script v2.2- Ã‚Â© Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/
    </script>

    <script type="text/javascript" src="<?=base_url()?>js/jquery.js"></script>

    <script src="<?=base_url()?>js/top_up-min.js" type="text/javascript"></script>

</head>
<body bgcolor="#e5e5e">
    <style type="text/css">
body
{
background-image:url('/images/landing/background.png');
background-position:top center;

background-repeat:no-repeat;
}

a {
text-decoration: none;
}
</style>
    <table border="0" width="100%" id="table1">
        <tr>
            <td>
                <p align="center">
                    <img border="0" src="/images/landing/logo.png" width="187" height="54">
            </td>
        </tr>
        <tr>
            <td>
                <p align="center">
                    <b><font face="Arial" size="4"><font color="#8A9398">Get</font> <font color="#0A5BCA">
                        FREE</font> <font color="#01AFEC">60 mins* Skype Calls</font> <font color="#8A9398">
                            with these products</font></font></b>
            </td>
        </tr>
    </table>
    <p align="center">
        &nbsp;</p>
    <div align="center">
        <table border="0" width="871" id="table3">
            <tr>
                <td align="center">
                    <a href="<?=base_url()?>mainproduct/view/<?=$popular->get_sku()?>"><font face="Arial"
                        size="4" color="#C93C39"><b>Plantronics<br>
                            Audio655</b> </font></a>
                    <br>
                    <font color="#8A9398" face="Arial" size="3">Digital USB headset for Skype</font></td>
                <td align="center">
                    <a href="<?=base_url()?>mainproduct/view/<?=$popular->get_sku()?>"><font face="Arial"
                        size="4" color="#FF9300"><b>iTech<br>
                            EasyChat 306</b> </font></a>
                    <br>
                    <font color="#8A9398" face="Arial" size="3">PC and mobile phone wireless<br>headset</font></td>
                <td align="center">
                    <a href="<?=base_url()?>mainproduct/view/10120-AA-NA"><font face="Arial" size="4"
                        color="#8CB820"><b>FaceVsion<br>
                            TouchCam V1 </b></font></a>
                    <br>
                    <font face="Arial" color="#8A9398">High definition VideoCam</font></td>
                <td align="center">
            <a href="<?=base_url()?>mainproduct/view/10035-AA-NA">
            <font face="Arial" size="4"
                        color="#0A5BCA"><b>Yamaha USB Microphone Speaker (PSG-01S)</b></font></a>
                    <br>
                    <font face="Arial" color="#8A9398">High-quality portable speakerphone</font></td>
            </tr>
            <tr>
                <td>
                    <p align="center">
                        <a href="<?=base_url()?>mainproduct/view/10022-AA-NA">
                            <img border="0" src="/images/landing/pdt1.png" width="225" height="250"></a>
                </td>
                <td>
                    <p align="center">
                        <a href="<?=base_url()?>mainproduct/view/10030-AA-NA">
                            <img border="0" src="/images/landing/pdt2.png" width="275" height="256"></a>
                </td>
                <td>
                    <p align="center">
                        <a href="<?=base_url()?>mainproduct/view/10120-AA-NA">
                            <img border="0" src="/images/landing/pdt3.png" width="262" height="286"></a>
                </td>
                <td>
                    <p align="center">
                        <a href="<?=base_url()?>mainproduct/view/10035-AA-NA">
                     <img border="0" src="/images/landing/pdt4.png" width="247" height="248"></a>
                </td>
            </tr>
        </table>
    </div>
    <p align="left">
        &nbsp;</p>
    <div align="center">
        <table border="0" width="871" height="276" id="table20" background="/images/landing/pdtpop.png">
            <tr>
                <td width="299" rowspan="6">
                    <p align="left">
                </td>
                <td colspan="2" valign="bottom">
                    <p align="left">
                        <b><a href="<?=base_url()?>mainproduct/view/<?=$popular->get_sku()?>"><font face="Arial"
                            size="4"><?=$popular->get_content_prod_name();?></font> </a></b>
                </td>
                <td rowspan="6">
                    <table border="0" width="100%">
                        <tr>
                            <td align="left">
                                <object height="150" width="232">
                                    <param value="true" name="allowFullScreen">
                                    <param value="always" name="allowscriptaccess">
                                    <param value="transparent" name="wmode">
<?php // this is not good, HTTPS can run on some other port
// if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
//$secure_connection = true;
// use this instead of the line above
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { ?>
                                    <param value="https://www.youtube.com/watch?v=<?=$popular->get_youtube_id();?>" name="movie">
                                    <embed height="150" width="232" wmode="transparent" allowfullscreen="true" allowscriptaccess="always"
                                        type="application/x-shockwave-flash" src="https://www.youtube.com/v/<?=$popular->get_youtube_id();?>? autoplay=1;fs=1&amp;hl=en_US&amp;rel=0">
<?php } else { ?>
                                <param value="http://www.youtube.com/watch?v=<?=$popular->get_youtube_id();?>" name="movie">
                                <embed height="150" width="232" wmode="transparent" allowfullscreen="true" allowscriptaccess="always"
                                    type="application/x-shockwave-flash" src="http://www.youtube.com/v/<?=$popular->get_youtube_id();?>? autoplay=1;fs=1&amp;hl=en_US&amp;rel=0">
<?php } ?>
</object>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <b><font color="#01AFEC" face="Arial" size="2">Digital USB headset for Skype, music
                                    and gaming<br>
                                </font></b><font face="Arial" size="1" color="#8A9398">Quality digital sound for all
                                    of your PC / Mac audio needs...</font></td>
                        </tr>
                        <tr>
                            <td align="left">
                                <p>
                                    <a href="<?=base_url()?>mainproduct/view/<?=$popular->get_sku()?>" style="background:url(/images/landing/bluebutton.png);
                                        display: block; padding-left: 15px; padding-top: 3px; width: 98px; height: 34px;
                                        margin: 0; background-repeat: no-repeat;" valign="top"><font face="Arial" color="#FFFFFF"
                                            size="1">learn more</font></a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" valign="top">
                    <p align="left">
                        <b><font face="Arial" color="#AD494F"></font></b>
                </td>
            </tr>
            <tr>
                <td colspan="2" valign="top">
                    <p align="left">
                        <b><font color="#01AFEC" face="Arial" size="2">
                            <img border="0" src="/images/landing/arrow.png" width="16" height="16">&nbsp;<?=$popular->get_display_quantity();?>
                            in stock</font></b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p align="left">
                        <font face="Arial"><b><font color="#8A9398">Special Offer: now 20% off!</font></b></font>
                </td>
            </tr>
            <tr>
                <td valign="top" align="left" width="30">
                    <font face="Arial"><b><font size="2" color="#8A9398">from</font></b></font></td>
                <td valign="top">
                    <p align="left">
                        <font face="Arial"><b><font color="#DE328F" size="5">
                <?=platform_curr_format(PLATFORMID,$popular->get_price())?>
                            </font></b></font>
                </td>
            </tr>
            <tr>
                <td colspan="2" valign="top">
                    <p align="left">
            <a href="<?=base_url()?>cart/add_item/<?=$popular->get_sku()?>" style="background: url('/images/landing/yellowbutton.png'); display: block; padding-left: 15px; padding-top: 3px; width: 98px; height: 34px; margin: 0; background-repeat: no-repeat;" valign="top">
            <font face="Arial" color="#FFFFFF" size="1">add to cart</font></a>
                </td>
            </tr>
        </table>
    </div>
    <div align="center">
        <table>
            <tr>
                <td>
                    <ul id="cattabs" class="shadetabs">
<?php
$i = 0;
foreach($cat_list as $key=>$cat)
{
    // create the tabs here
    if (strtoupper($key) == "SMARTPHONES") $key = "phones";

    echo "<li ><a href='#' style='padding:5px;' rel='$key'";
    if ($i == 0)
    {
        echo "class='selected'";
        $i++;
    }
    echo "><font face='Arial' color='#FFFFFF' size='2'>&nbsp;&nbsp;&nbsp;". strtoupper($key) . "&nbsp;&nbsp;&nbsp;</font></a></li>";
}
?>
                    </ul>
<?php
$i = 0;
foreach($cat_list as $key=>$cat)
{
    $display = $key;
    if (strtoupper($key) == "SMARTPHONES") $display = "phones";
//  $cat[5] = null;
    echo "<div id='$display' class='tabcontent'>";
?>
                        <table width="862px" style="background: url(/images/landing/tableback.png); width=862px;
                            height=548px;" border='0'>
                            <tr>
                                <td colspan=3 align=right>
<a href='<?=base_url();?>search/?search=&from=c&filter=skypecert&catid=&limit=&total=14&sort=&cat=<?=$key?>&brand=&min=&max=&page=1&displayqty'>
<font color="#01AFEC" face="Arial" size="2">See all <?=$display?></font></a>
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <?=htmlsku($cat[0])?>
                                </td>
                                <td valign="top">
                                    <?=htmlsku($cat[1])?>
                                </td>
                                <td valign="top">
                                    <?=htmlsku($cat[2])?>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <?=htmlsku($cat[3])?>
                                </td>
                                <td valign="top">
                                    <?=htmlsku($cat[4])?>
                                </td>
                                <td valign="top">
                                    <?=htmlsku($cat[5])?>
                                </td>
                            </tr>
                        </table>
                    </div>
<?php } ?>
                </td>
            </tr>
    </div>

    <script type="text/javascript">
var countries=new ddtabcontent("cattabs")
countries.setpersist(true)
countries.setselectedClassTarget("link") //"link" or "linkparent"
countries.init()
    </script>

    <p>
        &nbsp;</p>
    <div align="center">
        <table border="0" width="871" id="table2">
<tr>
<td>
<font color="#01AFEC" face="Arial" size="2">
Want to see more products with this offer,
<a href='<?=base_url();?>search/?from=c&filter=skypecert'>click here</a>
</font>
</td>
</tr>
            <tr>
                <td>
                    <br>
                    <br>
                    <b><font color="#01AFEC" face="Arial" size="2">Terms and Conditions</font></b><br>
                    <font face="Arial" size="1" color="#8A9398">The "Up to 60 Minutes Free Credit from Skype"
                        Offer consists of an "up to 60 Minutes Free Skype Credit" voucher from Skype. The
                        Promoter - ValueBasket is not responsible for any Skype products or services including
                        call rates. 60 total minutes is based on a single call to a landline at the estimated
                        rate of 0.02 per minute as controlled by Skype. The actual number of minutes Participants
                        can redeem with this offer may vary based on the calling destination and whether
                        the Participant is calling landline or mobile phones. A connection fee applies to
                        all calls. More details on call rates can be viewed here.</font>
                    <br>
                    <br>
                </td>
            </tr>
        </table>
    </div>
<?php
//var_dump($cat_list);
//var_dump($popular);
?>
</body>
</html>
