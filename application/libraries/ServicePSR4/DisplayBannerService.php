<?php
namespace ESG\Panther\Service;

class DisplayBannerService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_display($where)
    {
        return $this->get_d_dao()->get($where);
    }

    public function getDisplayList($where = [], $option = [])
    {
        return $this->getDao('Display')->getList($where, $option);
    }

    public function getDbcNumRows($where)
    {
        return $this->getDao('DisplayBannerConfig')->getNumRows($where);
    }

    public function getDbNumRows($where)
    {
        return $this->getDao('DisplayBanner')->get_num_rows($where);
    }

    public function getDisplayBannerConfig($where)
    {
        return $this->getDao('DisplayBannerConfig')->get($where);
    }

    public function getDisplayBanner($where = [])
    {
        return $this->getDao('DisplayBanner')->get($where);
    }

    public function getDisplayBannerList($where, $option)
    {
        return $this->getDao('DisplayBanner')->getList($where, $option);
    }

    public function getDbWithGraphic($banner_type = '', $display_id = '', $position_id = '', $slide_id = '', $country_id ='', $lang_id = '', $usage = '', $backup_image = '')
    {
        return $this->getDao('DisplayBanner')->getDbWithGraphic($banner_type, $display_id, $position_id, $slide_id, $country_id, $lang_id, $usage, $backup_image);
    }

    public function getDisplayBannerConfigList($where = [], $option)
    {
        return $this->getDao('DisplayBannerConfig')->getList($where, $option);
    }

    public function insertDisplayBannerConfig($obj)
    {
        return $this->getDao('DisplayBannerConfig')->insert($obj);
    }

    public function updateDisplayBannerConfig($obj)
    {
        return $this->getDao('DisplayBannerConfig')->update($obj);
    }

    public function getDifferentCountryList($display_id, $lang_id)
    {
        return $this->getDao('DisplayBannerConfig')->getDifferentCountryList($display_id, $lang_id);
    }

    public function getPublishBanner($display_id = '', $position_id = '', $country_id = '', $lang_id = '', $usage = "PB")
    {
        define('GRAPHIC_PH', $this->getService('ContextConfig')->valueOf("default_graphic_path"));
        //get country banner
        $dbc = $this->getDao('DisplayBannerConfig')->get(array('display_id' => $display_id, 'usage' => $usage, 'country_id' => $country_id, 'lang_id' => $lang_id, 'position_id' => $position_id, 'status' => 1));
        if (!$dbc) {
            $dbc = $this->getDao('DisplayBannerConfig')->get(array('display_id' => $display_id, 'usage' => $usage, 'country_id IS NULL' => NULL, 'lang_id' => $lang_id, 'position_id' => $position_id, 'status' => 1));
            if (!$dbc) {
                $dbc = $this->getDao('DisplayBannerConfig')->get(array('display_id' => $display_id, 'usage' => $usage, 'country_id IS NULL' => NULL, 'lang_id' => 'en', 'position_id' => $position_id, 'status' => 1));
            }
        }
        if ($dbc) {
            $banner["publish_key"] = $dbc->getDisplayId() . "_" . $dbc->getPositionId();
            $banner["banner_height"] = $dbc->getHeight();
            $banner["banner_width"] = $dbc->getWidth();
            $banner["banner_type"] = $dbc->getBannerType();
            $dbc_id = $dbc->getId();
            $country_id = $dbc->getCountryId();
            $lang_id = $dbc->getLangId();
            $banner["backup_graphic"] = $banner["backup_link_type"] = $banner["backup_link"] = $banner["time_interval"] = "";
            $redirect_link = $link_type = $graphic = array();
            if ($banner["banner_type"] == "I") {
                $db_obj = $this->getDao('DisplayBanner')->getDbWithGraphic($banner["banner_type"], $display_id, $position_id, 0, $country_id, $lang_id, $usage, FALSE);
                if ($db_obj) {
                    if ($db_obj->getStatus() == 1 && file_exists(GRAPHIC_PH . $db_obj->getGraphicLocation() . $db_obj->getGraphicFile())) {
                        $redirect_link[0] = $db_obj->getLink();
                        $link_type[0] = $db_obj->getLinkType();
                        $graphic[0] = "/" . GRAPHIC_PH . $db_obj->getGraphicLocation() . $db_obj->getGraphicFile();
                        $graphic_location[0] = $db_obj->getGraphicLocation();
                        $graphic_file[0] = $db_obj->getGraphicFile();
                    }
                }
            } elseif ($banner["banner_type"] == "F") {
                $db_obj = $this->getDao('DisplayBanner')->getDbWithGraphic($banner["banner_type"], $display_id, $position_id, 0, $country_id, $lang_id, $usage);
                if ($db_obj->get_status() == 1 && file_exists(GRAPHIC_PH . $db_obj->getGraphicLocation() . $db_obj->getGraphicFile())) {
                    $redirect_link[0] = $db_obj->getLink();
                    $link_type[0] = $db_obj->getLinkType();

                    $file = explode('.', $db_obj->getGraphicFile());
                    $graphic[0] = "/" . GRAPHIC_PH . $db_obj->getGraphicLocation() . $file[0];
                    $graphic_location[0] = $db_obj->getGraphicLocation();
                    $graphic_file[0] = $db_obj->getGraphicFile();
                }
                $image_db_obj = $this->getDao('DisplayBanner')->getDbWithGraphic($banner["banner_type"], $display_id, $position_id, 0, $country_id, $lang_id, $usage, TRUE);
                if ($image_db_obj->get_status() == 1 && file_exists(GRAPHIC_PH . $image_db_obj->getGraphicLocation() . $image_db_obj->getGraphicFile())) {
                    $banner["backup_link"] = $image_db_obj->getLink();
                    $banner["backup_link_type"] = $image_db_obj->getLinkType();
                    $banner["backup_graphic"] = "/" . GRAPHIC_PH . $image_db_obj->getGraphicLocation() . $image_db_obj->getGraphicFile();
                }
            } elseif ($banner["banner_type"] == "R") {
                if ($country_id) {
                    $db_list = $this->getDao('DisplayBanner')->getList(array('position_id' => $position_id, 'display_id' => $display_id, 'usage' => $usage, 'country_id' => $country_id, 'lang_id' => $lang_id, 'status' => 1), array("orderby" => "priority DESC"));
                } else {
                    $db_list = $this->getDao('DisplayBanner')->getList(array('position_id' => $position_id, 'display_id' => $display_id, 'usage' => $usage, 'country_id IS NULL' => NULL, 'lang_id' => $lang_id, 'status' => 1), array("orderby" => "priority DESC"));
                }
                if ($db_list) {
                    $num = 0;
                    foreach ($db_list AS $db_temp) {
                        $db_obj = $this->getDao('DisplayBanner')->getDbWithGraphic($banner["banner_type"], $display_id, $position_id, $db_temp->getSlideId(), $country_id, $lang_id, $usage);
                        $redirect_link[$num] = $db_obj->getLink();
                        $link_type[$num] = $db_obj->getLinkType();
                        $time_interval = $db_obj->getTimeInterval();
                        $graphic[$num] = "/" . GRAPHIC_PH . $db_obj->getGraphicLocation() . $db_obj->getGraphicFile();
                        $graphic_location[$num] = $db_obj->getGraphicLocation();
                        $graphic_file[$num] = $db_obj->getGraphicFile();
                        $num++;
                    }
                    $banner["num"] = $num;
                }
            }
            $banner["redirect_link"] = $redirect_link;
            $banner["link_type"] = $link_type;
            $banner["graphic"] = $graphic;
            $banner["graphic_location"] = $graphic_location;
            $banner["graphic_file"] = $graphic_file;
            $banner["time_interval"] = $time_interval * 1000;
        }

        return $banner;
    }
}


