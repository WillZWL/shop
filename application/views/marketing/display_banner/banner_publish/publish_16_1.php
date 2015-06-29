<script type="text/javascript">
    if (typeof LyteboxAutoLoad == "undefined") {
        document.write('<scr' + 'ipt type="text/javascript" src="/js/lytebox_cv.min.js" ></scr' + 'ipt>');
    }
</script>
<script src="/js/AC_OETags.js" type="text/javascript" language="javascript"></script>
<script language="JavaScript" type="text/javascript">
    <!--
    var requiredMajorVersion = 10;// Major version of Flash required
    var requiredMinorVersion = 0;// Minor version of Flash required
    var requiredRevision = 0;// Minor version of Flash required
    // -->
</script>
<style type="text/css">

    #imgslide_11_1 {
        width: 534px;
        margin: 0 0 0px !important;
        float: left;
        text-align: center;
    }

    #imgslide_11_1 .tabbody {
        position: relative;
        z-index: 2
    }

    #imgslide_11_1 .tabprocess {
        position: absolute;
        width: 496px;
        height: 176px;
        z-index: 0;
        left: 0;
        top: 0;
        background: #F60;
    }

    .kandySlide .tabtitle {
        right: 16px;
    }

</style>
<?php
$publish_key = $banner["publish_key"];
$banner_type = $banner["banner_type"];
$banner_width = $banner["banner_width"];
$banner_height = $banner["banner_height"];
$time_interval = $banner["time_interval"];
$backup_link = $banner["backup_link"];
$backup_link_type = $banner["backup_link_type"];
$backup_graphic = $banner["backup_graphic"];
$redirect_link = $banner["redirect_link"];
$link_type = $banner["link_type"];
$graphic = $banner["graphic"];
$num = $banner["num"];

if ($banner_type == "R") {
    ?>
    <ol id="imgslide_<?= $publish_key ?>" style="width:<?= $banner_width ?>px;height:<?= $banner_height ?>px;">
        <?php
        for ($i = 0; $i < $num; $i++) {
            $result_link = '';
            $link = $redirect_link[$i];
            $pos = strpos($link, 'http');
            if ($pos === false) {
                $result_link = base_url() . $link;
            } else {
                $result_link = $link;
            }
            if ($link_type[$i] == "E") {
                $type = "_blank";
            } else {
                $type = "";
            }
            ?>
            <li>
                <?php
                if ($link)
                {
                ?>
                <a href="<?= $result_link ?>" target="<?= $type ?>">
                    <?php
                    }
                    ?>
                    <img width="<?= $banner_width ?>" height="<?= $banner_height ?>" border="0"
                         src="<?= $graphic[$i] ?>">
                    <?php
                    if ($link)
                    {
                    ?>
                </a>
            <?php
            }
            ?>
            </li>
        <?php
        }
        ?>
    </ol>
    <script type="text/javascript">
        $("#imgslide_<?=$publish_key?>").KandyTabs({
            classes: "kandySlide",
            action: "slifade",
            stall:<?=$time_interval?>,
            type: "slide",
            auto: true,
            process: false,
            direct: "left"
        });
    </script>
<?php
} elseif ($banner_type == "I") {
    $result_link = '';
    $link = $redirect_link[0];
    $pos = strpos($link, 'http');
    if ($pos === false) {
        $result_link = base_url() . $link;
    } else {
        $result_link = $link;
    }
    if ($link_type[0] == "L") {
        if (file_exists(VIEWPATH . "banner/lytebox_" . $publish_key . ".php") == FALSE) {
            ?>
            <a href="<?= "http://" . $lytebox_country_id . ".chatandvision.com/header/lytebox/" . $publish_key ?>"
               onclick="return false;" rel="lyteframe[header]"
               rev="width: 620px; height:410px; scrolling: auto;padding: 40px;"
               title="<?= $lang["lytebox_title"] ?>"><img width="<?= $banner_width ?>" height="<?= $banner_height ?>"
                                                          border="0" src="<?= $graphic[0] ?>"></a>
        <?php
        } else {
            ?>
            <link rel="stylesheet" href="/css/lytebox.css" type="text/css" media="screen"/>
            <a href="<?= base_url() . "header/lytebox/" . $publish_key ?>" onclick="return false;"
               rel="lyteframe[header]" rev="width: 620px; height:410px; scrolling: auto;padding: 40px;"
               title="<?= $lang["lytebox_title"] ?>"><img width="<?= $banner_width ?>" height="<?= $banner_height ?>"
                                                          border="0" src="<?= $graphic[sizeof($graphic) - 1] ?>"></a>
        <?php
        }
    } else {
        if ($link_type[0] == "E") {
            $type = "_blank";
        } else {
            $type = "";
        }
        if ($link) {
            ?>
            <a href="<?= $result_link ?>" target="<?= $type ?>">
        <?php
        }
        ?>
        <img width="<?= $banner_width ?>" height="<?= $banner_height ?>" border="0" src="<?= $graphic[0] ?>">
        <?php
        if ($link) {
            ?>
            </a>
        <?php
        }
    }
    ?>
<?php
} elseif ($banner_type == "F") {
    if ($backup_link_type == "E") {
        $type = "_blank";
    } else {
        $type = "";
    }
    ?>
    <script language="JavaScript" type="text/javascript">
        <!--
        var hasReqestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
        if (hasReqestedVersion) {
            AC_FL_RunContent(
                "src", "<?=$graphic[0]?>",
                "width", "<?=$banner_width?>",
                "height", "<?=$banner_height?>",
                "align", "middle", "id", "detectionExample", "quality", "high", "bgcolor", "#FFFFFF", "name", "detection", "allowScriptAccess", "sameDomain", "type", "application/x-shockwave-flash",
                "wmode", "transparent", 'codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab', "pluginspage", "http://www.adobe.com/go/getflashplayer"
            );
        } else {
            var alternateContent = "<a href=\"<?=$backup_link[0]?>\" target=\"<?=$type?>\"><img width=\"<?=$banner_width?>\" height=\"<?=$banner_height?>\" border=\"0\" src=\"<?=$backup_graphic?>\"></a>";
            document.write(alternateContent);
        }
        // -->
    </script>
<?php
}
?>
