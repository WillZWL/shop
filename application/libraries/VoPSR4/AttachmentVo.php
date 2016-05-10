<?php
class AttachmentVo extends \BaseVo
{
    private $id;
    private $tpl_id;
    private $lang_id = 'en';
    private $name;
    private $description;
    private $att_file;

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

    public function setTplId($tpl_id)
    {
        if ($tpl_id !== null) {
            $this->tpl_id = $tpl_id;
        }
    }

    public function getTplId()
    {
        return $this->tpl_id;
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

    public function setDescription($description)
    {
        if ($description !== null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setAttFile($att_file)
    {
        if ($att_file !== null) {
            $this->att_file = $att_file;
        }
    }

    public function getAttFile()
    {
        return $this->att_file;
    }

}
