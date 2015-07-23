<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head></head>

<body>
<script language="JavaScript" type="text/javascript">
    <!--
    var error = '<?=$enquiry_error;?>';

    if (error != '') {
        window.top.window.EnquiryResultError(error);
    }
    else {
        var result = new Array();
        result['fullname'] = "<?=str_replace(array('\\', '"'), array('\\\\', '\"'), $fullname)?>";
        result['email'] = "<?=str_replace(array('\\', '"'), array('\\\\', '\"'), $email)?>";
        result['subject'] = "<?=str_replace(array('\\', '"'), array('\\\\', '\"'), $subject)?>";
        result['contents'] = "<?=str_replace(array("\r\n", "\r", "\n", '\\', '"'), array("<br />", "<br />", "<br />", '\\\\', '\"'), $contents)?>";
        result['custom_field_error'] = '<?=$custom_field_error?>';

        window.top.window.EnquiryResultSuccess(result);
    }
    -->
</script>
</body>
</html>