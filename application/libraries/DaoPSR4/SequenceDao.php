<?php
namespace ESG\Panther\Dao;

class SequenceDao extends BaseDao
{
    private $table_name = "sequence";
    private $vo_class_name = "SequenceVo";
    private $seq_name = "";

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getSeqName()
    {
        return $this->seq_name;
    }

    public function setSeqName($seq_name)
    {
        return $this->seq_name = $seq_name;
    }
}


