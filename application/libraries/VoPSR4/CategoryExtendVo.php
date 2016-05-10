<?php
class CategoryExtendVo extends \BaseVo
{
    private $id;
    private $cat_id;
    private $lang_id;
    private $name;
    private $stop_sync_name = '0';

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

    public function setCatId($cat_id)
    {
        if ($cat_id !== null) {
            $this->cat_id = $cat_id;
        }
    }

    public function getCatId()
    {
        return $this->cat_id;
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

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setStopSyncName($stop_sync_name)
    {
        //if ($stop_sync_name) {
            $this->stop_sync_name = $stop_sync_name;
        //}
    }

    public function getStopSyncName()
    {
        return $this->stop_sync_name;
    }

}
