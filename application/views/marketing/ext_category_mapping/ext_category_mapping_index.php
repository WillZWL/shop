<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Google Admincentre</title>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <style>
        body {
            height: 2000px;
        }

        .custom-combobox {
            position: relative;
            display: inline-block;
            font-size: 10px;
        }

        .custom-combobox-toggle {
            position: absolute;
            top: 0;
            bottom: 0;
            margin-left: -2px;
            padding: 0;
            font-size: 10px;
        }

        .custom-combobox-input {
            padding: 5px 0px;
        }

        .ui-widget label {
            font-size: 15px;
        }

        .ui-menu .ui-menu-item a {
            font-size: 10px;
        }

        .ui-autocomplete-input {
            width: 100px;
        }

        .label {
            margin-left: 50px;
        }

        .ui-button-text {
            font-size: 60%;
        }

        .new_google_category, .input_box {
            border: 1px solid gray;
            padding: 2px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            color: #555555;
            font-weight: normal;
        }

        .arrow {
            color: gray;
        }

        #selectable .ui-selecting {
            background: #FECA40;
        }

        #selectable .ui-selected {
            background: #F39814;
            color: white;
        }

        #selectable {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 20%;
        }

        #selectable li {
            margin: 3px;
            padding: 0.4em;
            font-size: 15px;
            height: 12px;
        }

        #select-result {
            display: block;
            margin: 10px;
            height: 20px;
            color: #F39814;
        }

        #country_block {
            1 float: right;
        }

        .ui-dialog-titlebar {
            display: none;
        }

        .ui-dialog-content {
            font-size: 60%;
        }

        .ui-autocomplete {
            max-height: 200px;
            width: 500px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .function_block {
            border: 1px solid gray;
            padding: 10px;
            margin: 10px 0px;
        }

        .button_section {
            margin: 10px 0;
        }

        .page_title {
            font-size: 20px;
            color: #F39814;
        }

        .divider {
            padding: 0 20px;
        }

        .item_label {
            display: inline-block;
            margin-right: 50px;
            width: 20px;
            color: #555555;
        }

        .float_right {
            float: right;
        }

        <!--
        -->
        .gray_border {
            border: 1px solid gray;
            padding: 5px;
        }

        .campaign_item:hover, .adGroup_item:hover {
            cursor: pointer;
            outline: 1px solid gray;
        }

        <!--
        -->
        .ui-widget label {
            font-size: 10px;
        }

        .ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button {
            font-size: 15px;
        }

        .ui-accordion {
            font-size: 60%;
        }

        .system_cat {
            width: 30%;

        }

        .google_cat {
            width: 60%;
        }

        .ui-widget .system_cat, .ui-widget .google_cat {
            font-size: 90%;
            border: none;
        }

        .ui-widget .valid_google_cat {

        }

        .ui-widget .invalid_google_cat {
            border-bottom: dotted 1px #98BF21;
            background-color: #EAF2D3;
        }

        .ui-widget .invalid_google_cat.google_cat {
            border-bottom: solid 1px #98BF21;
            background-color: #EAF2D3;
        }

    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>


    <script>

        (function ($) {
            $.widget("custom.combobox", {
                _create: function () {
                    this.wrapper = $("<span>")
                        .addClass("custom-combobox")
                        .insertAfter(this.element);

                    this.element.hide();
                    this._createAutocomplete();
                    this._createShowAllButton();
                },

                _createAutocomplete: function () {
                    var selected = this.element.children(":selected"),
                        value = selected.val() ? selected.text() : "";

                    this.input = $("<input>")
                        .appendTo(this.wrapper)
                        .val(value)
                        .attr("title", "")
                        .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
                        .autocomplete({
                            delay: 0,
                            minLength: 0,
                            source: $.proxy(this, "_source")
                        })
                        .tooltip({
                            tooltipClass: "ui-state-highlight"
                        });

                    this._on(this.input, {
                        autocompleteselect: function (event, ui) {
                            ui.item.option.selected = true;
                            this._trigger("select", event, {
                                item: ui.item.option
                            });
                        },

                        autocompletechange: "_removeIfInvalid"
                    });
                },

                _createShowAllButton: function () {
                    var input = this.input,
                        wasOpen = false;

                    $("<a>")
                        .attr("tabIndex", -1)
                        .attr("title", "Show All Items")
                        .tooltip()
                        .appendTo(this.wrapper)
                        .button({
                            icons: {
                                primary: "ui-icon-triangle-1-s"
                            },
                            text: false
                        })
                        .removeClass("ui-corner-all")
                        .addClass("custom-combobox-toggle ui-corner-right")
                        .mousedown(function () {
                            wasOpen = input.autocomplete("widget").is(":visible");
                        })
                        .click(function () {
                            input.focus();

                            // Close if already visible
                            if (wasOpen) {
                                return;
                            }

                            // Pass empty string as value to search for, displaying all results
                            input.autocomplete("search", "");
                        });
                },

                _source: function (request, response) {
                    var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
                    response(this.element.children("option").map(function () {


                        var combox = $(this).parent().attr("id");
                        var text = $(this).text();

                        if (combox == "combobox_2") {
                            var cat_id = $("#combobox").find(":selected")[0].id;
                            var parent_cat_id = $(this).attr("parent_cat_id");

                            if (this.value && ( !request.term || matcher.test(text) ) && cat_id == parent_cat_id)
                                return {
                                    label: text,
                                    value: text,
                                    option: this
                                };
                        }
                        else if (combox == "combobox_3") {
                            var cat_id = $("#combobox_2").find(":selected")[0].id;
                            var parent_cat_id = $(this).attr("parent_cat_id");

                            if (this.value && ( !request.term || matcher.test(text) ) && cat_id == parent_cat_id)
                                return {
                                    label: text,
                                    value: text,
                                    option: this
                                };
                        }
                        else if (combox == "combobox_5") {
                            var country_id = $("#combobox_4").find(":selected")[0].id;
                            var this_option_country_id = $(this).attr("country_id");

                            if (this.value && ( !request.term || matcher.test(text) ) && country_id == this_option_country_id)
                                return {
                                    label: text,
                                    value: text,
                                    option: this
                                };
                        }
                        else if (combox == "combobox_googlefeed_country") {
                            var account_id = $("#combobox_googlefeed_name").find(":selected")[0].id;
                            var this_option_account_id = $(this).attr("account_id");

                            if (this.value && ( !request.term || matcher.test(text) ) && account_id == this_option_account_id)
                                return {
                                    label: text,
                                    value: text,
                                    option: this
                                };
                        }
                        else if (combox == "combobox_googlefeed_language") {
                            var account_id = $("#combobox_googlefeed_name").find(":selected")[0].id;
                            var this_option_account_id = $(this).attr("account_id");

                            if (this.value && ( !request.term || matcher.test(text) ) && account_id == this_option_account_id)
                                return {
                                    label: text,
                                    value: text,
                                    option: this
                                };
                        }
                        else if (this.value && ( !request.term || matcher.test(text) ))
                            return {
                                label: text,
                                value: text,
                                option: this
                            };
                    }));
                },

                _removeIfInvalid: function (event, ui) {

                    // Selected an item, nothing to do
                    if (ui.item) {
                        return;
                    }

                    // Search for a match (case-insensitive)
                    var value = this.input.val(),
                        valueLowerCase = value.toLowerCase(),
                        valid = false;
                    this.element.children("option").each(function () {
                        if ($(this).text().toLowerCase() === valueLowerCase) {
                            this.selected = valid = true;
                            return false;
                        }
                    });

                    // Found a match, nothing to do
                    if (valid) {
                        return;
                    }

                    // Remove invalid value
                    this.input
                        .val("")
                        .attr("title", value + " didn't match any item")
                        .tooltip("open");
                    this.element.val("");
                    this._delay(function () {
                        this.input.tooltip("close").attr("title", "");
                    }, 2500);
                    this.input.data("ui-autocomplete").term = "";
                },

                _destroy: function () {
                    this.wrapper.remove();
                    this.element.show();
                }
            });
        })(jQuery);

        $(function () {
            $("#dialog_mapping_rule, #dialog_ajax_feedback").dialog({
                autoOpen: false,
                show: {
                    effect: "fade",
                    duration: 1000
                },
                hide: {
                    effect: "silde",
                    duration: 1000
                }
            });

            $(".enable_combobox").combobox();

            $(".button").button();
            $("#add_new_google_category_level").click(function () {
                $("<span class='arrow'> &gt; </span>").appendTo("#new_google_category_block");
                $("<input class='new_google_category'>").appendTo("#new_google_category_block");
            });
            $("#selectable").selectable({
                stop: function () {
                    var result = $("#select-result").empty();
                    $(".ui-selected", this).each(function () {
                        var country_id = $(this).attr("id");
                        result.append(" " + (country_id));
                    });
                }
            });

            $("#create_mapping_rule").click(function () {
                var cat_id = $("#combobox").find(":selected")[0].id;
                var sub_cat_id = $("#combobox_2").find(":selected")[0].id;
                var sub_sub_cat_id = $("#combobox_3").find(":selected")[0].id;
                var country_id = $("#combobox_4").find(":selected")[0].id;
                var target_google_category = $("#combobox_5").val();

                if ((cat_id.trim() == "" && sub_cat_id.trim() == "" && sub_sub_cat_id.trim() == "") || country_id.trim() == "" || target_google_category.trim() == "") {
                    $("#dialog_mapping_rule").dialog("open");
                }
                else {
                    $.ajax({
                        type: "POST",
                        dataType: "text",
                        url: "ext_category_mapping/create_mapping_rule",
                        data: {
                            "cat_id": cat_id,
                            "sub_cat_id": sub_cat_id,
                            "sub_sub_cat_id": sub_sub_cat_id,
                            "country_id": country_id,
                            "target_google_category": target_google_category
                        },
                        success: function (data) {
                            var result = $("#ajax_message").html(data);
                            $("#dialog_ajax_feedback").dialog("open");
                        },
                        error: function (data) {
                            var result = $("#ajax_message").html(data);
                            $("#dialog_ajax_feedback").dialog("open");
                        }
                    });
                }

            });

            $(".dialog_block").bind('click', function (e) {
                if ($(this).dialog('isOpen')) {
                    $(this).dialog('close');
                }

                if ($(this).dialog('isOpen')) {
                    $(this).dialog('close');
                }
            });


            $("#add_new_category").click(function () {
                var google_category_list = $(".new_google_category");
                var new_google_category = "";

                for (var k = 0; k < google_category_list.length; k++) {
                    var label = google_category_list[k].value.trim();
                    if (label.trim() == "") {
                        $("#dialog_mapping_rule").dialog("open");
                        return;
                    }
                    else {
                        label = label.toLowerCase();
                        label = label.charAt(0).toUpperCase() + label.slice(1);
                        new_google_category = new_google_category + label + " > ";
                    }
                }
                var selected_country = $("#country_block .ui-selected");
                var arr = new Array();
                //alert(selected_country.length);return;
                for (var i = 0; i < selected_country.length; i++) {
                    var country = selected_country[i].id;
                    arr.push(country);
                }

                if (arr.length <= 0) {
                    $("#dialog_mapping_rule").dialog("open");
                    return;
                }

                $.ajax({
                    url: "ext_category_mapping/create_google_category",
                    type: "POST",
                    dataType: "html",
                    data: {"new_google_cat": new_google_category, "country_list": arr},
                    success: function (data) {
                        var result = $("#ajax_message").html(data);
                        $("#dialog_ajax_feedback").dialog("open");
                    },
                    error: function () {

                    }
                });
            });

            $("div.accordion").accordion({
                heightStyle: "content",
                collapsible: true,
                active: false
            });


            $(document).on("click", ".country_tab", function () {
                var country_id = $(this).attr("id");
                $.ajax({
                    url: "ext_category_mapping/get_country_google_category_mapping",
                    type: "POST",
                    dataType: "html",
                    data: {"country_id": country_id},
                    success: function (data) {

                        var result = $("#" + country_id + "_container").html(data);
                        $("div.sub_accordion").accordion({
                            heightStyle: "content",
                            collapsible: true,
                            active: false
                        });
                        $("div.sub_accordion").accordion("refresh");
                    },
                    error: function () {
                        alert("error, please contact technical staff for help");
                    }
                });


            });
            /*
             $('.country_tab').click(function()
             {
             var country_id = $(this).attr("id");
             $.ajax({
             url: "ext_category_mapping/get_country_google_category_mapping",
             type:"POST",
             dataType: "html",
             data: {"country_id":country_id},
             success:function(data){

             var result = $("#"+ country_id +"_container").html(data);
             },
             error:function(){
             alert("error, please contact technical staff for help");
             }
             });
             });
             */
        });


    </script>
</head>
<body>
<div class="page_title">Google Admincentre</div>
<div class="function_block">
    <h3>Create Mapping Rule</h3>

    <div class="ui-widget">
        <br>------------------<br>

        <form action="<?= base_url() ?>marketing/ext_category_mapping" method="GET">
            <b style="font-size: 12px;">FILTER GOOGLE CAT.</b><br>
            <b style="font-size: 12px;">Country (select for faster category loading) </b>
            <select id="gcat_country" name="gcat_country" style="width:100px;size:30">
                <option id="" value=""></option>
                <?php  foreach ($country_list as $country) {
                    ?>
                    <option
                        id="<?= $country ?>" <?= $_GET["gcat_country"] == $country ? " selected" : "" ?> ><?= $country ?></option>
                <?php  } ?>
            </select>
            <br>
            <b style="font-size: 12px;">Search Term (in local lang.): </b>
            <select id="gcat_wildtype" name="gcat_wildtype">
                <option value="contain" <?= $_GET["gcat_wildtype"] == "contain" ? " selected" : "" ?> >Contains</option>
                <option value="begin" <?= $_GET["gcat_wildtype"] == "begin" ? " selected" : "" ?>>Begins with</option>
                <option value="end"  <?= $_GET["gcat_wildtype"] == "end" ? " selected" : "" ?>>Ends with</option>
            </select>
            <input type="text" name="gcat" value="<?= $_GET["gcat"] ? $_GET["gcat"] : "" ?>" size="50"><br>
            <input type="submit" value="Search">
        </form>
        <br>------------------<br>
        <select id="combobox" class="enable_combobox">
            <?php  foreach ($cat_details_list[0] as $cat_obj) { ?>
                <option id="<?= $cat_obj->get_id() ?>"><?= $cat_obj->get_name() ?></option>
            <?php  } ?>
        </select>


        <span class="divider"></span>
        <select id="combobox_2" class="enable_combobox">
            <option id="" value=""></option>
            <?php  foreach ($cat_details_list[1] as $cat_obj) { ?>
                <option id="<?= $cat_obj->get_id() ?>"
                        parent_cat_id="<?= $cat_obj->get_parent_cat_id() ?>"><?= $cat_obj->get_name() ?></option>
            <?php  } ?>
        </select>

        <span class="divider"></span>
        <select id="combobox_3" class="enable_combobox">
            <option id="" value=""></option>
            <?php  foreach ($cat_details_list[2] as $cat_obj) { ?>
                <option id="<?= $cat_obj->get_id() ?>"
                        parent_cat_id="<?= $cat_obj->get_parent_cat_id() ?>"><?= $cat_obj->get_name() ?></option>
            <?php  } ?>
        </select>

        <span class="divider"></span>
        <label class="label">Country</label>
        <select id="combobox_4" class="enable_combobox">
            <option id="" value=""></option>
            <?php  foreach ($country_list as $country) { ?>
                <option id="<?= $country ?>"><?= $country ?></option>
            <?php  } ?>
        </select>

        <br><br>
        <label>Google Category</label>
        <select id="combobox_5" style="width:500px;size:30">

            <option id="" value=""></option>
            <?php  foreach ($google_category_list as $google_category) { ?>
                <option country_id="<?= $google_category->get_country_id() ?>"
                        value="<?= $google_category->get_id() ?>"><?= htmlspecialchars($google_category->get_ext_name()) ?></option>
            <?php  } ?>
        </select>
    </div>


    <div class="button_section">
        <button class="button" id="create_mapping_rule">Create Mapping Rule</button>
    </div>

</div>

<div id="google_category_section" class="function_block">
    <h3>Google Category Mapping</h3>

    <div class="accordion">
        <?php  if ($country_list) {
            foreach ($country_list as $country_id) {
                ?>
                <h3 id="<?= $country_id ?>" class="country_tab"><?= $country_id ?></h3>
                <div>
                    <p id="<?= $country_id ?>_container">
                    </p>
                </div>
            <?php  }
        } ?>
    </div>
</div>

<div id="google_category_section" class="function_block">
    <h3>Create New Google Categroy</h3>

    <div id="new_google_category_block">
        <input type="text" class="new_google_category">
    </div>
    <div class="button_section">
        <button class="button" id="add_new_google_category_level">add new level</button>
    </div>
    <div id="country_block">
        <span id="select-result">Hold Ctrl to select Multiple Country</span>

        <div>
            <ol id="selectable">
                <?php  foreach ($country_list as $country) { ?>
                    <li class="ui-widget-content" id="<?= $country ?>"><?= $country ?></li>
                <?php  } ?>
            </ol>
        </div>
    </div>
    <div class="button_section">
        <button class="button" id="add_new_category">Add new Category</button>
    </div>
</div>

<span class="page_title">Google Shopping Content</span>

<div>
    <div class="ui-widget">
        <select id="combobox_googlefeed_name" class="enable_combobox">
            <?php  foreach ($google_datafeed_account as $item) { ?>
                <option id="<?= $item["account_id"] ?>"><?= $item["account_name"] ?></option>
            <?php  } ?>
        </select>
        <span class="divider"></span>
        <select id="combobox_googlefeed_country" class="enable_combobox">
            <?php  foreach ($google_datafeed_account as $item) {
                foreach ($item["country"] as $country_id) {
                    ?>
                    <option account_id="<?= $item["account_id"] ?>"
                            value="<?= $country_id ?>"><?= $country_id ?></option>
                <?php
                }
            } ?>
        </select>

        <span class="divider"></span>

        <select id="combobox_googlefeed_language" class="enable_combobox">
            <?php  foreach ($google_datafeed_account as $item) {
                foreach ($item["language"] as $language) {
                    ?>
                    <option account_id="<?= $item["account_id"] ?>" value="<?= $language ?>"><?= $language ?></option>
                <?php
                }
            } ?>
        </select>
        <span class="divider"></span>
        <input class="input_box" type="text" id="sku" placeholder="SKU">

        <button class="button" id="get_product_item">Retrieve</button>
        <button class="button" id="item_update_button" style="display:none;">Update</button>
        <button class="button" id="get_report">Report</button>
        <div id="item_result" style="text-align:centre"></div>
    </div>

</div>

<br>
<span class="page_title">Adwords Admin</span>

<div style="border:1px solid gray; height:200px">

    <div style="border:1px solid gray;width:25%; float:left">
        <div class="gray_border ui-widget">
            <select id="ad_accountId"></select>
            <button class="button" id="ad_start" style="float:right;"> Start</button>
        </div>

        <div id="campaign_result" class="gray_border"></div>
    </div>

    <div style="border:1px solid gray;width:25%;float:left">
        <div id="adgroup_result" class="gray_border"></div>
    </div>

    <div style="border:1px solid gray;width:25%;float:left">
        <div id="keyword_result" class="gray_border"></div>
    </div>

</div>

<div style="clear:both"></div>

<div style="border:1px solid gray;width:25%;float:left">
    <div id="adGroupAd_result" class="gray_border"></div>
</div>


<div id="dialog_mapping_rule" class="dialog_block" title="New Categroy Mapping">
    <h3>New Category Mapping </h3>

    <p>Please check your input. </p>
</div>

<div id="dialog_ajax_feedback" class="dialog_block" title="">
    <p id="ajax_message"></p>
</div>
<script>
    $(function () {
        $("#get_product_item").click(function () {
            var account_id = $("#combobox_googlefeed_name").find(":selected")[0].id;
            var country_id = $("#combobox_googlefeed_country").find(":selected")[0].value;
            var language_id = $("#combobox_googlefeed_language").find(":selected")[0].value;
            var sku = $("#sku").val().trim();
            var result = $("#ajax_message").html("Loading...");
            $("#dialog_ajax_feedback").dialog("open");
            //alert(sku);return ;
            $.ajax({
                url: "ext_category_mapping/get_product_item",
                type: "POST",
                dataType: "html",
                data: {"account_id": account_id, "country_id": country_id, "language_id": language_id, "sku": sku},
                success: function (data) {
                    if (data == "No Result Found") {
                        $("#item_update_button").css({
                            "display": "none"
                        });

                    }
                    else {
                        $("#item_update_button").css({
                            "display": "inline"
                        });
                    }
                    $("#dialog_ajax_feedback").dialog("close");
                    $("#item_result").empty().html(data);
                    //$("#dialog_ajax_feedback").dialog("open");

                },
                error: function () {
                    var result = $("#ajax_message").html("Error. Please Contact IT staff.");
                    $("#dialog_ajax_feedback").dialog("open");
                }
            });
        });

        $("#item_update_button").click(function () {
            var item_sku = $("#item_sku").val();
            var item_country = $("#item_country").val();
            var item_account = $("#item_account").val();
            var item_language = $("#item_language").val();
            var item_title = $("#item_title").val();
            var item_condi = $("#item_condi").val();
            var item_price = $("#item_price").val();
            var item_brand = $("#item_brand").val();
            var item_valid = $("#item_valid").val();
            var item_gtin = $("#item_gtin").val();
            var item_mpn = $("#item_mpn").val();
            var item_currency = $("#item_currency").val();
            var item_google_categorys = $("#item_google_categorys").val();

            var result = $("#ajax_message").html("Loading...");
            $("#dialog_ajax_feedback").dialog("open");
            $.ajax({
                url: "ext_category_mapping/update_product_item",
                type: "POST",
                dataType: "html",
                data: {
                    "item_sku": item_sku,
                    "item_title": item_title,
                    "item_condi": item_condi,
                    "item_price": item_price,
                    "item_brand": item_brand,
                    "item_valid": item_valid,
                    "item_gtin": item_gtin,
                    "item_mpn": item_mpn,
                    "item_currency": item_currency,
                    "item_country": item_country,
                    "item_account": item_account,
                    "item_language": item_language,
                    "item_google_categorys": item_google_categorys,
                },
                success: function (data) {
                    var result = $("#ajax_message").html(data);
                },
                error: function () {
                    var result = $("#ajax_message").html("Error. Please Contact IT staff.");
                    $("#dialog_ajax_feedback").dialog("open");
                }
            });
        });

        $("#get_report").click(function () {
            //var account_id = $("#combobox_googlefeed_name").find(":selected")[0].id;
            var country_id = $("#combobox_googlefeed_country").find(":selected")[0].value;

            //window.location.url = "ext_category_mapping/get_google_shopping_content_report/WEB" + country_id;
            var url = "ext_category_mapping/get_google_shopping_content_report/WEB" + country_id;
            window.location.assign(url);
        });


    })
</script>
<script>

    $(function () {
        $.ajax({
            url: "ext_category_mapping/account_info",
            dataType: "json",
            success: function (data) {
                $.map(data, function (item) {
                    $("<option value='" + item.accountId + "'>" + item.accountName + "</option>").appendTo("#ad_accountId");
                });
                $("#ad_accountId").combobox();
            }

        });

        $("#ad_start").click(function () {
            var ad_accountId = $("#ad_accountId").val();
            $.ajax({
                url: "ext_category_mapping/compaign_info",
                type: "POST",
                dataType: "json",
                data: {"ad_accountId": ad_accountId},
                success: function (data) {
                    $("#campaign_result").empty();
                    $.map(data, function (item) {
                        $("<div class='campaign_item' data='" + item.ad_accountId + "' id='" + item.campaignId + "'>" + item.campaignName + "</div>").appendTo("#campaign_result");
                    });
                },
                error: function () {

                }
            });
        });

        $(document).on('click', '.campaign_item', function () {
            var campaignId = $(this).attr('id');
            var ad_accountId = $(this).attr('data');
            //alert(ad_accountId);return;
            $.ajax({
                url: "ext_category_mapping/adgroup_info",
                type: "POST",
                data: {"ad_accountId": ad_accountId, "campaignId": campaignId},
                dataType: "JSON",
                success: function (data) {
                    $("#adgroup_result").empty();

                    if (data[0].error) {
                        $("<div>" + data[0].error + "</div>").appendTo("#adgroup_result");
                        return;
                    }
                    $.map(data, function (item) {
                        $("<div class='adGroup_item' data1='" + item.campaignId + "' data='" + item.ad_accountId + "' id='" + item.adGroupId + "'>" + item.adGroupName + "</div>").appendTo("#adgroup_result");

                    });
                },
                error: function () {

                }
            });
            //alert(ad_accountId);
        });

        $(document).on('click', '.adGroup_item', function () {
            var ad_accountId = $(this).attr("data");
            var adGroupId = $(this).attr("id");

            var result = $("#ajax_message").html("Loading...");
            //  $("#dialog_ajax_feedback").dialog("open");
            $.ajax({
                url: "ext_category_mapping/keyword_info",
                type: "POST",
                data: {"ad_accountId": ad_accountId, "adGroupId": adGroupId},
                dataType: "JSON",
                success: function (data) {
                    $("#keyword_result").empty();

                    if (data[0].error) {
                        $("<div>" + data[0].error + "</div>").appendTo("#keyword_result");
                        return;
                    }
                    $.map(data, function (item) {
                        $("<div class='keyword_item' title='" + item.matchType + "'data1='" + item.adGroupId + "' data='" + item.ad_accountId + "' id='" + item.keywordId + "'>" + item.keyword + "</div>").appendTo("#keyword_result");
                    });

                    //  $("#dialog_ajax_feedback").dialog("close");
                },
                error: function (data) {

                }
            });

            $.ajax({
                url: "ext_category_mapping/adGroup_ad_info",
                type: "POST",
                data: {"ad_accountId": ad_accountId, "adGroupId": adGroupId},
                dataType: "JSON",
                success: function (data) {
                    $("#adGroupAd_result").empty();

                    if (data[0].error) {
                        $("<div>" + data[0].error + "</div>").appendTo("#adGroupAd_result");
                        return;
                    }
                    $.map(data, function (item) {
                        $("<div class='adGroupAd_item' data1='" + item.adGroupId + "' data='" + item.ad_accountId + "' id='" + item.adGroupAd_id + "'><a href=" + item.adGroupAd_url + ">" + item.headline + "</a><div>" + item.adGroupAd_description1 + "</div><div>" + item.adGroupAd_description2 + "</div><div style='color:#0E8F0E'>" + item.adGroupAp_display_url + "</div></div><br>").appendTo("#adGroupAd_result");
                    });

                    //  $("#dialog_ajax_feedback").dialog("close");
                },
                error: function (data) {

                }
            });
        });

        $(document).on("click", ".keyword_item", function () {

            var ad_accountId = $(this).attr('data');
            var keywordId = $(this).attr('id');
            var adGroupId = $(this).attr('data1');
            $.ajax({
                url: "ext_category_mapping/keyword_parameter_info",
                type: "POST",
                DataType: "html",
                data: {
                    "ad_accountId": ad_accountId,
                    "keywordId": keywordId,
                    "adGroupId": adGroupId
                },
                success: function (data) {
                    var result = $("#ajax_message").html(data);
                    $("#dialog_ajax_feedback").dialog("open");
                },
                error: function (data) {

                }
            });


        })
    })

</script>
</body>
</html>
