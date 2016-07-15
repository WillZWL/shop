<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<link rel="stylesheet" href="<?=base_url()?>css/imgUpload.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="//code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/webuploader.html5only.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/imgUpload.js"></script>
<script type="text/javascript" src="<?= base_url() ?>marketing/category/js_catlist"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<style type="text/css">
.content {
    width: 1000px;
    margin: 0 auto;
    padding: 10px;
    text-align: left;
}
.image {
    width: 980px;
}

</style>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="400" align="right" class="title">
        </td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <tr>
        <td height="70" style="padding-left:8px">
            <b style="font-size:14px"><?=$lang["header"]?></b><br>
            <?=$lang["header_message"]?><br>
            </td>
    </tr>
</table>
<form name="fm" method="get">
    <table cellpadding="0" cellspacing="0" width="100%" class="page_header" border="0">
        <!-- <col width="100"><col width="100"><col width="500"> -->
        <tr>
            <td class="" align="center" style="margin: 10px; padding: 10px 0px; width:180px; ">
                <b><?=$lang['platform']?>:</b> &nbsp;&nbsp;
                <select onChange="Redirect('<?=base_url()?>marketing/BannerManagement/index/'+this.value)">
                    <option value="">Select Platform</option>
                    <?php
                    $sp_selected[$platform_id] = " SELECTED";
                    foreach ($platform_list as $platform) {
                        $id = $platform->getSellingPlatformId();
                    ?>
                    <option value="<?=$id?>" <?=$sp_selected[$id]?>><?=$id?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
            <td class="" align="center" style="margin: 10px; padding: 10px 0px;width:240px;">
                <b><?=$lang['banner_type']?>:</b> &nbsp;&nbsp;
                <select onChange="Redirect('<?=base_url()?>marketing/BannerManagement/index/<?=$platform_id?>/'+this.value)">
                    <option value="">Select Type</option>
                    <?php
                    if ($platform_id) {
                        $ty_selected[$type] = " SELECTED";
                        foreach ($type_list as $key => $value) {
                    ?>
                        <option value="<?=$key?>" <?=$ty_selected[$key]?>><?=$value?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="" align="left" style="margin: 20px; padding: 10px 0px;width:60px; ">
                <b><?=$lang['location']?>:</b> &nbsp;&nbsp;
            </td>
            <?php
                if ($type == 1) {
            ?>
            <td>
            <select name="location" onChange="Redirect('<?=base_url()?>marketing/BannerManagement/index/<?=$platform_id?>/<?=$type?>/'+this.value)">
                <option value="">Select Location</option>

                <?php
                    $lo_selected[$location] = " SELECTED";
                    foreach ($home_location_list as $key => $value) {
                ?>
                <option value="<?=$key?>" <?=$lo_selected[$key]?>><?=$value?></option>
                <?php
                    }
                ?>
            </select>
            </td>
            <?php
                } else if ($type == 2) {
            ?>
                <td><b><?=$lang['category']?>:&nbsp;&nbsp;</b><select name="cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_cat_id, this.form.sub_sub_cat_id)">
                    <option value="">
                </select></td>
                <td><b><?=$lang['sub_cat']?>:&nbsp;&nbsp;</b><select name="sub_cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_sub_cat_id)">
                    <option value="">
                </select></td>
                <td><b><?=$lang['sub_sub_cat']?>:&nbsp;&nbsp;</b><select name="sub_sub_cat_id" class="input">
                    <option value="">
                </select></td>
            <?php
                } else if ($type == 3) {
            ?>
                <td><b>Input SKU: &nbsp;&nbsp;</b><input type="text" name="location" class="input" style="width:200px;"></td>
            <?php
                }
            ?>
            <td></td>
        </tr>
    </table>
</form>
    <div class="content">
        <h2>Upload Image For: &nbsp;&nbsp;<?=$breadcrumb?></h2>
        <?php
            if ($banner_list) {
                foreach ($banner_list as $banner_obj) {
                    $image = $banner_obj->getImage();
        ?>
        <div class="parentFileBox">
            <ul class="fileBoxUl" id="sortable">
                <li id="fileBox_WU_FILE_0" class="imgUploadHover">
                    <div class="viewThumb">
                        <img src="<?=base_url($image)?>">
                    </div>
                    <div class="imgCancel"></div>
                    <div class="imgSuccess"></div>
                    <div class="imgFileName">en_banner2 copy.jpg</div>
                    <div class="imgBar">
                        <div class="imgProgress"></div>
                        <div class="imgProgressText">0%</div>
                    </div>
                    <div class="imgInfo">
                        <p><b>Target Url:</b>
                            <input type="text" name="link" class="input">
                        </p>
                        <p><b>Target Type: </b>
                            <select name="target_type" class="target_type">
                                <option value="2">open in same window</option>
                                <option value="1">open in new window</option>
                            </select>
                        </p>
                        <p><b>Image Alt: </b>
                            <input type="text" name="image_alt" class="input">
                        </p>
                    </div>
                </li>
            </ul>
        </div>
        <?php
                }
            }
        ?>

        <div id="image">

        </div>
    </div>
</div>
<script language='javascript'>
$(function() {
    $( '#image' ).imgUpload({
        url:'<?=base_url()?>marketing/BannerManagement/handle',
        success:function( data ) {
            console.info( data );
        },
        error:function( err ) {
            console.info( err );
        },
        fileNumLimit:5-<?=$nums?>,
        thumb:{
            width:550,
            height:131,
        },
        formData:{
            platform_id: '<?=$platform_id?>',
            banner_type: '<?=$type?>',
            location:'<?=$location?>'
        },
    });
});

<?php
if ($type == 2) {
?>
    ChangeCat('0', document.fm.cat_id);
    document.fm.cat_id.value = '<?=$this->input->get("cat_id")?>';
    ChangeCat('<?=$this->input->get("cat_id")?>', document.fm.sub_cat_id);
    document.fm.sub_cat_id.value = '<?=$this->input->get("sub_cat_id")?>';
    ChangeCat('<?=$this->input->get("sub_cat_id")?>', document.fm.sub_sub_cat_id);
    document.fm.sub_sub_cat_id.value = '<?=$this->input->get("sub_sub_cat_id")?>';
<?php
}
?>
</script>
</body>
</html>