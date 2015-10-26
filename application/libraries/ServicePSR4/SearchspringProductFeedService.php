<?php
namespace ESG\Panther\Service;

class SearchspringProductFeedService extends DataFeedService
{
    protected $id = 'Searchspring Product Feed';
    protected $pbv_srv;
    protected $domain;
    protected $image_domain;
    protected $lang_id;

    public function __construct()
    {
        parent::__construct();
/*
        include_once APPPATH . 'libraries/service/Price_website_service.php';
        $this->set_price_srv(new Price_website_service());
        include_once APPPATH . 'libraries/service/Product_service.php';
        $this->set_product_srv(new Product_service());
        include_once APPPATH . 'libraries/service/Price_service.php';
        $this->set_price_srv(new Price_service());
        include_once APPPATH . 'libraries/service/Platform_biz_var_service.php';
        $this->set_pbv_srv(new Platform_biz_var_service());

        include_once(APPPATH . "helpers/image_helper.php");
        include_once(APPPATH . "helpers/MY_url_helper.php");
*/
        $this->pbvService = new PlatformBizVarService();
        $this->priceWebsiteService = new PriceWebsiteService();
        $this->setOutputDelimiter(',');
    }

    public function genDataFeed($platformId)
    {
        if ($this->_init($platformId)) {
//            define('DATAPATH', $this->getDao('Config')->valueOf("data_path"));
//debug
            define('DATAPATH', "D:\\tmp\\");
            $langId = $this->get_lang_id();
            $folderPath = DATAPATH . 'feeds/searchspring/' . $langId;
            $ftpPath = DATAPATH . 'feeds/searchspring/ftp/' . $langId;

            $this->createFolder($folderPath);
            $this->createFolder($ftpPath);

            $where = array('pbv.language_id' => $langId, 'pbv.selling_platform_id' => $platformId);
            if ($filename = $this->getDataFeed($where)) {
                copy($folderPath . '/' . $filename, $ftpPath . '/panther_searchspring_' . strtolower($platformId) . '.xml');
            }
        } else {
            error_log(__METHOD__ . __LINE__ . " " . "Fail to generate SearchSpring feed" . $platformId);
        }
    }

    private function _init($platformId) {
        $siteDto = $this->loadSiteParameterService->loadSiteByPlatform($platformId);
        if ($siteDto) {
            $this->setDomain($siteDto->getDomain());
            $this->setImageDomain($siteDto->getDomain());
            $this->set_lang_id($siteDto->getLangId());
            $this->loadLanguage($siteDto->getLangId());
            return true;
        }
        return false;
    }

    public function get_pbv_srv()
    {
        return $this->pbv_srv;
    }

