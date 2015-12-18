function hostname_url()
{
    var port = (window.location.hostname == 'dd.admin') ? ':8000' : '';
    return window.location.protocol + '//' + window.location.hostname + port;
}

var result_recv = false;
function rePrice(platform_type, platform, sku)
{
    $('#update_pricing_'+platform).attr('disabled','disabled');
    $('#submit_all_changes').attr('disabled','disabled');
    var remote_url;
    var sp = $("input[id='sp["+platform+"]']").val() * 1;

    if (platform_type == 'WEBSITE') {
        var selected_auto_price = $("select[name='auto_price["+platform+"]'] option:selected").val();
        if (selected_auto_price) {
            if (selected_auto_price == 'Y') {
                $("input[id='sp["+platform+"]']").val(0);
                sp = 0;
            }
        }
    } else {
        if (document.getElementById('auto_price_cb['+platform+']') != null) {
            if (document.getElementById('auto_price_cb['+platform+']').checked == true) {
                $("input[id='sp["+platform+"]']").val(0);
                sp = 0;
            }
        }

    }
    remote_url = hostname_url() + '/marketing/pricing_tools/get_profit_margin_json/' + platform + '/' + sku +'/' + sp;
    result_recv = false;
    $.ajax({
        type: 'POST',
        url: remote_url,
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function(msg)
        {
            declared = msg.get_declared_value;
            vat = msg.get_vat;
            duty = msg.get_duty;
            payment = msg.get_payment_charge;
            forex_fee = msg.get_forex_fee;
            delivery_charge = msg.get_delivery_cost;
            commission = msg.get_sales_commission;
            cost = msg.get_cost;
            total = msg.get_price;
            profit = msg.get_profit;
            margin = msg.get_margin;
            if (!result_recv) {
                sp = $("input[id='sp["+platform+"]']").val() * 1;
                a = parseFloat(sp);
                b = parseFloat(msg.based_on);

                if (a != b) {
                    $("td[id='profit["+platform+"]']").html('Wait...');
                    $("td[id='margin["+platform+"]']").html('-');
                    $("input[id='hidden_profit["+platform+"]']").val(profit);
                    $("input[id='hidden_margin["+platform+"]']").val(margin);
                    return;
                }
            } else {
                return;
            }

            result_recv = true;

            if (platform_type == 'WEBSITE') {
                if (selected_auto_price != null) {
                    if(selected_auto_price == 'Y') {
                        $("input[id='sp["+platform+"]']").attr('readOnly', false);
                        $("input[id='sp["+platform+"]']").val(total);
                        $("input[id='sp["+platform+"]']").attr('readOnly', true);
                    }
                }
            } else {
                if (document.getElementById('auto_price_cb['+platform+']') != null) {
                    if(document.getElementById('auto_price_cb['+platform+']').checked == true) {
                        $("input[id='sp["+platform+"]']").attr('readOnly', false);
                        $("input[id='sp["+platform+"]']").val(total);
                        $("input[id='sp["+platform+"]']").attr('readOnly', true);
                    }
                }
            }

            if(margin > 0) {
                color = '#ddffdd';
            } else {
                color = '#ffdddd';
            }

            $("tr[id='row["+platform+"]']").css('backgroundColor', color);

            if ($("td[id='declare["+platform+"]']").length > 0) {
                $("td[id='declare["+platform+"]']").html(declared);
            }
            if ($("td[id='vat["+platform+"]']").length > 0) {
                $("td[id='vat["+platform+"]']").html(vat);
            }
            $("td[id='duty["+platform+"]']").html(duty);
            $("td[id='pc["+platform+"]']").html(payment);
            $("td[id='forex_fee["+platform+"]']").html(forex_fee);
            $("td[id='delivery_charge["+platform+"]']").html(delivery_charge);
            $("td[id='comm["+platform+"]']").html(commission);
            $("td[id='total_cost["+platform+"]']").html(cost);
            $("td[id='total["+platform+"]']").html(total);
            $("td[id='profit["+platform+"]']").html(profit);
            $("td[id='margin["+platform+"]']").html(margin + '%');
            $("input[id='hidden_profit["+platform+"]']").val(profit);
            $("input[id='hidden_margin["+platform+"]']").val(margin);

            $('#update_pricing_'+platform).removeAttr('disabled');
            $('#submit_all_changes').removeAttr('disabled');
        },
        error: function(err) {
            $('#update_pricing_'+platform).removeAttr('disabled');
            $('#submit_all_changes').removeAttr('disabled');
            if (err.status == 200) {
                // ParseResult(err);
            }
            // else { alert('Error:' + err.responseText + '  Status: ' + err.status);
        }
    });

    return true;
};

