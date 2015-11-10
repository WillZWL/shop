<?php
namespace ESG\Panther\Dao;

interface HooksInsert {

    public function insertAfterExecute($obj);

    public function tableFieldsHooksInsert($obj);

}
