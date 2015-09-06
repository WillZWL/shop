<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/picklist.js"></script>
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
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
        </tr>
    </table>
    <form name="fm" method="post" onSubmit="return CheckForm(this);">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
            <col width="160">
            <col width="260">
            <col width="160">
            <col width="260">
            <col width="160">
            <col>
            <tr class="header">
                <td height="20" colspan="6"><?= $lang["table_header"] ?></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["login_name"] ?></td>
                <td class="value"><input name="id" class="input" <?=$cmd != "add" ? 'type="hidden"' : ''?> value="<?= htmlspecialchars($user->getId()) ?>" noSpecial><?= $user->getId() ?></td>
                <td class="field"><?= $lang["name"] ?></td>
                <td class="value"><input name="username" class="input"
                                         value="<?= htmlspecialchars($user->getUsername()) ?>" notEmpty></td>
                <td class="field"><?= $lang["email"] ?></td>
                <td class="value"><input name="email" class="input" value="<?= htmlspecialchars($user->getEmail()) ?>"
                                         notEmpty validEmail></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["password"] ?></td>
                <td class="value"><input type="password" name="password"
                                         class="input" <?= $cmd == "add" ? "notEmpty" : "" ?> minLen="5"></td>
                <td class="field"><?= $lang["confirm_password"] ?></td>
                <td class="value"><input type="password" name="confirm_password"
                                         class="input" <?= $cmd == "add" ? "notEmpty" : "" ?> match="password"></td>
                <td class="field"><?= $lang["status"] ?></td>
                <td class="value">
                    <?php
                    $selected[$user->getStatus()] = "SELECTED";
                    ?>
                    <select name="status" class="input">
                        <option value="1"><?= $lang["active"] ?>
                        <option value="0" <?= $selected[0] ?>><?= $lang["inactive"] ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field"><?= $lang["roles"] ?></td>
                <td class="value" colspan="5">
                    <table border="0" cellpadding="0" cellspacing="0" class="tb_noborder">
                        <tr>
                            <td align="center"><?= $lang["existing_roles"] ?><br>
                                <select name="full_list[]" multiple='multiple' class="multi_select">
                                    <?php
                                    foreach ($role_list as $role)
                                    {
                                    ?>
                                    <option value="<?= $role->getId() ?>"><?= $role->getRoleName() ?>
                                        <?php
                                        }
                                        ?>
                                </select>
                            </td>
                            <td align="center">
                                <input type="button" value=">"
                                       onclick="AddOne(document.fm.elements['full_list[]'], document.fm.elements['joined_list[]']);"
                                       class="button2"><br><br>
                                <input type="button" value=">>"
                                       onclick="AddAll(document.fm.elements['full_list[]'], document.fm.elements['joined_list[]']);"
                                       class="button2"><br><br><br>
                                <input type="button" value="<"
                                       onclick="DelOne(document.fm.elements['full_list[]'], document.fm.elements['joined_list[]']);"
                                       class="button2"><br><br>
                                <input type="button" value="<<"
                                       onclick="DelAll(document.fm.elements['full_list[]'], document.fm.elements['joined_list[]']);"
                                       class="button2">
                            </td>
                            <td align="center"><?= $lang["joined_roles"] ?><br>
                                <select name="joined_list[]" multiple='multiple' class="multi_select" selectAll>
                                    <?php
                                    foreach ($joined_list as $role)
                                    {
                                    ?>
                                    <option value="<?= $role->getId() ?>"><?= $role->getRoleName() ?>
                                        <?php
                                        }
                                        ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php
            if ($cmd != "add") {
                ?>
                <tr>
                    <td class="field"><?= $lang["create_on"] ?></td>
                    <td class="value"><?= $user->getCreateOn() ?></td>
                    <td class="field"><?= $lang["create_at"] ?></td>
                    <td class="value"><?= $user->getCreateAt() ?></td>
                    <td class="field"><?= $lang["create_by"] ?></td>
                    <td class="value"><?= $user->getCreateBy() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["modify_on"] ?></td>
                    <td class="value"><?= $user->getModifyOn() ?></td>
                    <td class="field"><?= $lang["modify_at"] ?></td>
                    <td class="value"><?= $user->getModifyAt() ?></td>
                    <td class="field"><?= $lang["modify_by"] ?></td>
                    <td class="value"><?= $user->getModifyBy() ?></td>
                </tr>
            <?php
            }
            ?>
            <tr class="tb_detail">
                <td colspan="3" height="40"><input type="button" name="back" value="<?= $lang['back_list'] ?>"
                                                   onClick="Redirect('<?= isset($_SESSION['LISTPAGE']) ? $_SESSION['LISTPAGE'] : base_url() . '/mastercfg/user' ?>')">
                </td>
                <td colspan="3" align="right" style="padding-right:8px;">
                    <?php
                    if ($cmd == "add") {
                        ?>
                        <input type="submit" value="<?= $lang['header'] ?>">
                    <?php
                    } elseif ($cmd == "edit") {
                        ?>
                        <input type="submit" value="<?= $lang['update_button'] ?>">
                    <?php
                    }
                    ?>
                </td>
            </tr>
        </table>

        <input type="hidden" name="posted" value="1">
    </form>
    <?= $notice["js"] ?>
</div>
</body>
</html>