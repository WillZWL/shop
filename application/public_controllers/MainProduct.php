<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class MainProduct extends PUB_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('price', 'image'));
    }

    public function view($sku = '', $sv = false)
    {
        $data = array();
        if ($sku) {
            $data = $this->getProdInfo($sku);
            $siteobj = \PUB_Controller::$siteInfo;
            $data["countryid"] = $siteobj->getPlatformCountryId();
            $this->load->view('product', $data);
        } else {
            show_404();
        }
    }

    public function getProdInfo($sku)
    {
        $lang_id = $this->get_lang_id();
        if ($this->sc['Price']->get(['sku' => $sku, 'listing_status' => 'L', 'platform_id' => PLATFORM])) {
            $listing_info = $this->sc['Price']->getListingInfo($sku, PLATFORM, $lang_id);
            if ($listing_info) {
                $data['listing_info'] = $listing_info;
                $where = array("p.sku" => $sku, "p.status" => 2, "pbv.selling_platform_id" => PLATFORM, "pc.lang_id" => $lang_id);
                $prod_info = $this->sc['Product']->getDao('Product')->getWebsiteProductInfo($where);
                if (!$prod_info) {
                    $where = array("p.sku" => $sku, "p.status" => 2, "pbv.selling_platform_id" => PLATFORM);
                    $prod_info = $this->sc['Product']->getDao('Product')->getWebsiteProductInfo($where);
                }
                $data['prod_name'] = $listing_info->getProdName();
                $data['prod_price'] = $listing_info->getPrice();
                $data['prod_rrp_price'] = $listing_info->getRrrPrice();
                $data['listing_status'] = $listing_info->getStatus();
                $data["overview"] = nl2br(trim($prod_info->getDetailDesc()));
                if ($prod_info->getSpecification() != "") {
                    $data['specification'] = $prod_info->getSpecification();
                }
                $data['prod_image'] = $this->getProdImage($sku);
                $data['prod_info'] = $prod_info;
                $data['microdata'] = $this->getMicroData($prod_info, $listing_info);
                return $data;
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    public function getProdImage($sku)
    {
        $where = array('sku' => $sku, 'status' => 1);
        $option = array("orderby" => "priority ASC, create_on DESC");
        $prod_image = array();
        if ($img_list = $this->sc['Product']->getDao('ProductImage')->getList($where, $option)) {
            foreach ($img_list AS $key => $prod_img_obj) {
                $prod_image[$key]["image_icon"] = get_image_file($prod_img_obj->getImage(), "s", $prod_img_obj->getSku(), $prod_img_obj->getId());
                $prod_image[$key]["image"] = get_image_file($prod_img_obj->getImage(), "l", $prod_img_obj->getSku(), $prod_img_obj->getId());
            }
        }
        return $prod_image;
    }

    public function getMicroData($prod_info, $listing_info)
    {
        $data['price'] = $listing_info->getPrice();
        $data['currency'] = $listing_info->getCurrencyId();
        $data['brand'] =  $prod_info->getBrandName();
        if (strtolower($prod_info->getCatName()) == "refurbish") {
            $data['itemCondition'] = 'http://schema.org/RefurbishedCondition';
        } else {
            $data['itemCondition'] = 'http://schema.org/NewCondition';
        }

        if ($listing_info->getQty() > 0) {
            $data['availability'] = "http://schema.org/InStock";
        } else {
            $data['availability'] = "http://schema.org/OutOfStock";
        }

        return $data;
    }
}
