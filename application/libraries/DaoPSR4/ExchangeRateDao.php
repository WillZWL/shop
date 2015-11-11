<?php
namespace ESG\Panther\Dao;

class ExchangeRateDao extends BaseDao
{
    private $tableName = "exchange_rate";
    private $voClassName = "ExchangeRateVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
    
    public function getExchangeRateByPlatform($platformId, $targetCurrency)
    {
        $this->db->from("platform_biz_var AS pbv");
        $this->db->join("exchange_rate AS er", "er.from_currency_id=pbv.platform_currency_id", 'INNER');
        $this->db->where('pbv.selling_platform_id', $platformId);
        $this->db->where('er.to_currency_id', $targetCurrency);
        $this->db->select("er.rate");
        $result = $this->db->get();

        if (!$result) {
            return FALSE;
        }

        return $result->result_array();
    }
}


