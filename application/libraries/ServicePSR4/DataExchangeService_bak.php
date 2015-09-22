<?php
namespace ESG\Panther\Service;

class DataExchangeService extends BaseService
{

    private $import;
    private $export;

    function __construct()
    {
        parent::__construct();
    }

    public function convert(inConverter $import, outConverter $export = NULL)
    {
        $this->import = $import;
        if (empty($export)) {
            return $this->import->inConvert();
        } else {
            $this->export = $export;
            $this->export->setInput($this->import->inConvert());
            return $this->export->outConvert();
        }
    }

}