<?php
class ProductContentVo extends \BaseVo
{
    private $id;
    private $prod_sku;
    private $lang_id;
    private $prod_name = '';
    private $prod_name_original = '0';
    private $short_desc;
    private $contents;
    private $contents_original = '0';
    private $series = '';
    private $keywords;
    private $keywords_original = '0';
    private $model_1 = '';
    private $model_2 = '';
    private $model_3 = '';
    private $model_4 = '';
    private $model_5 = '';
    private $detail_desc;
    private $detail_desc_original = '0';
    private $extra_info;
    private $website_status_long_text;
    private $website_status_short_text = '';
    private $youtube_id_1 = '';
    private $youtube_id_2 = '';
    private $youtube_caption_1 = '';
    private $youtube_caption_2 = '';
    private $stop_sync = '0';
    private $product_url = '';


    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setProdSku($prod_sku)
    {
        if ($prod_sku !== null) {
            $this->prod_sku = $prod_sku;
        }
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setLangId($lang_id)
    {
        if ($lang_id !== null) {
            $this->lang_id = $lang_id;
        }
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setProdName($prod_name)
    {
        if ($prod_name !== null) {
            $this->prod_name = $prod_name;
        }
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdNameOriginal($prod_name_original)
    {
        if ($prod_name_original !== null) {
            $this->prod_name_original = $prod_name_original;
        }
    }

    public function getProdNameOriginal()
    {
        return $this->prod_name_original;
    }

    public function setShortDesc($short_desc)
    {
        if ($short_desc !== null) {
            $this->short_desc = $short_desc;
        }
    }

    public function getShortDesc()
    {
        return $this->short_desc;
    }

    public function setContents($contents)
    {
        if ($contents !== null) {
            $this->contents = $contents;
        }
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function setContentsOriginal($contents_original)
    {
        if ($contents_original !== null) {
            $this->contents_original = $contents_original;
        }
    }

    public function getContentsOriginal()
    {
        return $this->contents_original;
    }

    public function setSeries($series)
    {
        if ($series !== null) {
            $this->series = $series;
        }
    }

    public function getSeries()
    {
        return $this->series;
    }

    public function setKeywords($keywords)
    {
        if ($keywords !== null) {
            $this->keywords = $keywords;
        }
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setKeywordsOriginal($keywords_original)
    {
        if ($keywords_original !== null) {
            $this->keywords_original = $keywords_original;
        }
    }

    public function getKeywordsOriginal()
    {
        return $this->keywords_original;
    }

    public function setModel1($model_1)
    {
        if ($model_1 !== null) {
            $this->model_1 = $model_1;
        }
    }

    public function getModel1()
    {
        return $this->model_1;
    }

    public function setModel2($model_2)
    {
        if ($model_2 !== null) {
            $this->model_2 = $model_2;
        }
    }

    public function getModel2()
    {
        return $this->model_2;
    }

    public function setModel3($model_3)
    {
        if ($model_3 !== null) {
            $this->model_3 = $model_3;
        }
    }

    public function getModel3()
    {
        return $this->model_3;
    }

    public function setModel4($model_4)
    {
        if ($model_4 !== null) {
            $this->model_4 = $model_4;
        }
    }

    public function getModel4()
    {
        return $this->model_4;
    }

    public function setModel5($model_5)
    {
        if ($model_5 !== null) {
            $this->model_5 = $model_5;
        }
    }

    public function getModel5()
    {
        return $this->model_5;
    }

    public function setDetailDesc($detail_desc)
    {
        if ($detail_desc !== null) {
            $this->detail_desc = $detail_desc;
        }
    }

    public function getDetailDesc()
    {
        return $this->detail_desc;
    }

    public function setDetailDescOriginal($detail_desc_original)
    {
        if ($detail_desc_original !== null) {
            $this->detail_desc_original = $detail_desc_original;
        }
    }

    public function getDetailDescOriginal()
    {
        return $this->detail_desc_original;
    }

    public function setExtraInfo($extra_info)
    {
        if ($extra_info !== null) {
            $this->extra_info = $extra_info;
        }
    }

    public function getExtraInfo()
    {
        return $this->extra_info;
    }

    public function setWebsiteStatusLongText($website_status_long_text)
    {
        if ($website_status_long_text !== null) {
            $this->website_status_long_text = $website_status_long_text;
        }
    }

    public function getWebsiteStatusLongText()
    {
        return $this->website_status_long_text;
    }

    public function setWebsiteStatusShortText($website_status_short_text)
    {
        if ($website_status_short_text !== null) {
            $this->website_status_short_text = $website_status_short_text;
        }
    }

    public function getWebsiteStatusShortText()
    {
        return $this->website_status_short_text;
    }

    public function setYoutubeId1($youtube_id_1)
    {
        if ($youtube_id_1 !== null) {
            $this->youtube_id_1 = $youtube_id_1;
        }
    }

    public function getYoutubeId1()
    {
        return $this->youtube_id_1;
    }

    public function setYoutubeId2($youtube_id_2)
    {
        if ($youtube_id_2 !== null) {
            $this->youtube_id_2 = $youtube_id_2;
        }
    }

    public function getYoutubeId2()
    {
        return $this->youtube_id_2;
    }

    public function setYoutubeCaption1($youtube_caption_1)
    {
        if ($youtube_caption_1 !== null) {
            $this->youtube_caption_1 = $youtube_caption_1;
        }
    }

    public function getYoutubeCaption1()
    {
        return $this->youtube_caption_1;
    }

    public function setYoutubeCaption2($youtube_caption_2)
    {
        if ($youtube_caption_2 !== null) {
            $this->youtube_caption_2 = $youtube_caption_2;
        }
    }

    public function getYoutubeCaption2()
    {
        return $this->youtube_caption_2;
    }

    public function setStopSync($stop_sync)
    {
        if ($stop_sync !== null) {
            $this->stop_sync = $stop_sync;
        }
    }

    public function getStopSync()
    {
        return $this->stop_sync;
    }

    public function setProductUrl($product_url)
    {
        if ($product_url !== null) {
            $this->product_url = $product_url;
        }
    }

    public function getProductUrl()
    {
        return $this->product_url;
    }

}