function update_pricing_for_platform(platform_type, platform, sku)
{
    $("#note_"+platform).html("Note:<font color='yellow'>It is run updating, wait...</font>");
    var selling_price = $("input[name='selling_price["+platform+"]']").val();
    var allow_express = $("input[name='allow_express["+platform+"]']:checked").val();
    var is_advertised = $("input[name='is_advertised["+platform+"]']:checked").val();
    var formtype = $("input[name='formtype["+platform+"]']").val();
    var listing_status = $("select[name='listing_status["+platform+"]'] option:selected").val();
    var hidden_profit = $("input[name='hidden_profit["+platform+"]']").val();
    var hidden_margin = $("input[name='hidden_margin["+platform+"]']").val();

    if (platform_type == "WEBSITE") {
        var auto_price = $("select[name='auto_price["+platform+"]'] option:selected").val();
        var fixed_rrp = $("input[name='fixed_rrp["+platform+"]']:checked").val();
        var rrp_factor = $("input[name='rrp_factor["+platform+"]']").val();
        var post_data = {
                sku:sku,
                platform:platform,
                selling_price:selling_price,
                allow_express:allow_express,
                listing_status:listing_status,
                is_advertised:is_advertised,
                auto_price:auto_price,
                formtype:formtype,
                fixed_rrp:fixed_rrp,
                rrp_factor:rrp_factor,
                hidden_profit:hidden_profit,
                hidden_margin:hidden_margin
            };
    } else if (platform_type == "EBAY") {
        var title = $("input[name='price_ext["+platform+"][title]']").val();
        var ext_ref_1 = $("input[name='price_ext["+platform+"][ext_ref_1]']").val();
        var ext_ref_2 = $("input[name='price_ext["+platform+"][ext_ref_2]']").val();
        var ext_ref_3 = $("select[name='price_ext["+platform+"][ext_ref_3]'] option:selected").val();
        var ext_ref_4 = $("select[name='price_ext["+platform+"][ext_ref_4]'] option:selected").val();
        var ext_qty = $("input[name='price_ext["+platform+"][ext_qty]']").val();
        var handling_time = $("select[name='price_ext["+platform+"][handling_time]'] option:selected").val();
        var action = $("select[name='action["+platform+"]'] option:selected").val();
        var reason = $("select[name='reason["+platform+"]'] option:selected").val();


        var post_data = {
            sku:sku,
            platform:platform,
            selling_price:selling_price,
            allow_express:allow_express,
            listing_status:listing_status,
            is_advertised:is_advertised,
            formtype:formtype,
            hidden_profit:hidden_profit,
            hidden_margin:hidden_margin,
            title:title,
            ext_ref_1:ext_ref_1,
            ext_ref_2:ext_ref_2,
            ext_ref_3:ext_ref_3,
            ext_ref_4:ext_ref_4,
            ext_qty:ext_qty,
            handling_time:handling_time,
            action:action
        };
    }
    var url = hostname_url() + '/marketing/pricing_tools/update_pricing_for_platform/'+platform_type;

    $.ajax({
        url:url,
        type:"POST",
        dataType:"json",
        data: post_data,
        success: function (data) {
            if (data.success) {
                $("input[name='formtype["+platform+"]']").val('update');
                var price = data.price;
                var listing_status = data.listing_status;
                var margin = data.margin;
                if (margin > 0) {
                    var m_color = "88ff88;"
                } else {
                    var m_color = "ff8888;"
                }

                if (listing_status == "L") {
                    var status = "Listed";
                    var s_color = "00FF00";
                } else {
                    var status = "Not Listed";
                    var s_color = "FF0000";
                }

                // update_product_for_pricing_tool(platform_type, sku, platform);
                if (price > 0) {
                    $("#title_"+platform).html("<span id='"+platform+"_price'>"+price + "</span> | <span style='color:#"+s_color+";'>"+ status + "</span> | <span style='color:#"+m_color+";'>"+ margin + "%</span>");
                } else {
                    var is_check_margin = false
                    var default_price = $("input[id='origin_price["+platform+"]']").val();
                    if (default_price > 0) {
                        var default_text = "WEBHK:" + default_price;
                        $.ajax({
                            type: 'POST',
                            url: hostname_url() + '/marketing/pricing_tools/get_profit_margin_json/' + platform + '/' + sku +'/' + default_price,
                            contentType: 'application/json; charset=utf-8',
                            dataType: 'json',
                            success: function(msg)
                            {
                                margin = msg.get_margin;
                                $("#title_"+platform).html("<span style='background:#ffff41; padding:3px 5px;color:#ed3113'>No Pricing</span> (<span class='converted'>"+default_text+
                                    "</span>)| <span style='color:#"+s_color+
                                    ";'>"+ status + "</span> | <span style='color:#"+m_color+";'>"+ margin +"%</span>");
                            }
                        });
                    } else {
                        var default_text = "WEBHK No Pricing";
                        $("#title_"+platform).html("<span style='background:#ffff41; padding:3px 5px;color:#ed3113'>No Pricing</span> (<span class='converted'>"+default_text+
                            "</span>)| <span style='color:#"+s_color+";'>"+ status + "</span> | <span style='color:#"+m_color+
                            ";'>"+ margin +"%</span>");
                    }
                }
                $("#note_"+platform).html("Note:<font color='blue'>It is update succeed</font>");
            }
            if (data.fail) {
                $("#note_"+platform).html("Note:<font color='red'>It is update failed</font>");
            }
            if (data.no_update) {
                $("#note_"+platform).html("Note:<font color='red'>No data changes, do not update</font>");
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#note_"+platform).html("Note:<font color='red'>It is update error, "+errorThrown+"</font>");
        }
    });
}

function update_product_for_pricing_tool(platform_type, sku, platform)
{
    if (platform == 'all') {
        platform = '';
    }
    $("#note_for_product").html("Note:<font color='yellow'>It is run updating, wait...</font>");
    var clearance = $("select[name='clearance'] option:selected").val();
    var status = $("select[name='status'] option:selected").val();
    var webqty = $("input[name='webqty']").val();
    var m_note = $("input[name='m_note']").val();
    var s_note = $("input[name='s_note']").val();
    // var google_adwords = $("input[name='google_adwords[]']").val();
    var ext_mapping_code = $("input[name='ext_mapping_code']").val();
    var max_order_qty = $("input[name='max_order_qty']").val();

    var chk = $("input[name='chk']").val();
    var ean = $("input[name='ean']").val();
    var mpn = $("input[name='mpn']").val();
    var upc = $("input[name='upc']").val();

    var url = hostname_url() + '/marketing/pricing_tools/update_product_for_pricing_tool/'+platform_type+'/'+sku+'/'+platform;
    var post_data = {
            clearance:clearance,
            status:status,
            webqty:webqty,
            m_note:m_note,
            s_note:s_note,
            // google_adwords:google_adwords,
            ext_mapping_code:ext_mapping_code,
            max_order_qty:max_order_qty,
            chk:chk,
            ean:ean,
            mpn:mpn,
            upc:upc
        };
    $.ajax({
        url:url,
        type:"POST",
        dataType:"json",
        data: post_data,
        success: function (data) {
            if (data.add_m_note) {
                if ($("#td_m_note > div").length > 4) {
                    $("#td_m_note > div:eq(0)").remove();
                }
                $("<div>"+data.m_note+"<br>"+
                    "<span style='font-size:9px; color:#888888; font-style:italic;'>"+
                        "<?= $lang['create_by'] ?>"+ data.m_create_by + "<?= $lang['on'] ?>" +data.m_create_on+
                    "</span>"+
                "</div>").insertBefore("#m_create_note");
                $("input[name='m_note']").val("");
            }
            if (data.add_s_note) {
                if ($("#td_s_note > div").length > 4) {
                    $("#td_s_note > div:eq(0)").remove();
                }
                $("<div>"+data.s_note+"<br>"+
                    "<span style='font-size:9px; color:#888888; font-style:italic;'>"+
                        "<?= $lang['create_by'] ?>"+ data.s_create_by + "<?= $lang['on'] ?>" +data.s_create_on+
                    "</span>"+
                "</div>").insertBefore("#s_create_note");
                $("input[name='s_note']").val("");
            }
            $("#note_for_product").html("Note:<font color='blue'>It is update succeed</font>");
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
                $("#note_for_product").html("Note:<font color='red'>It is update error, "+errorThrown+"</font>");
        }
    });

}

if(jQuery('input#update_pricing_tool').length  > 0){
var status = $("select[name='status'] option:selected").val();
lockqty(status);
}

function lockqty(value) {
    if (value == 'O') {
        $("input[name='webqty']").attr('readOnly', true);
    }
    else {
        $("input[name='webqty']").attr('readOnly', false);
    }
}

function check_sub_cat_margin(platform_type, platform, sku) {
    if (platform_type == 'WEBSITE') {
        var selected = $("select[name='auto_price["+platform+"]'] option:selected").val();
        if (selected) {
            if (selected == "Y") {
                if (!$("input[id='sub_cat_margin["+platform+"]']").val()) {
                    alert('Please set the Sub Cat Margin before auto pricing');
                    $("select[name='auto_price["+platform+"]']").find("option[value='N']").attr("selected",true);
                } else {
                    $("input[id='sp["+platform+"]']").attr('readOnly', true);
                    $("input[id='sp["+platform+"]']").val($("input[id='auto_calc_price["+platform+"]']").val());
                    rePrice(platform_type, platform, sku);
                }
            } else {
                $("input[id='sp["+platform+"]']").attr('readOnly', false);
                var is_price = false;
                if ( $("span#"+platform+"_price").length > 0 && selected == "N") {
                    if ($("span#"+platform+"_price").html() > 0) {
                        $("input[id='sp["+platform+"]']").val($("span#"+platform+"_price").html());
                        is_price = true;
                    }
                }
                if (is_price == false) {
                    $("input[id='sp["+platform+"]']").val($("input[id='origin_price["+platform+"]']").val());
                }
                rePrice(platform_type, platform, sku);
            }
        }
    }
}

// function showHide_with_eleid(target_ele) {
//     console.log(target_ele);
//     var target = document.getElementById(target_ele);
//     console.log(target);
//     target.style.display = 'block';
// }

function showHide(platform_type, platform) {
    if (platform) {
        var target = 'prow_' + platform;
        var sign = 'sign_' + platform;
        var sp = 'sp_' + platform;
        var tobj = document.getElementById(target);
        var sobj = document.getElementById(sign);
        var spobj = document.getElementById(sp);
        if (tobj && sobj && spobj) {
            if (tobj.style.display == 'block') {
                tobj.style.display = 'none';
                sobj.innerHTML = '+';
                spobj.style.display = 'block';
            }
            else if (tobj.style.display == 'none') {
                tobj.style.display = 'block';
                sobj.innerHTML = '-';
                spobj.style.display = 'none';
            }
            else {
                return;
            }
        }
    }
    if (platform_type == 'WEBSITE') {
        if ($("input[id='sub_cat_margin["+platform+"]']").val()) {
            if ($("select[name='auto_price["+platform+"]'] option:selected").val() == "Y") {
                $("input[id='sp["+platform+"]']").attr('readOnly', true);
            }
        }
    }
}
