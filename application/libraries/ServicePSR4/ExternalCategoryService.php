<?php
namespace ESG\Panther\Service;

class ExternalCategoryService extends BaseService
{
    public function __construct() {
        parent::__construct();
    }


    public function createNewExternalCategory($obj)
    {
        $newObj = new \ExternalCategoryVo();

        // id come from VB is not reliable, should use auto-increment id
        $newObj->setId((string) $obj->id);
        $this->updateExternalCategory($newObj, $obj);

        return $newObj;
    }

    public function updateExternalCategory($newObj, $oldObj)
    {
        $newObj->setExtParty((string) $oldObj->ext_party);
        $newObj->setLevel((string) $oldObj->level);
        $newObj->setExtId((string) $oldObj->ext_id);
        $newObj->setExtName((string) $oldObj->ext_name);
        $newObj->setLangId((string) $oldObj->lang_id);
        $newObj->setCountryId((string) $oldObj->country_id);
        $newObj->setStatus((string) $oldObj->status);
    }
}


