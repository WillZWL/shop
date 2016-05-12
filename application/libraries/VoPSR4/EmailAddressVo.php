<?php

class EmailAddressVo extends \BaseVo
{
    private $func_id;
    private $email;

    protected $primary_key = ['func_id', 'email'];
    protected $increment_field = '';

    public function setFuncId($func_id)
    {
        if ($func_id !== null) {
            $this->func_id = $func_id;
        }
    }

    public function getFuncId()
    {
        return $this->func_id;
    }

    public function setEmail($email)
    {
        if ($email !== null) {
            $this->email = $email;
        }
    }

    public function getEmail()
    {
        return $this->email;
    }

}
