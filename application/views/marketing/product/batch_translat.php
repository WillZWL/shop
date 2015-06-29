<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/product/js_catlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
</head>
<body>
<div id="main">
    <form name="form" action="/marketing/product/translat_all_sku" enctype="multipart/form-data" method="post"
          target="_self">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="30" class="title"><?= $lang["header"] ?></td>
                <td class="title" align="right">
                    <input type="button" value="<?= $lang["list_button"] ?>" class="button"
                           onclick="Redirect('<?= site_url('marketing/product/') ?>')">
                    &nbsp; <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                                  onclick="Redirect('<?= site_url('marketing/product/add/') ?>')">
                    <?php
                    if ($prod_grp_cd) {
                        ?>
                        &nbsp; <input type="button" value="<?= $lang["add_colour"] ?>" class="button"
                                      onclick="Redirect('<?= site_url('marketing/product/add_colour/' . $prod_grp_cd) ?>')">&nbsp;
                        <input type="button" value="<?= $lang["add_version"] ?>" class="button"
                               onclick="Redirect('<?= site_url('marketing/product/add_version/' . $prod_grp_cd . '/' . $version_id) ?>')">
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td height="2" class="line"></td>
                <td height="2" class="line"></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" height="50" class="page_header" width="100%"
               style="padding-left:8px">
            <tr>
                <td colspan="5" height="70"><b
                        style="font-size:14px"><?= $lang["title"] ?></b><br/><?= $lang["header_message"] ?></td>
            </tr>
            <tr>
                <td height="50">
                    <b><?= $lang["from_en"] ?></b>
                </td>
                <td colspan="4">
                    <b><?= $lang["to"] ?>: </b>
                    <select name="language_id" class="select">
                        <option></option>
                        <?php
                        if ($lang_list) {
                            $selected_lang[$this->input->post("language_id")] = "SELECTED";
                            foreach ($lang_list as $language) {
                                if ($language->get_id() != 'en') {
                                    ?>
                                    <option
                                        value="<?= $language->get_id() ?>" <?= $selected_lang[$language->get_id()] ?>><?= strtoupper($language->get_id()) . ' - ' . $language->get_name() ?></option>
                                <?php
                                }
                            }
                        }
                        ?>
                    </select> <? if (!$this->input->post("language_id")) echo $lang["lang_null"]; ?>
                </td>
            </tr>
            <tr>
                <td width="50" height="50">
                    <input type="radio" name="model"
                           value="first" <? if ($this->input->post("model") == 'first') echo 'checked'; ?>/><b><?= $lang["s_time"] ?></b>
                </td>

                <!-- <td>
                    <b>Number:</b>
                    <input type="text" value="20" name="limit" />
                </td> -->
                <td width="250">
                    <b><?= $lang["start_date"] ?>: </b>
                    <input id="start_date" name="start_date" value='<?= htmlspecialchars($start_date) ?>'><img
                        src="/images/cal_icon.gif" class="pointer"
                        onclick="showcalendar(event, document.getElementById('start_date'), false, false, false, '2010-01-01')"
                        align="absmiddle">
                </td>
                <td width="250">
                    <b><?= $lang["end_date"] ?>: </b>
                    <input id="end_date" name="end_date" value='<?= htmlspecialchars($end_date) ?>'><img
                        src="/images/cal_icon.gif" class="pointer"
                        onclick="showcalendar(event, document.getElementById('end_date'), false, false, false, '2010-01-01')"
                        align="absmiddle">
                </td>
                <td>
                    <b><?= $lang["amount"] ?>: </b><input type="input" name="limit"
                                                          value="<?= ($this->input->post("limit")) ? $this->input->post("limit") : 20; ?>"/>
                </td>
                <td></td>
            </tr>
            <tr>
                <td height="50">
                    <input type="radio" name="model"
                           value="second" <? if ($this->input->post("model") != 'first') echo 'checked'; ?> /><b><?= $lang["s_list"] ?></b>
                </td>
                <td colspan="4">
                    <b><?= $lang["sku_list"] ?>:</b><br/>
                    <textarea name='sku_list' rows="5" cols="20"
                              style="width: 800"><? if ($this->input->post("sku_list")) echo $this->input->post("sku_list") ?></textarea>
                    <br/><br/>
                    <?= $lang["message"] ?>
                </td>
            </tr>
            <tr>
                <td height="50"></td>
                <td colspan="4">
                    <input type="submit" value="<?= $lang["button"] ?>"
                           onclick="javascript:{this.disabled=true;document.form.submit();}"/>
                    <?php
                    if ($_GET['translat']) {
                        echo 'Total ' . $_GET['n'] . ' be translat completed.';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td height="50"></td>
                <td colspan="3">

                </td>
                <td height="50"></td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>