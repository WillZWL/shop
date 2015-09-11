<?php
namespace ESG\Panther\Models\Mastercfg;

use ESG\Panther\Service\ColourService;

class ColourModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->colourService = new ColourService;
    }

    public function save($data)
    {
        return $this->colourService->save($data);
    }

    public function update($obj)
    {
        return $this->colourService->update($obj);
    }

    public function insert($obj)
    {
        return $this->colourService->insert($obj);
    }

    public function get($where = [])
    {
        return $this->colourService->get($where);
    }

    public function getNumRows($where = [])
    {
        return $this->colourService->getNumRows($where);
    }

    public function getList($where = [], $option = [])
    {
        return $this->colourService->getList($where, $option);
    }

    public function getListWithLang($where, $option)
    {
        return $this->colourService->getListWithLang($where, $option);
    }
}
