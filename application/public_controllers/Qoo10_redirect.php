<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Qoo10_redirect
{
    public function index($page_type = "home")
    {
        # SBF #2558 qoo10_description_sg.html leads here so we don't have to update all qoo10 listing when urls change
        switch ($page_type) {
            case "home":
                $url = "http://www.qoo10.sg/shop/valuebasketsg";
                break;

            case "cat_1":
                $url = "http://list.qoo10.sg/gmkt.inc/MiniShop/Default.aspx?keyword=&gdlc_cd=100000014&sell_cust_no=ChMdS17DHNdBp5hI5vwALg%3d%3d&search_mode=basic";
                break;

            case "cat_2":
                $url = "http://list.qoo10.sg/gmkt.inc/MiniShop/Default.aspx?keyword=&gdlc_cd=100000011&sell_cust_no=ChMdS17DHNdBp5hI5vwALg%3d%3d&search_mode=basic";
                break;

            case "cat_3":
                $url = "http://list.qoo10.sg/gmkt.inc/MiniShop/Default.aspx?keyword=&gdlc_cd=100000013&sell_cust_no=ChMdS17DHNdBp5hI5vwALg%3d%3d&search_mode=basic";
                break;

            case "cat_4":
                $url = "http://list.qoo10.sg/gmkt.inc/MiniShop/Default.aspx?keyword=&gdlc_cd=100000006&sell_cust_no=ChMdS17DHNdBp5hI5vwALg%3d%3d&search_mode=basic";
                break;

            case "cat_5":
                $url = "http://list.qoo10.sg/gmkt.inc/MiniShop/Default.aspx?keyword=&gdlc_cd=100000008&sell_cust_no=ChMdS17DHNdBp5hI5vwALg%3d%3d&search_mode=basic";
                break;

            case "cat_6":
                $url = "http://www.qoo10.sg/gmkt.inc/MiniShop/?minishop_bar_onoff=N&keyword_hist=&sell_coupon_cust_no=&sell_cust_no=ChMdS17DHNdBp5hI5vwALg%3D%3D&theme_sid=0&page_type=&global_yn=N&qid=0&search_mode=basic&fbidx=0&group_code=&gdlc_cd=&gdmc_cd=&gdsc_cd=&delivery_group_no=&curPage=1&filterDelivery=NNNNNNNN&flt_pri_idx=0&flt_tab_idx=0&sortType=SORT_GD_NO&dispType=GALLERY3&pageSize=60&partial=on&goodscode_for_css=&shipto=&basis=&adult=&brandnm=&flowCheck=&shipFromNation=&qshopshipto=&global_yn_check=N&goodsStorePickup=N&priceMin=&priceMax=&paging_value=1#anchor_detail_top";
                break;

            case "cat_7":
                $url = "http://www.qoo10.sg/gmkt.inc/MiniShop/?minishop_bar_onoff=N&keyword_hist=&sell_coupon_cust_no=&sell_cust_no=ChMdS17DHNdBp5hI5vwALg%3D%3D&theme_sid=0&page_type=&global_yn=N&qid=0&search_mode=basic&fbidx=-1&group_code=&gdlc_cd=&gdmc_cd=&gdsc_cd=&delivery_group_no=&curPage=1&filterDelivery=NNNNNNNN&flt_pri_idx=0&flt_tab_idx=0&sortType=SORT_RANK_POINT&dispType=GALLERY3&pageSize=60&partial=on&goodscode_for_css=&shipto=&basis=&adult=&brandnm=&flowCheck=&shipFromNation=&qshopshipto=&global_yn_check=N&goodsStorePickup=N&priceMin=&priceMax=&paging_value=1#anchor_detail_top";
                break;

            case "cat_8":
                $url = "http://www.qoo10.sg/gmkt.inc/MiniShop/?minishop_bar_onoff=N&keyword_hist=&sell_coupon_cust_no=&sell_cust_no=ChMdS17DHNdBp5hI5vwALg%3D%3D&theme_sid=0&page_type=&global_yn=N&qid=0&search_mode=&fbidx=-1&group_code=&gdlc_cd=&gdmc_cd=&gdsc_cd=&delivery_group_no=&curPage=1&filterDelivery=NNNNNNNN&flt_pri_idx=0&flt_tab_idx=0&sortType=SORT_RANK_POINT&dispType=GALLERY3&pageSize=60&partial=on&goodscode_for_css=&shipto=&basis=TD&adult=&brandnm=&flowCheck=&shipFromNation=&qshopshipto=&global_yn_check=&goodsStorePickup=N&priceMin=&priceMax=&paging_value=1#anchor_detail_top";
                break;

            default:
                $url = "http://www.qoo10.sg/shop/valuebasketsg";
                break;
        }

        header("Location: " . $url);
    }
}

