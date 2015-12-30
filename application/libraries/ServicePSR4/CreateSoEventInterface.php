<?php
namespace ESG\Panther\Service;

interface CreateSoEventInterface
{
    public function soBeforeInsertEvent($soObj);
    public function soInsertSuccessEvent($soObj);
}
