<?php
namespace ESG\Panther\Service;

interface CreateClientInterface
{
    public function clientBeforeUpdateEvent($clientObj);
    public function clientCreateSuccessEvent($clientObj);
}
