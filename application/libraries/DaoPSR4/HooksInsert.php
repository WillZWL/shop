<?php
namespace ESG\Panther\Dao;

interface HooksInsert {

    public function triggerAfterInsert($obj);

    public function tableFieldsHooksInsert($obj);

}
