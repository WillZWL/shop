<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="<?= base_url() . "css/style.css" ?>">
    <style type="text/css">
        .level1 {
            width: 100%;
            text-align: left;
            padding-top: 8px;
            padding-left: 8px;
        }

        .level2 {
            width: 100%;
            text-align: left;
            padding-top: 8px;
            padding-left: 8px;
        }

        .level3 {
            width: 100%;
            text-align: left;
            padding-top: 8px;
            padding-left: 8px;
        }

        a {
            color: #000000;
            text-decoration: none;
        }

        a.vlink {
            color: #0000CC;
        }
    </style>
    <script type="text/javascript">
        <!--
        function showNextLayer(display_id, id, level, htmlid) {
            var xmlhttp = GetXmlHttpObject();
            var tlink = 'a' + id;
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4) {
                    document.getElementById(htmlid).innerHTML += xmlhttp.responseText;
                    if (document.getElementById('a' + id)) {
                        document.getElementById('a' + id).innerHTML = '<a href="#"' + ' onClick="remove(' + "'" + id + "'" + ')">-</a>';
                    }
                }
            }
            url = '<?=base_url()?>marketing/display_banner/getnext/?id=' + id + '&level=' + level + '&display_id=' + display_id;
            xmlhttp.open("GET", url, true);
            xmlhttp.send(null);
        }

        function remove(id) {

            var hide = 'na' + id;
            document.getElementById(hide).style.display = 'none';
            var show = 'a' + id;
            document.getElementById(show).innerHTML = '<a href="#" onClick="show(' + "'" + id + "'" + ')">+</a>';
        }

        function show(id) {
            var show = 'na' + id;
            document.getElementById(show).style.display = 'block';
            var hide = 'a' + id;
            document.getElementById(hide).innerHTML = '<a href="#" onClick="remove(' + "'" + id + "'" + ')">-</a>';
        }

        function GetXmlHttpObject() {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                return new XMLHttpRequest();
            }
            if (window.ActiveXObject) {
                // code for IE6, IE5
                return new ActiveXObject("Microsoft.XMLHTTP");
            }
            return null;
        }
        -->
    </script>
</head>
<body class="frame_left" style="width: auto;">
<div id="main" style="width: auto;">
    <div id="main" style="width:auto; text-align:left; padding-left:4px;">
        <?php
        foreach ($objlist as $obj) {
            if ($obj->get_id() == '17' && $obj->get_name() == 'category') {
                ?>
                <div id="main" style="width:auto; text-align:left; padding-left:0px;">
                    <div class="level1"><a href="#" class="vlink"><?= $obj->get_display_name() ?></a></div>
                    <?php
                    foreach ($catlist as $cat_obj) {
                        if ($cat_obj->get_count_row() > 0) {
                            $link = "<span id=\"a" . $cat_obj->get_id() . "\"><a href=\"javascript:showNextLayer('" . $obj->get_id() . "','" . $cat_obj->get_id() . "','" . ($level + 1) . "','c" . $cat_obj->get_id() . "')\">+</a></span>&nbsp;&nbsp;";
                        } else {
                            $link = "&nbsp;&nbsp;&nbsp;";
                        }
                        $name = "<a href=\"" . base_url() . "marketing/display_banner/view/" . $obj->get_id() . "/?catid=" . $cat_obj->get_id() . "\" target=\"cview\" class=\"vlink\">" . $cat_obj->get_name() . "</a>";

                        if (!$cat_obj->get_status()) {

                            $style = "inactive";
                        } else {
                            if ($cat_obj->get_pv_cnt()) {

                                $style = "pv";
                            }
                            if ($cat_obj->get_pb_cnt()) {

                                $style = "pb";
                            }
                        }
                        ?>
                        <div id="c<?= $cat_obj->get_id() ?>" class="level1 <?= $style ?>"><?= $link . $name ?></div>
                    <?php
                    }
                    ?>
                </div>
            <?php
            } else {
                $name = "<a href=\"" . base_url() . "marketing/display_banner/view/" . $obj->get_id() . "\" target=\"cview\" class=\"vlink\">" . $obj->get_display_name() . "</a>";
                ?>
                <div id="c<?= $obj->get_id() ?>" class="level1 <?= $style ?>"><?= $link . $name ?></div>
            <?php
            }
        }
        ?>
    </div>
</div>
</body>
</html>