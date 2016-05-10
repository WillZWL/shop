<?php
class FaqadminVo extends \BaseVo
{
    private $id;
    private $lang_id;
    private $faq_ver;


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

    public function setFaqVer($faq_ver)
    {
        if ($faq_ver !== null) {
            $this->faq_ver = $faq_ver;
        }
    }

    public function getFaqVer()
    {
        return $this->faq_ver;
    }

}
