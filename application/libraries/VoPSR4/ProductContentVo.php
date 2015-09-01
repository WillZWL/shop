<?php
class ProductContentVo extends \BaseVo
{
    private $id;
    private $prod_sku;
    private $lang_id;
    private $prod_name;
    private $prod_name_original;
    private $short_desc;
    private $contents;
    private $contents_original;
    private $series;
    private $keywords;
    private $keywords_original;
    private $model_1;
    private $model_2;
    private $model_3;
    private $model_4;
    private $model_5;
    private $detail_desc;
    private $detail_desc_original;
    private $extra_info;
    private $website_status_long_text;
    private $website_status_short_text;
    private $youtube_id_1;
    private $youtube_id_2;
    private $youtube_caption_1;
    private $youtube_caption_2;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdNameOriginal($prod_name_original)
    {
        $this->prod_name_original = $prod_name_original;
    }

    public function getProdNameOriginal()
    {
        return $this->prod_name_original;
    }

    public function setShortDesc($short_desc)
    {
        $this->short_desc = $short_desc;
    }

    public function getShortDesc()
    {
        return $this->short_desc;
    }

    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function setContentsOriginal($contents_original)
    {
        $this->contents_original = $contents_original;
    }

    public function getContentsOriginal()
    {
        return $this->contents_original;
    }

    public function setSeries($series)
    {
        $this->series = $series;
    }

    public function getSeries()
    {
        return $this->series;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setKeywordsOriginal($keywords_original)
    {
        $this->keywords_original = $keywords_original;
    }

    public function getKeywordsOriginal()
    {
        return $this->keywords_original;
    }

    public function setModel1($model_1)
    {
        $this->model_1 = $model_1;
    }

    public function getModel1()
    {
        return $this->model_1;
    }

    public function setModel2($model_2)
    {
        $this->model_2 = $model_2;
    }

    public function getModel2()
    {
        return $this->model_2;
    }

    public function setModel3($model_3)
    {
        $this->model_3 = $model_3;
    }

    public function getModel3()
    {
        return $this->model_3;
    }

    public function setModel4($model_4)
    {
        $this->model_4 = $model_4;
    }

    public function getModel4()
    {
        return $this->model_4;
    }

    public function setModel5($model_5)
    {
        $this->model_5 = $model_5;
    }

    public function getModel5()
    {
        return $this->model_5;
    }

    public function setDetailDesc($detail_desc)
    {
        $this->detail_desc = $detail_desc;
    }

    public function getDetailDesc()
    {
        return $this->detail_desc;
    }

    public function setDetailDescOriginal($detail_desc_original)
    {
        $this->detail_desc_original = $detail_desc_original;
    }

    public function getDetailDescOriginal()
    {
        return $this->detail_desc_original;
    }

    public function setExtraInfo($extra_info)
    {
        $this->extra_info = $extra_info;
    }

    public function getExtraInfo()
    {
        return $this->extra_info;
    }

    public function setWebsiteStatusLongText($website_status_long_text)
    {
        $this->website_status_long_text = $website_status_long_text;
    }

    public function getWebsiteStatusLongText()
    {
        return $this->website_status_long_text;
    }

    public function setWebsiteStatusShortText($website_status_short_text)
    {
        $this->website_status_short_text = $website_status_short_text;
    }

    public function getWebsiteStatusShortText()
    {
        return $this->website_status_short_text;
    }

    public function setYoutubeId1($youtube_id_1)
    {
        $this->youtube_id_1 = $youtube_id_1;
    }

    public function getYoutubeId1()
    {
        return $this->youtube_id_1;
    }

    public function setYoutubeId2($youtube_id_2)
    {
        $this->youtube_id_2 = $youtube_id_2;
    }

    public function getYoutubeId2()
    {
        return $this->youtube_id_2;
    }

    public function setYoutubeCaption1($youtube_caption_1)
    {
        $this->youtube_caption_1 = $youtube_caption_1;
    }

    public function getYoutubeCaption1()
    {
        return $this->youtube_caption_1;
    }

    public function setYoutubeCaption2($youtube_caption_2)
    {
        $this->youtube_caption_2 = $youtube_caption_2;
    }

    public function getYoutubeCaption2()
    {
        return $this->youtube_caption_2;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
