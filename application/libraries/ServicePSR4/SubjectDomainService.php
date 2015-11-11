<?php
namespace ESG\Panther\Service;

class SubjectDomainService extends BaseService
{
    function __construct()
    {
        parent::__construct();
    }

    public function valueOf($subject = "", $subkey = "", $lang_id = "")
    {
        return $this->getDao('SubjectDomainDetailLabel')->valueOf($subject, $subkey, $lang_id);
    }

    public function getSubjListWSubjLang($subject = "", $lang_id = "")
    {
        return $this->getDao('SubjectDomainDetailLabel')->getSubjListWSubjLang($subject, $lang_id);
    }

    public function getListWSubject($subject = [], $option = [])
    {
        return $this->getDao('SubjectDomainDetail')->getListWSubject($subject, $option);
    }
}




