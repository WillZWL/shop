<?php

namespace ESG\Panther\Models\Website;
use ESG\Panther\Service\ClientService;

class ClientModel extends \CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->clientService = New clientService;
    }

    public function forgetPassword($email = '') {
        return $this->clientService->resetPassword($email);
    }

    public function updatePassword($email = '', $newPassword = '', $oldPassword = '') {
        return $this->clientService->updatePassword($email, $newPassword, $oldPassword);
    }

    public function registerSuccessEvent($obj) {
        return $this->clientService->register_success_event($obj);
    }

    public function getNewVipCustomerList() {
        return $this->clientService->getNewVipCustomerList();
    }

    public function getClient($where = array()) {
        return $this->clientService->get($where);
    }

    public function updateClient($client_obj) {
        return $this->clientService->update($client_obj);
    }

    public function includeVo() {
        $this->clientService->include_vo();
    }
}