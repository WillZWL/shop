<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script>
        var ajax = createAjaxObject();
    </script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/lytebox.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/lytebox.css" type="text/css" media="screen"/>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="650" align="right" class="title">
                <!-- <input type="button" value="Latest Arrival" class="button" onClick="Redirect('<?= site_url('order/special_order/pending') ?>')"> -->
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b style="font-size:14px"><?= $lang["header"] ?></b><br>
                <?= $lang["header_message"] ?>
            </td>
            <td align="right" style="padding-right:8px">
                <?= $lang["selling_platform"] ?>:
                <select style="width:250px" onChange="Redirect('<?= base_url() ?>marketing/<?= $handle ?>/index/'+this.value)">
                    <option></option>
                    <?php
                    $sp_selected[$platform_id] = " SELECTED";
                    foreach ($selling_platform as $obj) {
                        $id = $obj->getSellingPlatformId();
                    ?>
                        <option value="<?=$id ?>"<?= $sp_selected[$id] ?>><?=$obj->getName(); ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <form name="fm" method="post" action="<?= base_url() ?>marketing/<?= $handle ?>/update">
        <input type="hidden" name="platform_id" value="<?=$platform_id?>">
        <input type="hidden" name="catid" value="<?=$catid?>">
        <table border="0" cellpadding="0" cellspacing="1" bgcolor="#000000" width="100%" class="tb_list">
            <col width="20">
            <col width="80">
            <col width="80">
            <col width="400">
            <col width="80">
            <col width="20">
            <tr class="header">
                <td>Sort</td>
                <td>SKu</td>
                <td>Master Sku</td>
                <td>Name</td>
                <td>Mode</td>
                <td></td>
            </tr>
        <?php
            if ($platform_id) {
                 for ($i = 0; $i < $limit; $i++) {
                    if ($seller_list[$i]) {
                       $seller_obj = $seller_list[$i];
                       $sku = $seller_obj->getSku();
                       $master_sku = $seller_obj->getMasterSku();
                       $name = $seller_obj->getName();
                       $mode = $seller_obj->getMode();
                    } else {
                        $sku = $master_sku = $name = $mode = '';
                    }
        ?>
            <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')">
                <input type="hidden" name="item[<?= $i ?>][rank]" value="<?=($i+1);?>">
                <td align="center"><?=($i+1);?></td>
                <td>
                    <input type="text" name="item[<?= $i ?>][sku]" value="<?=$sku?>" readonly>
                </td>
                <td>
                    <input type="text" name="item[<?= $i ?>][master_sku]" value="<?=$master_sku?>" disabled>
                </td>
                <td>
                    <input type="text" name="item[<?= $i ?>][name]" value="<?=$name?>" style="width:100%" disabled>
                </td>
                <td>
                    <?php
                    $m_select = [];
                    if ($mode) {
                        $m_select[$mode] = "SELECTED";
                    }
                    ?>
                    <select name="item[<?= $i ?>][mode]">
                        <option value=""></option>
                        <option value="A" <?=$m_select['A']?>>A</option>
                        <option value="M" <?=$m_select['M']?>>M</option>
                    </select>
                </td>
                <td>
                    <a href="<?= base_url() ?>marketing/<?= $handle?>/prodList/<?= $i ?>/<?= $platform_id ?>"
                        rel="lyteframe" rev="width: 1024px; height: 400px; scrolling: auto;" title="Select Product for <?= $handle ?>"
                        class="search_button" style="background: url('<?= base_url() ?>images/find.gif') no-repeat;">&nbsp; &nbsp; &nbsp;
                    </a>
                </td>
            </tr>
        <?php
                }
            }
        ?>
        </table>
        <p style="float: right;margin-right: 40px;">
            <input type="submit" value="Submit" style="font-size: 18px;">
        </p>
    </form>
</div>
<?= $notice["js"] ?>

<script type="text/javascript">
    function additem(str, line) {
        var fm = document.fm;
        prod = fetch_params('?' + str);
        fm.elements["item[" + line + "][sku]"].value = prod["sku"];
        if (prod['master_sku']) {
            fm.elements["item[" + line + "][master_sku]"].value = prod["master_sku"];
        };
        fm.elements["item[" + line + "][name]"].value = prod["name"];
    }
</script>
</body>
</html>