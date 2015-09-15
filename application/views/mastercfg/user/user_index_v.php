<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('mastercfg/user/') ?>')">
                &nbsp; <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                              onclick="Redirect('<?= site_url('mastercfg/user/add/') ?>')"></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
            <td width="200" valign="top" align="right" style="padding-right:8px"><br><?= $lang["staff_found"] ?>
                <b><?= $total ?></b><br><br>
                <?php
                if ($showall) {
                    ?>
                    <input type="button" name="showall" value="<?= $lang["show_active_staff"] ?>" class="button"
                           onclick="Redirect('<?= site_url('mastercfg/user/') ?>')">
                <?php
                } else {
                    ?>
                    <input type="button" name="showall" value="<?= $lang["show_all_staff"] ?>" class="button"
                           onclick="Redirect('<?= site_url('mastercfg/user/?showall=1') ?>')">
                <?php
                }
                ?>
            </td>
        </tr>
    </table>
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="140">
            <col width="200">
            <col width="260">
            <col>
            <col width="26">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'id', '<?= $xsort["id"] ?>')"><?= $lang["login_name"] ?> <?= $sortimg["id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'username', '<?= @$xsort["username"] ?>')"><?= $lang["name"] ?> <?= @$sortimg["username"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'email', '<?= @$xsort["email"] ?>')"><?= $lang["email"] ?> <?= @$sortimg["email"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'roles', '<?= @$xsort["roles"] ?>')"><?= $lang["roles"] ?> <?= @$sortimg["roles"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="id" class="input" value="<?= htmlspecialchars($this->input->get("id")) ?>"></td>
                <td><input name="username" class="input" value="<?= htmlspecialchars($this->input->get("username")) ?>">
                </td>
                <td><input name="email" class="input" value="<?= htmlspecialchars($this->input->get("email")) ?>"></td>
                <td><input name="roles" class="input" value="<?= htmlspecialchars($this->input->get("roles")) ?>"></td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <?php
            $i = 0;
            if (!empty($userlist)) {
                foreach ($userlist as $user) {
                    ?>

                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="Redirect('<?= site_url('mastercfg/user/view/' . $user->getId()) ?>')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $user->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $user->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $user->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $user->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $user->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $user->getModifyBy() ?>'>
                        </td>
                        <td><?= $user->getId() ?></td>
                        <td><?= $user->getUsername() ?></td>
                        <td><?= $user->getEmail() ?></td>
                        <td><?= $user->getRoles() ?></td>
                        <td align="center"><input type="button" value="x" class="x_button"
                                                  onClick="event.cancelBubble=true;Redirect('<?= site_url('mastercfg/user/delete/' . $user->getId()) ?>')"
                                                  onClick=""></td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
        </table>
        <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>