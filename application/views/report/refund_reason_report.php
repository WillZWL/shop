<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<link rel="stylesheet" href="<?=base_url()?>js/jquery-ui-1.10.4/css/ui-lightness/jquery-ui-1.10.4.min.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery-1.11.1/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery-ui-1.10.4/js/jquery-ui-1.10.4.min.js"></script>
<script language="javascript">
<!--
/*function prepareSubmit()
{
    var keyword = document.getElementById('prod_name').value;
    var sku = document.getElementById('psku').value;
    plistframe.list.keyword.value = keyword;
    plistframe.list.sku.value = sku;
    plistframe.list.submit();
}*/
-->
</script>
</head>
<?php
$today = getdate();
?>
<body onResize="SetFrameFullHeight(document.getElementById('report'));">
<div id="main">
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
    <td align="left" class="title" height="30"><b style="font-size: 16px; color: rgb(0, 0, 0);"><?=$lang["title"]?></b></td>
</tr>
<tr>
    <td height="2" bgcolor="#000033"></td>
</tr>
</table>
<form name="fm" action="<?=base_url()."report/refund_reason_report/export_csv"?>" method="post" target="report">
<input type="hidden" name="is_query" value="1">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
<tr height="70">
    <td align="left" style="padding-left:8px;"><b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
    <td align="right">
    <table border="0" cellpadding="2" cellspacing="0">
    <col width="780"><col width="200"><col width="40">
    <tr>
        <td align='right'><b>Choose Week:</b></td>
        <td>
            <div class="week-picker"></div>
            <br /><br />
            <label>Week :</label> <span id="startDate"></span> - <span id="endDate"></span>
            <input type="hidden" id="startD" name="startD" value="">
            <input type="hidden" id="endD" name="endD" value="">
        </td>
        <td rowspan="2" align="center"><input type="submit" value="" class="search_button" style="background: url('<?=base_url()?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;"> &nbsp; </td>
    </tr>
    </table>
    </td>
</tr>
<tr>
    <td height="2" bgcolor="#000033" colspan="3"></td>
</tr>
</table>
</form>
<iframe name="report" id="report" src="<?=base_url()."report/refund_reason_report/export_csv"?>" width="1259" style="float:left;border-right:1px solid #000000;" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>
</div>
<script type="text/javascript">
    $(function() {
        var startDate;
        var endDate;
        
        var selectCurrentWeek = function() {
            window.setTimeout(function () {
                $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
            }, 1);
        }
        
        $('.week-picker').datepicker( {
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            onSelect: function(dateText, inst) { 
                var date = $(this).datepicker('getDate');
                startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
                endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7);
                //var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
                //$('#startDate').text($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
                //$('#endDate').text($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
                $('#startDate').text($.datepicker.formatDate('yy-mm-dd', startDate));
                $('#endDate').text($.datepicker.formatDate('yy-mm-dd', endDate));               
                $('#startD').val($.datepicker.formatDate('yy-mm-dd', startDate));
                $('#endD').val($.datepicker.formatDate('yy-mm-dd', endDate));
                selectCurrentWeek();
            },
            beforeShowDay: function(date) {
                var cssClass = '';
                if(date >= startDate && date <= endDate)
                    cssClass = 'ui-datepicker-current-day';
                return [true, cssClass];
            },
            onChangeMonthYear: function(year, month, inst) {
                selectCurrentWeek();
            }
        });
        
        $('.week-picker .ui-datepicker-calendar tr').on('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
        $('.week-picker .ui-datepicker-calendar tr').on('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });
    });
</script>
</body>
</html>