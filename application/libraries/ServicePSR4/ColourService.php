<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ColourDao;
use ESG\Panther\Dao\ColourExtendDao;

class ColourService extends BaseService
{
    private $colourExtendDao;

    public function __construct()
    {
        parent::__construct();
        // $this->setDao(new ColourDao);
        // $this->setColourExtendDao(new ColourExtendDao);
    }

    public function getListWithLang($where, $option)
    {
        return $this->getDao('Colour')->getListWithLang($where, $option);
        // return $this->getDao()->getListWithLang($where, $option);
    }

    public function save($data)
    {
        $errorMsg = '';

        $obj = $this->getDao()->get();

        $obj->setColourId($data['colour_id']);
        $obj->setColourName($data['colour_name']);
        $obj->setStatus($data['status']);
        $this->getDao()->insert($obj);

        $nameTranslate = $data["name_translate"];

        $colourExtendVo = $this->getDao('ColourExtendDao')->get();

        foreach ($nameTranslate as $langId => $name) {
            $colourExtendObj = clone $colourExtendVo;
            $colourExtendObj->setColourId($data['colour_id']);
            $colourExtendObj->setLangId($langId);
            $colourExtendObj->setColourName(ucfirst(strtolower($name)));

            $ret = $this->getColourExtendDao()->insert($colourExtendObj);

            if ($ret === false) {
                $errorMsg .= "\r\nTranslated name <$name> cannot be updated for language <$langId>. DB error_msg: {$this->db->_error_message()}";
            }
        }

        return $errorMsg ?: true;
    }


    public function setColourExtendDao($dao)
    {
        $this->colourExtendDao = $dao;
    }

    public function getColourExtendDao()
    {
        return $this->colourExtendDao;
    }
}
