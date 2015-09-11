<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\SubjectDomainDao;
use ESG\Panther\Dao\SubjectDomainDetailDao;
use ESG\Panther\Dao\SubjectDomainDetailLabelDao;

class SubjectDomainService extends BaseService
{

    private $subDdDao;
    private $subDdlDao;

    function __construct()
    {
        parent::__construct();
        $this->setDao(new SubjectDomainDao);
        $this->setSubDdDao(new SubjectDomainDetailDao);
        $this->setSubDdlDao(new SubjectDomainDetailLabelDao);
    }

    public function value_of($subject = "", $subkey = "", $lang_id = "")
    {
        return $this->getSubDdlDao()->valueOf($subject, $subkey, $lang_id);
    }

    public function getSubDdlDao()
    {
        return $this->subDdlDao;
    }

    public function setSubDdlDao($dao)
    {
        $this->subDdlDao = $dao;
    }

    public function getSubjListWSubjLang($subject = "", $lang_id = "")
    {
        return $this->getSubDdlDao()->getSubjListWSubjLang($subject, $lang_id);
    }

    public function getListWSubject($subject = [], $option = [])
    {
        return $this->getSubDdDao()->getListWSubject($subject, $option);
    }

    public function getSubDdDao()
    {
        return $this->subDdDao;
    }

    public function setSubDdDao($dao)
    {
        $this->subDdDao = $dao;
    }
}




