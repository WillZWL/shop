<?php
namespace ESG\Panther\Models\Auth;

use ESG\Panther\Service\AuthenticationService;

class AuthModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->authenticationService = new AuthenticationService;
    }

    public function externalAuth($user_id, $password, $salt)
    {
        return $this->authenticationService->authUser($user_id, $password, $salt);
    }

    public function auth($user_id, $password)
    {
        return $this->authenticationService->authUser($user_id, $password);
    }

    public function checkAuthed()
    {
        return $this->authenticationService->checkAuthed();
    }

    public function deauthUser()
    {
        return $this->authenticationService->deauthUser();
    }

}
