<?php
namespace ESG\Panther\Dao;

interface HooksUpdate {

    public function updateAfterExecute($obj);

    public function tableFieldsHooksUpdate($obj);

}