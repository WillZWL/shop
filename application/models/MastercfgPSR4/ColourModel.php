<?php
namespace AtomV2\Models\Mastercfg;

use AtomV2\Service\ColourService;

class ColourModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->colourService = new ColourService;
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

    public function getList($where = [], $option = [])
    {
        return $this->colourService->getList($where, $option);
    }

    public function getListWithLang($where, $option)
    {
        return $this->colourService->getListWithLang($where, $option);
    }
}
