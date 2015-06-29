<?php
class Home_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('service/website_service');
        $this->load->library('service/language_service');
        $this->load->library('service/country_service');
        $this->load->library('service/selling_platform_service');
        $this->load->library('service/display_banner_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/customer_service_info_service');
    }

    public function get_content()
    {
        $lang_id = isset($_SESSION['lang_id']) ? $_SESSION['lang_id'] : 'en';
        return $this->website_service->get_home_content($lang_id);
    }

    public function gen_home_best_seller_grid()
    {
        $str = '<table border="0" cellpadding="0" cellspacing="0" width="940">';

        $cat_list = $this->website_service->get_home_category_info_list();
        $row_limit = 3;
        $col_limit = 3;
        $pop_sub_cat_limit = 6;
        $pop_brand_limit = 6;

        for ($row_count = 0; $row_count < $row_limit; $row_count++)
        {
            $str .= '
                <tr>';

            for ($col_count = 0; $col_count < $col_limit; $col_count++)
            {
                if ($col_count > 0)
                {
                    $str .= '
                    <td width="8">&nbsp;</td>';
                }

                $str .= '
                    <td height="230" width="308" align="left" valign="top" background="images/indexbox.gif">
                        <table border="0" cellpadding="0" cellspacing="0" width="308">
                            <tr>
                                <td height="36" align="left" colspan="2">&nbsp;&nbsp;<b style="font-size:12px">
                                    ';

                $item = current($cat_list);
                next($cat_list);

                if (!($item === FALSE))
                {
                    $str .= '<a href="' . str_replace(" ", "-", parse_url_char($item["category"]->get_name())) . '/cat/?catid=' . $item["category"]->get_id() . '">';
                    $str .= $item["category"]->get_name();
                    $str .= '</a>';
                }

                $str .= '</b></td>
                            </tr>
                            <tr>
                                <td width="152" height="120" align="left" valign="top" style="padding-left:8px;padding-right:14px">
                ';

                $pos = $row_count * 3 + $col_count;

                if (!($item === FALSE))
                {
                    $str .= '
                                    <a href="<?php echo base_url() . str_replace(\' \', \'-\', parse_url_char($best_seller[' . $pos
                        . '][\'product\'][\'name\']));?>/mainproduct/view/'
                        . '<?php echo $best_seller[' . $pos . '][\'product\'][\'selection\'];?>"
                                        <img border="0" src="<?php echo $best_seller[' . $pos
                        . '][\'product\'][\'image_file\'];?>" height="70" vspace="4"><br>
                                            <b style="font-size:12px"><?php echo $best_seller[' . $pos
                        . '][\'product\'][\'name\'];?></b>
                                    </a>';
                }

                $str .= '
                                </td>
                                <td width="160" align="left" valign="top" style="padding-top:6px" rowspan="2">
                                    <b style="font-size:12px;color:#FF6600">Popular Categories</b><br>
                                        ';

                if (!($item === FALSE) && $item["pop_sub_cat_list"])
                {
                    for ($pop_sub_cat_count = 0; $pop_sub_cat_count < $pop_sub_cat_limit
                        && !(($cur_sub_cat = current($item["pop_sub_cat_list"])) === FALSE); $pop_sub_cat_count++)
                    {
                        $str .= '<a href="'
                            . str_replace(" ", "-", parse_url_char($cur_sub_cat->get_name())) . '/cat/?catid=' . $cur_sub_cat->get_id() . '">';
                        $str .= $cur_sub_cat->get_name();
                        $str .= " (" . $cur_sub_cat->get_count_row() . ")</a><br>";
                        next($item["pop_sub_cat_list"]);
                    }
                }

                $str .= '<br>
                                    <b style="font-size:12px;color:#FF6600">Popular Brands</b><br>
                                        ';


                if (!($item === FALSE) && $item["pop_brand_list"])
                {
                    for ($pop_brand_count = 0; $pop_brand_count < $pop_brand_limit
                        && !(($cur_brand = current($item["pop_brand_list"])) === FALSE); $pop_brand_count++)
                    {
                        if ($pop_brand_count > 0)
                        {
                            $str .= ":";
                        }
                        $str .= '<a href="'
                            . str_replace(" ", "-", parse_url_char($cur_brand->get_brand_name())) . '/brand?brandid=' . $cur_brand->get_brand_id() . '">';
                        $str .= $cur_brand->get_brand_name();
                        $str .= "</a>";
                        next($item["pop_brand_list"]);
                    }
                }

                $str .= '<br>
                                </td>
                                </tr>
                                <tr>
                                    <td width="152" height="36" align="left" valign="bottom" style="padding-left:6px;padding-bottom:6px">';

                if (!($item === FALSE))
                {
                    $str .= '
                                        <del>Previous Price &pound;<?php echo $best_seller['. $pos
                        . '][\'product\'][\'rrp\'];?></del><br>
                                        <b style="font-size:18px;color:#FF6600">&pound;<?php echo $best_seller[' . $pos
                        . '][\'product\'][\'price\'];?></b>';
                }

                $str .= '
                                </td>
                            </tr>
                        </table>
                    </td>
                ';
            }
            $str .= '
                </tr>';
        }

        $str .= '
            </table>';

        $menu_file = "../app/public_views/auto/best_seller_home.php";
        file_put_contents($menu_file, $str);
        chown($menu_file, "apache");
        chgrp($menu_file, "users");
        chmod($menu_file, 0664);

    }

    public function gen_footer_cat_menu()
    {
        $lang_list = $this->language_service->get_list();
        foreach($lang_list as $lang_obj)
        {
            $lang_id = $lang_obj->get_id();

            if(!$list = $this->website_service->get_footer_menu_list($lang_id))
            {
                $list = $this->website_service->get_footer_menu_list('en');
            }

            $count = 0;
            $str = "";
            $str .= "<table width='1020' border='0' align='center' cellpadding='0' cellspacing='0'>
                        <tr>";
            $width = floor(100 / count($list["menu_list"]));
            foreach($list["menu_list"] as $menu_header)
            {
                $count++;
                $str .= "<td width='<?=$width?>%' valign='top'>
                        <table width='100%' border='0' cellspacing='5' cellpadding='0'>
                        <tr>
                            <td><font face='Arial, Helvetica, sans-serif' color='#00aff0' size='-1'><strong>".$menu_header->get_name()."</strong></font><br />
                                <br style='line-height:4px' />
                                <font face='Arial, Helvetica, sans-serif' color='#666666' size='-1'>";
                foreach($list['menu_item_list'][$menu_header->get_menu_id()] as $menu_item)
                {
                    $str .= "<a href='".$menu_item->get_link()."'>".$menu_item->get_name()."</a><br>";
                }
                $str .= "   </font></td>
                        </tr>
                        </table>
                        </td>";

                if($count != sizeof($list['menu_list']))
                {
                    $str .= "<td width='2'><img src='/images/01index_98.gif' width='2' height='100' /></td>";
                }
            }
            $str .= "</tr></table>";

            $menu_file = "../app/public_views/footer_menu_".$lang_obj->get_id().".html";
            file_put_contents($menu_file, $str);
            chown($menu_file, "apache");
            chgrp($menu_file, "users");
            chmod($menu_file, 0664);
        }
    }

    public function gen_select_country_grid()
    {
        $platform_list = $this->platform_biz_var_service->get_list_w_country_name(array("s.type"=>"WEBSITE", "s.status"=>1, "pbv.language_id"=>"en", "c.allow_sell"=>1), array("orderby"=>"c.name"));

        foreach($platform_list as $obj)
        {
            $cur_country_id = $obj->get_platform_country_id();
            $option_str .= '<option<?=PLATFORMCOUNTRYID=="'.$cur_country_id.'"?" SELECTED":""?> value="'.$cur_country_id.'"><?="'.$obj->get_platform_country().'"?></option>
                    ';
            $platform_currency[$cur_country_id] = $obj->get_platform_currency_id();
        }

        $currency_str = json_encode($platform_currency);

        $str .= '
                <table border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="7" height="6"><img src="<?=base_url()?>images/borders_01.png" width="8" height="15" /></td>
                        <td width="309" background="<?=base_url()?>images/borders_02.png" style="background-repeat:no-repeat"><img src="<?=base_url()?>images/borders_02.png" width="309" height="15" /></td>
                        <td width="7" height="6"><img src="<?=base_url()?>images/borders_03.png" width="8" height="15" /></td>
                    </tr>
                    <tr>
                        <td background="<?=base_url()?>images/borders_12.png" style="background-repeat:repeat"><img src="<?=base_url()?>images/borders_12.png" width="8" height="79" /></td>
                        <td>
                            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td height="60px" align="center" width="100%">
                                        <script src="/js/common.js" type="text/javascript"></script>
                                        <?php
                                            $back_url = urlencode(current_url().($_SERVER["QUERY_STRING"] ? "?".$_SERVER["QUERY_STRING"] : ""));
                                            $domain = check_domain();
                                            if ($_SESSION["cart"][PLATFORMID])
                                            {
                                                include_once(APPPATH."helpers/string_helper.php");
                                                echo \'
                                                    <script type="text/javascript">var platform_currency = ' . $currency_str . ';</script>
                                                    \';
                                                $chk_cart = base64_encode(serialize($_SESSION["cart"][PLATFORMID]));
                                                $on_change = "if (confirm(\"Please note that your shopping cart will be displayed in \" + platform_currency[this.value] +\"  Do you wish to continue?\")){setcookie(\"chk_cart\", \"{$chk_cart}\", 0, \"/\", \".{$domain}\");setcookie(\"back_url\", \"{$back_url}\", 0, \"/\", \".{$domain}\"); this.form.submit()}";
                                            }
                                            else
                                            {
                                                $on_change = "setcookie(\"back_url\", \"{$back_url}\", 0, \"/\", \".{$domain}\");this.form.submit();";
                                            }
                                                                                <form name="fm_custom_country" id="fm_custom_country" method="post">
                                            <select name="custom_country_id" id="custom_country_id" onChange=\'<?=$on_change?>\'>
                                            '.$option_str.'
                                            </select><br />
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td background="<?=base_url()?>images/borders_14.png" style="background-repeat:repeat"><img src="<?=base_url()?>images/borders_14.png" width="8" height="79" /></td>
                    </tr>
                    <tr>
                        <td width="7" height="6"><img src="<?=base_url()?>images/borders_21.png" width="8" height="8" /></td>
                        <td background="<?=base_url()?>images/borders_22.png" style="background-repeat:no-repeat"><img src="<?=base_url()?>images/borders_22.png" width="309" height="8" /></td>
                        <td width="7" height="6"><img src="<?=base_url()?>images/borders_23.png" width="8" height="8" /></td>
                    </tr>
                </table>
                ';
        $menu_file = "../app/public_views/footer_country_grid.php";
        file_put_contents($menu_file, $str);
    }

    public function get_contact_page_content()
    {
        return $this->customer_service_info_service->get(array("platform_id"=>PLATFORMID));
    }

    public function get_cat_url($cat_id, $relative_path = FALSE)
    {
        return $this->website_service->get_cat_url($cat_id, $relative_path);
    }

    public function get_prod_url($sku, $relative_path = FALSE)
    {
        return $this->website_service->get_prod_url($sku, $relative_path);
    }
}
/* End of file home_model.php */
/* Location: ./app/models/website/home_model.php */