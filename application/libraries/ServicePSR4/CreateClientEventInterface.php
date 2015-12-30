<?php
namespace ESG\Panther\Service;

interface CreateClientEventInterface
{
    public function clientBeforeUpdateEvent($clientObj);
    public function clientInsertSuccessEvent($clientObj);
}
