<?php
include_once 'Base_dto.php';

class Country_lang_name_dto extends Base_dto
{
    private $country_id;
    private $name;
    private $lang_name;
    private $fc_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_country_id()
    {
        return $this->country_id;
    }

    public function set_country_id($country_id)
    {
        $this->country_id = $country_id;
    }

    public function get_fc_id()
    {
        return $this->fc_id;
    }

    public function set_fc_id($fc_id)
    {
        $this->fc_id = $fc_id;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($name)
    {
        $this->name = $name;
    }

    public function get_lang_name()
    {
        return $this->lang_name;
    }

    public function set_lang_name($lang_name)
    {
        $this->lang_name = $lang_name;
    }
}

?>