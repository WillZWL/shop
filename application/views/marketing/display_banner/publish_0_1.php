<script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/kandytabs.pack.js"></script>
<script src="/js/AC_OETags.js" type="text/javascript" language="javascript"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/kandytabs.css">
<script language="JavaScript" type="text/javascript">
    <!--
    var requiredMajorVersion = 10;// Major version of Flash required
    var requiredMinorVersion = 0;// Minor version of Flash required
    var requiredRevision = 0;// Minor version of Flash required
    // -->
</script>
<style type="text/css">

    #imgslide_0_1 {
        width: 684px;
        margin: 0 0 0px !important;
        float: left;
        text-align: center;
    }

    #imgslide_0_1 .tabbody {
        position: relative;
        z-index: 2
    }

    #imgslide_0_1 .tabprocess {
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
    if ($link_type[0] == "E") {
        $type = "_blank";
    } else {
        $type = "";
    }

    if ($link) {
        ?>
        <div id="imgslide_<?= $publish_key ?>" style="width:<?= $banner_width ?>px;height:<?= $banner_height ?>px;">
        <a href="<?= $result_link ?>" target="<?= $type ?>">
    <?php
    }
    ?>
    <img width="<?= $banner_width ?>" height="<?= $banner_height ?>" border="0" src="<?= $graphic[0] ?>">
    <?php
    if ($link) {
        ?>
        </a>
        </div>
    <?php
    }
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
