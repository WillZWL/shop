<?php
class SupplierVo extends \BaseVo
{
    private $id;
    private $name;
    private $origin_country='';
    private $currency_id;
    private $creditor = '0';
    private $address_1;
    private $address_2;
    private $address_3;
    private $phone_1;
    private $phone_2;
    private $phone_3;
    private $fax_1;
    private $fax_2;
    private $fax_3;
    private $email;
    private $supplier_reg;
    private $sourcing_reg;
    private $note;
    private $fc_id;
    private $status = '1';

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setOriginCountry($origin_country)
    {
        if ($origin_country !== null) {
            $this->origin_country = $origin_country;
        }
    }

    public function getOriginCountry()
    {
        return $this->origin_country;
    }

    public function setCurrencyId($currency_id)
    {
        if ($currency_id !== null) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCreditor($creditor)
    {
        if ($creditor !== null) {
            $this->creditor = $creditor;
        }
    }

    public function getCreditor()
    {
        return $this->creditor;
    }

    public function setAddress1($address_1)
    {
        if ($address_1 !== null) {
            $this->address_1 = $address_1;
        }
    }

    public function getAddress1()
    {
        return $this->address_1;
    }

    public function setAddress2($address_2)
    {
        if ($address_2 !== null) {
            $this->address_2 = $address_2;
        }
    }

    public function getAddress2()
    {
        return $this->address_2;
    }

    public function setAddress3($address_3)
    {
        if ($address_3 !== null) {
            $this->address_3 = $address_3;
        }
    }

    public function getAddress3()
    {
        return $this->address_3;
    }

    public function setPhone1($phone_1)
    {
        if ($phone_1 !== null) {
            $this->phone_1 = $phone_1;
        }
    }

    public function getPhone1()
    {
        return $this->phone_1;
    }

    public function setPhone2($phone_2)
    {
        if ($phone_2 !== null) {
            $this->phone_2 = $phone_2;
        }
    }

    public function getPhone2()
    {
        return $this->phone_2;
    }

    public function setPhone3($phone_3)
    {
        if ($phone_3 !== null) {
            $this->phone_3 = $phone_3;
        }
    }

    public function getPhone3()
    {
        return $this->phone_3;
    }

    public function setFax1($fax_1)
    {
        if ($fax_1 !== null) {
            $this->fax_1 = $fax_1;
        }
    }

    public function getFax1()
    {
        return $this->fax_1;
    }

    public function setFax2($fax_2)
    {
        if ($fax_2 !== null) {
            $this->fax_2 = $fax_2;
        }
    }

    public function getFax2()
    {
        return $this->fax_2;
    }

    public function setFax3($fax_3)
    {
        if ($fax_3 !== null) {
            $this->fax_3 = $fax_3;
        }
    }

    public function getFax3()
    {
        return $this->fax_3;
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

    public function setSupplierReg($supplier_reg)
    {
        if ($supplier_reg !== null) {
            $this->supplier_reg = $supplier_reg;
        }
    }

    public function getSupplierReg()
    {
        return $this->supplier_reg;
    }

    public function setSourcingReg($sourcing_reg)
    {
        if ($sourcing_reg !== null) {
            $this->sourcing_reg = $sourcing_reg;
        }
    }

    public function getSourcingReg()
    {
        return $this->sourcing_reg;
    }

    public function setNote($note)
    {
        if ($note !== null) {
            $this->note = $note;
        }
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setFcId($fc_id)
    {
        if ($fc_id !== null) {
            $this->fc_id = $fc_id;
        }
    }

    public function getFcId()
    {
        return $this->fc_id;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
