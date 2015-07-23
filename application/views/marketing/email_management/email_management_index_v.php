<!DOCTYPE html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body>
<?php
?>
<div id="template"
     style="width:770px;margin:auto;overflow:auto;padding:20px 50px;background-color:#FCFCFC;box-shadow:0 5px 10px rgba(0, 0, 0, 0.5);">
    <div style="padding:10px 15px;text-align:left;">
        <h2><?= $lang["title"] ?></h2>

        <form id="template_id" name="template_id" method="get">
            <div style="padding:10px;">
                <table width="100%" style="text-align=left;font-family: Lucida Sans Unicode,sans-serif;">
                    <colgroup>
                        <col width="60%">
                        <col width="40%">
                    </colgroup>
                    <tr>
                        <td>
                            <?= $lang["template"] ?>
                            <select id="tpl" name="tpl" onchange="showdescription();clearselect('filter');">
                                <option></option>
                                <?php
                                $option = "";
                                foreach ($tpl_list as $value) {
                                    $selected = "";
                                    if ($_GET["tpl"]) {
                                        if ($_GET["tpl"] == $value)
                                            $selected = "SELECTED";
                                    }

                                    $option .= <<<html
            <option value=$value $selected>$value</option>
html;
                                }
                                echo $option;
                                ?>
                            </select>
                            &nbsp;&nbsp;<input type="submit" value="Submit">
                        </td>
                        <td id="description">
                            <!-- description will appear here when template selected -->
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php
                            if ($filter_arr) {
                                $option = $selected_filter = "";

                                ?>
                                <?= $lang["filter"] ?> <select id="filter" name="filter">
                                    <option></option>
                                    <?php
                                    foreach ($filter_arr as $value) {
                                        $selected = "";
                                        if ($_GET["filter"]) {
                                            if ($_GET["filter"] == $value) {
                                                $selected = "SELECTED";
                                                $selected_filter = $value;
                                            }
                                        } else {
                                            if ($tpl_edit["selected_filter"] == $value) {
                                                $selected = "SELECTED";
                                                $selected_filter = $value;
                                            }
                                        }
                                        $option .= <<<html
                <option value=$value $selected>$value</option>
html;

                                    }
                                    echo $option;
                                    ?>
                                </select>
                                &nbsp;&nbsp;&nbsp;<input type="submit" value="Apply Filter">
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
        <?php
        if ($_GET["tpl"]) {
            ?>
            <form name="save" id="save" method="post" onsubmit="return check_missing_variable('save')">
                <div>
                    <br><i><b><?= $lang["note"] ?></b></i><br>
                    <fieldset style="border-width:2px;margin-left:6px;margin-right:6px;padding:0 8px 15px;">
                        <legend><font style="color:red;"><h1><?= "[$selected_filter] - {$_GET["tpl"]}" ?></h1></font>
                        </legend>
                        <table width="100%" style="text-align=left;font-family: Lucida Sans Unicode,sans-serif;"
                               cellpadding="10">
                            <!-- <col width="60%"><col width="40%"> -->
                            <tr>
                                <td>
                                    <?= ($textarea["subject"]) ? $textarea["subject"] : ""; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?= ($textarea["message_html"]) ? $textarea["message_html"] : ""; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?= ($textarea["message_alt"]) ? "<font color='red'>*</font> <font color='slategray'><i>{$lang['message_alt_explanation']}</i></font><br><br>{$textarea['message_alt']}" : ""; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" name="filter_type" id="filter_type"
                                           value="<?= $filter_type ?>">
                                    <input type="hidden" name="selected_filter" id="selected_filter"
                                           value="<?= $selected_filter ?>">
                                    <input type="hidden" name="tpl_id" id="tpl_id" value="<?= $_GET["tpl"] ?>">
                                    <input type="hidden" name="tpl_table" id="tpl_table" value="<?= $tpl_table ?>">
                                    <input type="submit" value="Save Template Details" style="padding:5px 20px;">
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
            </form>
        <?php
        }
        ?>
    </div>
</div>
<?= $notice["js"] ?>


<script type="text/javascript">
    function showdescription() {
        // get selected template's description

        var tplselect = document.getElementById('tpl');
        var tpl = tplselect.options[tplselect.selectedIndex].value;
        if (tpl != "") {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("description").innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "<?=base_url()?>marketing/email_management/get_description?tpl=" + tpl, true);
            xmlhttp.send();
        }
        else {
            document.getElementById("description").innerHTML = "";
            return;
        }
    }

    function clearselect(select_id) {
        var obj = document.getElementById(select_id);
        if (obj) {
            obj.remove(obj.selectedIndex);
        }
    }

    function postform(type, id) {
        // gets the content of id passed in and echo out in new window for preview
        if (type == 'preview') {
            var message_html = document.getElementById(id).value;
            var w = window.open("", "Preview", "status=1,resizable=1,scrollbars=1,height=500,width=800");
            w.document.open();
            w.document.write(message_html);
            w.document.close();
        }
    }

    function check_missing_variable(form_id) {
        var form_ele = document.getElementById(form_id).elements;
        var missing_alert = "";

        // loop through each element in form
        for (var i = 0; i < form_ele.length; i++) {
            var id = form_ele[i].id;

            // if current element's id contain "_variable", then get the input value of its html content
            // e.g. in a loop for id="message_html_variable", it will get the value of id="message_html"
            if (id.indexOf('_variable') != -1) {
                var variable_text = document.getElementById(id).value;
                var content_id = id.substring(0, id.indexOf('_variable'));

                // skip current loop if cannot find id of respective content of variable's id
                if (document.getElementById(content_id)) {
                    var content_text = document.getElementById(content_id).value;

                    // explode the variables string and loop through each variable to check if exist in content
                    var variable_arr = variable_text.split(',');
                    var count = 0;

                    for (var j = 0; j < variable_arr.length; j++) {
                        var trim_variable = variable_arr[j].trim();
                        var missing_var = get_missing_variable(content_text, trim_variable);
                        if (missing_var != false) {
                            if (count == 0) {
                                if (missing_alert != "") {
                                    missing_alert += "\n";
                                }

                                missing_alert += "- " + content_id.toUpperCase() + " is missing variable(s): ";
                            }
                            missing_alert += missing_var + ", ";
                            count++;
                        }
                    }
                    if (missing_alert != "" && (missing_alert.lastIndexOf(", ")) != -1) {
                        missing_alert = missing_alert.slice(0, missing_alert.lastIndexOf(", "));
                    }

                }

            }
        }

        if (missing_alert != "") {

            missing_alert += "\nDo you want to continue?";

            var confirm = window.confirm(missing_alert);
            return confirm;
        }
        else {
            return true;
        }
    }

    function get_missing_variable(haystack, needle) {
        // check if number of each variable in orginal template has been accidentally removed. Returns missing variable.
        // haystack: template content
        // needle: original variable list in hidden input. in format of [:var1:]::CountofOccurences, e.g. [:so_item:]::3

        if (!haystack || !needle) {
            return false;
        }
        else {

            var var_count = needle.split('::');
            var variable = var_count[0];
            var count = var_count[1];

            // get number of occurences in the template string
            var occurence_in_haystack = (haystack.split(variable).length - 1);

            if (occurence_in_haystack != count) {
                return variable;
            }
            return false;

        }
    }

</script>
</body>
</html>