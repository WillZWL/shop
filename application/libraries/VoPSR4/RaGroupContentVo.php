<?php
class RaGroupContentVo extends \BaseVo
{
    private $group_id;
    private $group_display_name;
    private $lang_id;

    protected $primary_key = ['group_id', 'lang_id'];
    protected $increment_field = 'group_id';

    public function setGroupId($group_id)
    {
        if ($group_id !== null) {
            $this->group_id = $group_id;
        }
    }

    public function getGroupId()
    {
        return $this->group_id;
    }

    public function setGroupDisplayName($group_display_name)
    {
        if ($group_display_name !== null) {
            $this->group_display_name = $group_display_name;
        }
    }

    public function getGroupDisplayName()
    {
        return $this->group_display_name;
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

}
