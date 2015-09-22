<?php
namespace ESG\Panther\Service;

interface ActableService
{
    public function init();

    public function run($dto);
}
