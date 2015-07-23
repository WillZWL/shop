<?php ////////////google analytics
$google_acct_cd = "UA-30728445-1";
?>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '<?=$google_acct_cd;?>']);
    _gaq.push(['_setDomainName', 'none']);
    _gaq.push(['_setAllowLinker', true]);
    _gaq.push(['_trackPageview']);
    <?php
    if (isset($data["so"]))
    {
        $so = $data["so"];
        $so_ext = $data["so_ext"];
        $so_items = $data["so_items"];
        $country = $data["country"];

        if ($data["is_dev_site"] == 0)
        {
            print "_gaq.push(['_addTrans', '" . $so->get_so_no() . "', '" . $so_ext->get_conv_site_id() . " " . $so->get_currency_id() . "','" . $so->get_amount(). "', '', '" . $so->get_delivery_charge() . "', '" . $so->get_delivery_city() . "', '" . $so->get_delivery_state() . "', '" . $country->get_name() . "']);\n";
            foreach ($so_items as $so_item)
            {
                print "_gaq.push(['_addItem', '" . $so->get_so_no() . "', '" . $so_item->get_prod_sku() . "', '" . $so_item->get_name() . "','" . $so_item->get_cat_name() . "','" . $so_item->get_unit_price() . "','" . $so_item->get_qty() . "']);\n";
            }

            print "_gaq.push(['_set', 'currencyCode', '".$so->get_currency_id()."']);\n";
            print "_gaq.push(['_trackTrans']);\n";
        }
    }
    ?>
    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
</script>
<?php ////////////google analytics ?>
