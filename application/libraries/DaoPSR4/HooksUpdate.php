<?php
namespace ESG\Panther\Dao;

interface HooksUpdate {

    public function triggerAfterUpdate($obj, $oldObj);

    public function tableFieldsHooksUpdate($obj, $oldObj);

}