    public function set_pbv_srv(Base_service $srv)
    {
        $this->pbv_srv = $srv;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function getDataFeed($where = array(), $option = array())
    {
        $filename = $this->genDataList($where, $option);
        return $filename;
    }

    protected function genDataList($where = array(), $option = array())
    {
        $this->delDir(DATAPATH . 'feeds/searchspring/' . $where['pbv.language_id']);
        $filename = 'panther_data_feed_' . $where['pbv.selling_platform_id'] . '.xml';
        $fp = fopen(DATAPATH . 'feeds/searchspring/' . $where['pbv.language_id'] . '/' . $filename, 'w');

        set_time_limit(300);
        $num_rows = $this->productService->getDao()->getSearchspringProductFeed($where, ['num_rows' => 1]);
        $offset = 0;
        $arr = array();

        if ($num_rows > 0) {
            $total = ceil($num_rows / 5000);
        }
        $content = '';
        $content .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content .= '<Products>' . "\n";

        for ($i = 0; $i < $total; $i++) {
            if ($arr = $this->productService->getDao()->getSearchspringProductFeed($where, $option = array('orderby' => 'pr.sku', 'offset' => $i * 5000, 'limit' => 5000))) {
                foreach ($arr as $product_info_dto) {
                    $this->processDataRow($product_info_dto);
                    $data[$product_info_dto->getSku()]['product_info_dto'] = $product_info_dto;
                    $content .= $this->genXml($data);
                    $data = array();
                    if ($content) {
                        if (fwrite($fp, $content)) {
                            $content = '';
                        } else {
                            $subject = '<DO NOT REPLY> Fails to create SearchSpring Product Feed File';
                            $message = "FILE: " . __FILE__ . "<br>
                                         LINE: " . __LINE__;
                            $this->errorHandle($subject, $message);
                        }
                    }
                }
            }
        }
        $content = '</Products>' . "\n";
        fwrite($fp, $content);

        return $filename;
    }

    public function processDataRow($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }

        $replace_domain = 'http://' . $this->getDomain();
        $image_domain = 'http://' . $this->getImageDomain();
        $data->setAddCartUrl($replace_domain . $data->getAddCartUrl());
        $data->setImageUrl($image_domain . get_image_file($data->getImage(), 'm', $data->getSku()));
        $data->setThumbImageUrl($image_domain . get_image_file($data->getImage(), 's', $data->getSku()));
        $data->setProductUrl($replace_domain . $data->getProductUrl());
        if ($data->getPrice() > 0) {
            $rrp = $this->priceWebsiteService->calcWebsiteProductRrp($data->getPrice(), $data->getFixedRrp(), $data->getRrpFactor());
        } else {
            $rrp = 0;
        }
        $data->setRrp($rrp);
        return $data;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getImageDomain()
    {
        return $this->image_domain;
    }

    public function setImageDomain($image_domain)
    {
        $this->image_domain = $image_domain;
    }

    public function genXml($data_list = NULL)
    {
        $lang = $this->lang;
        $website_status = array('I' => $lang["in_stock"], 'O' => $lang["out_stock"], 'P' => $lang["pre_order"], 'A' => $lang["arriving"]);
        $xml_content = '';

        $prev_sku = "";
        foreach ($data_list as $data) {
            $xml_content .= '<Product>' . "\n";
            $xml_content .= '<id>' . $data['product_info_dto']->getSku() . '</id>' . "\n";
            $xml_content .= '<sku>' . $data['product_info_dto']->getSku() . '</sku>' . "\n";
            $xml_content .= '<name>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->getProdName()))) . '</name>' . "\n";
            $xml_content .= '<brand>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->getBrandName()))) . '</brand>' . "\n";
            $xml_content .= '<image_url>' . $data['product_info_dto']->getImageUrl() . '</image_url>' . "\n";
            $xml_content .= '<thumb_image_url>' . $data['product_info_dto']->getThumbImageUrl() . '</thumb_image_url>' . "\n";
            $xml_content .= '<add_cart_url>' . $data['product_info_dto']->getAddCartUrl() . '</add_cart_url>' . "\n";
            $xml_content .= '<description>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->getDetailDesc()))) . '</description>' . "\n";
            $xml_content .= '<create_date>' . $data['product_info_dto']->getCreateDate() . '</create_date>' . "\n";

            if ($data['product_info_dto']->getWebsiteStatus() == 'O') {
                $quantity = 0;
            } else {
                $quantity = $data['product_info_dto']->getQuantity();
            }

            $xml_content .= '<url><![CDATA[' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->getProductUrl()))) . ']]></url>' . "\n";
            $xml_content .= '<price>' . $data['product_info_dto']->getPrice() . '</price>' . "\n";
            $xml_content .= '<msrp>' . $data['product_info_dto']->getRrp() . '</msrp>' . "\n";
            $xml_content .= '<discount_text>' . $lang['save'] . number_format(($data['product_info_dto']->getRrp() == 0 ? 0 : ($data['product_info_dto']->getRrp() - $data['product_info_dto']->getPrice()) / $data['product_info_dto']->getRrp() * 100), 0) . '%</discount_text>' . "\n";
            $xml_content .= '<quantity>' . $quantity . '</quantity>' . "\n";


            $xml_content .= '<categories>' . "\n";
            $xml_content .= '<category>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->getCatName()))) . '</category>' . "\n";
            if ($data['product_info_dto']->getSubCatName() != '') {
                $xml_content .= '<category>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->getCatName() . '/' . $data['product_info_dto']->getSubCatName()))) . '</category>' . "\n";
            }
            $xml_content .= '</categories>' . "\n";

            if ($data['product_info_dto']->getClearance() == 1) {
                $clearance = '111';
            } else {
                $clearance = '0';
            }


            $xml_content .= '<clearance>' . $clearance . '</clearance>' . "\n";
            //$xml_content .= '<clearance>' . $data['product_info_dto']->get_clearance() . '</clearance>' . "\n";
            $xml_content .= '</Product>' . "\n";
        }

        return $xml_content;
    }

    public function getContactEmail()
    {
        return 'itsupport@eservicesgroup.com';
    }

    protected function getDefaultVo2XmlMapping()
    {
        return '';
    }

    protected function getDefaultXml2CsvMapping()
    {
        return '';
    }

    protected function get_ftp_name()
    {
        return 'SEARCHSPRING';
    }

    protected function getSjId()
    {
        return 'SEARCHSPRING_PRODUCT_FEED';
    }

    protected function getSjName()
    {
        return 'SearchSpring Product Feed Cron Time';
    }
}


