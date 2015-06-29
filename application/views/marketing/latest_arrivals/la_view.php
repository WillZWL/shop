<html>
<head>
    <link media="all" type="text/css" href="<?= base_url() ?>css/style.css" rel="stylesheet">
    <script src="<?= base_url() ?>js/common.js" type="text/javascript"></script>
</head>
<body topmargin="0" leftmargin="0"
      onResize="SetFrameFullHeight(document.getElementById('left'));SetFrameFullHeight(document.getElementById('right'));">
<div id="main">
    <iframe src="<?= base_url() ?>marketing/latest_arrivals/view_left/?cat=<?= $catid ?>&level=<?= $level ?>" noresize
            frameborder="0" name="left" id="left" marginwidth="0" marginheight="0" hspace="0" vspace="0" width="200"
            height="450" style="float:left; border-right:1px solid #000000;" onLoad="SetFrameFullHeight(this)"></iframe>
    <iframe src="<?= base_url() ?>marketing/latest_arrivals/view_right/<?= $catid ?>" noresize frameborder="0"
            name="right" id="right" marginwidth="0" marginheight="0" hspace="0" vspace="0" width="1059" height="450"
            style="float:left; " onLoad="SetFrameFullHeight(this)"></iframe>
</div>
</body>
</html>
