<?php
namespace ESG\Panther\Models\CustomerService;

use ESG\Panther\Service\FaqadminService;

class FaqadminModel extends \CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->faqadminService = new FaqadminService;
    }

    public function getListCnt($where = [], $option = [])
    {
        return ["list" => $this->faqadminService->getListCnt($where, $option)];
    }

    public function get($where = [])
    {
        return $this->faqadminService->getDao()->get($where);
    }

    public function update($obj)
    {
        return $this->faqadminService->getDao()->update($obj);
    }

    public function insert($obj)
    {
        return $this->faqadminService->getDao()->insert($obj);
    }

    public function getContent($platform_id = 'WSGB')
    {
        return $this->faqadminService->getContent($platform_id);
    }

    public function save($wh = [])
    {
        return $this->faqadminService->save($wh);
    }
}

